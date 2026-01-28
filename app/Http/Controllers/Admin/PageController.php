<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Media;
use App\Models\Admin\Programs;
use App\Models\Admin\Page;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:page.view|page.create|page.edit')->only(['index', 'show']);
        $this->middleware('permission:page.create')->only(['create', 'store']);
        $this->middleware('permission:page.edit')->only(['edit', 'update']);
        // $this->middleware('permission:page.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = Page::query();

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%$search%")
                        ->orWhere('slug', 'LIKE', "%$search%");
                });
            }

            $pages = $query->orderBy('title', 'asc')->paginate(config('pagination.per_page'));

            // Preserve search term in pagination links
            $pages->appends($request->only('search'));

            return view('admin.pages.list', compact('pages'));
        } catch (Exception $e) {
            Log::error("Error fetching pages: " . $e->getMessage());
            return back()->with('error', 'Unable to load pages.');
        }
    }

    public function create()
    {
        try {
            return view('admin.pages.create');
        } catch (Exception $e) {
            Log::error("Error loading create page: " . $e->getMessage());
            return back()->with('error', 'Unable to open create page.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title'             => 'required',
                'slug'              => 'required|unique:pages,slug',
                'type'              => 'required',
                'meta_title'        => 'nullable|string|max:60',
                'meta_description'  => 'nullable|string|max:160',
            ]);

            $jsonFile = resource_path("page-templates/{$request->type}.json");

            $contentJson = File::exists($jsonFile)
                ? File::get($jsonFile)
                : json_encode(['sections' => []]);

            Page::create([
                'title'   => $request->title,
                'slug'    => $request->slug,
                'type'    => $request->type,
                'content' => $contentJson,
            ]);

            return redirect()
                ->route('pages.index')
                ->with('success', 'Page created successfully.');
        } catch (Exception $e) {
            Log::error("Error creating page: " . $e->getMessage());
            return back()->with('error', 'Failed to create page.')->withInput();
        }
    }

    public function edit(Page $page)
    {
        // $page = Page::find(24);
        // $json = $page->content;
        // $sizeKB = strlen($json) / 1024;
        // dd("Page JSON size = {$sizeKB} KB");

        $raw = json_decode($page->content, true) ?? [];
        $content = isset($raw['sections']) ? $raw : ['sections' => []];

        foreach ($content['sections'] as &$section) {

            $convert = function (&$value) use (&$convert) {

                // -----------------------------------
                // 1) JSON encoded list string → decode to array
                // -----------------------------------
                if (is_string($value) && str_starts_with(trim($value), '[') && str_ends_with(trim($value), ']')) {
                    $decoded = json_decode($value, true);

                    // If decoded into array of strings, replace value
                    if (is_array($decoded)) {
                        $value = $decoded;
                    }
                }

                // -----------------------------------
                // 2) ARRAY OF STRINGS → convert to <ul>
                // -----------------------------------
                if (is_array($value) && isset($value[0]) && is_string($value[0])) {

                    $ul = "<ul>";

                    foreach ($value as $text) {
                        $clean = str_replace('\/', '/', $text);
                        $clean = json_decode('"' . $clean . '"');
                        $clean = strip_tags($clean, '<strong><b><i><u><em>');
                        $ul .= "<li>{$clean}</li>";
                    }

                    $ul .= "</ul>";
                    $value = $ul;
                    return;
                }

                // -----------------------------------
                // 3) Nested array → recurse
                // -----------------------------------
                if (is_array($value)) {
                    foreach ($value as &$v) {
                        $convert($v);
                    }
                }
            };

            if (isset($section['data'])) {
                $convert($section['data']);
            }
        }

        return view('admin.pages.create', compact('page', 'content'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|max:255',
            'slug'  => 'required|max:255',
        ]);

        // Combine input + files (keeps structure: sections[index][data][field][files] etc.)
        $sectionsInput = $request->input('sections', []);
        $sectionFiles  = $request->file('sections', []);

        // Merge uploaded files into input structure
        $sectionsInput = array_replace_recursive($sectionsInput, $sectionFiles);

        // echo "<pre>";
        // print_r($sectionsInput);
        // exit;

        $resultSections = [];

        foreach ($sectionsInput as $idx => $sec) {

            if (!isset($sec['type'])) continue;

            $type = $sec['type'];
            $status = $sec['status'] ?? 'enable';
            $data = $sec['data'] ?? [];

            // Normalize and handle gallery merge / uploads
            $cleanData = $this->normalizeForSaving($data, null, $page);

            $resultSections[] = [
                'type' => $type,
                'status' => $status,
                'data' => $cleanData
            ];
        }

        $page->title = $request->title;
        $page->slug  = $request->slug;
        $page->meta_title       = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->meta_keys        = $request->meta_keys;

        $page->content = json_encode(
            ['sections' => $resultSections],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        $page->save();

        return back()->with('success', 'Page updated successfully.');
    }

    protected function storeMedia(\Illuminate\Http\UploadedFile $file, ?Page $page = null, ?int $oldMediaId = null): int
    {
        $disk = Storage::disk('public');

        // store in pages/{slug}
        $slugFolder = $page ? 'pages/' . $page->slug : 'pages/general';

        if (!$disk->exists($slugFolder)) {
            $disk->makeDirectory($slugFolder);
        }

        // If we are updating an existing media record, we can reuse it
        $media = null;
        if ($oldMediaId) {
            $media = Media::find($oldMediaId);
        }

        // if updating existing, optionally delete old file
        if ($media && $media->file_path && $disk->exists($media->file_path)) {
            $disk->delete($media->file_path);
        }

        // store new file
        $storedPath = $file->store($slugFolder, 'public'); // returns e.g. pages/home/abc.jpg
        $fileName   = basename($storedPath);
        $fileUrl    = asset('storage/' . $storedPath);
        $mime       = $file->getClientMimeType();
        $size       = $file->getSize();

        $type = Str::startsWith($mime, 'image/')
            ? 'image'
            : (Str::startsWith($mime, 'video/') ? 'video' : 'file');

        if ($media) {
            // update existing media record (keep same ID)
            $media->update([
                'file_name' => $fileName,
                'file_path' => $storedPath,
                'file_url'  => $fileUrl,
                'mime_type' => $mime,
                'type'      => $type,
                'size'      => $size,
                'page_id'   => $page?->id,
            ]);
        } else {
            // create new
            $media = Media::create([
                'file_name' => $fileName,
                'file_path' => $storedPath,
                'file_url'  => $fileUrl,
                'mime_type' => $mime,
                'type'      => $type,
                'size'      => $size,
                'page_id'   => $page?->id,
            ]);
        }

        return $media->id;
    }

    protected function ensureMediaFromUrl(string $url, ?Page $page = null): int
    {
        // If it already exists, reuse it
        $existing = Media::where('file_url', $url)->first();
        if ($existing) {
            return $existing->id;
        }

        // Download the file to local storage
        try {
            $fileContents = @file_get_contents($url);
        } catch (\Exception $e) {
            return 0; // fallback
        }

        if (!$fileContents) {
            return 0;
        }

        $slugFolder = $page ? 'pages/' . $page->slug : 'pages/general';

        if (!Storage::disk('public')->exists($slugFolder)) {
            Storage::disk('public')->makeDirectory($slugFolder);
        }

        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $storedPath = $slugFolder . '/' . uniqid() . '_' . $fileName;

        Storage::disk('public')->put($storedPath, $fileContents);

        $fileUrl = asset('storage/' . $storedPath);

        $media = Media::create([
            'file_name' => $fileName,
            'file_path' => $storedPath,
            'file_url'  => $fileUrl,
            'mime_type' => null,
            'type'      => 'image',
            'size'      => null,
            'page_id'   => $page?->id,
        ]);

        return $media->id;
    }

    private function normalizeForSaving($value, $oldValue = null, ?Page $page = null)
    {
        if ($value === null) {
            return "";
        }

        if ($value === '') {
            return '';
        }

        if (is_array($value) && isset($value['_is_gallery'])) {

            $oldImages = json_decode($value['old'] ?? '[]', true) ?: [];
            $keptImages = $value['keep'] ?? [];

            // NEW UPLOADED IMAGES
            $newImages = [];
            if (!empty($value['files'])) {
                foreach ($value['files'] as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $newImages[] = $this->storeMedia($file, $page);
                    }
                }
            }

            // FINAL = kept old images + new images
            $final = array_merge($keptImages, $newImages);

            // IMPORTANT: cast all values to INTEGER
            $final = array_map('intval', $final);

            return $final;
        }

        // 1) Pattern: ['old' => ..., 'file' => ...] - single image field pair
        if (is_array($value) && array_key_exists('old', $value) && array_key_exists('file', $value)) {

            $oldMediaIdRaw = $value['old'] ?? null;
            $oldMediaId = is_numeric($oldMediaIdRaw) ? (int) $oldMediaIdRaw : null;

            // New uploaded file
            if ($value['file'] instanceof \Illuminate\Http\UploadedFile) {
                return $this->storeMedia($value['file'], $page, $oldMediaId);
            }

            // No new file → keep old media_id if present
            if (!empty($oldMediaId)) {
                return (int) $oldMediaId;
            }

            return null;
        }

        // 2) Pattern: ['old' => xyz] but NO 'file' key (single image existing)
        if (is_array($value) && array_key_exists('old', $value) && !array_key_exists('file', $value)) {
            if (!empty($value['old'])) {
                return is_numeric($value['old']) ? (int) $value['old'] : $value['old'];
            }
            return null;
        }

        // 3) Single UploadedFile
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            return $this->storeMedia($value, $page, $oldValue ? (int)$oldValue : null);
        }

        // 4) If this is a media id already, just cast to int
        if (is_numeric($value)) {
            return (int) $value;
        }

        // 5) If string and looks like an image/video URL -> convert to Media id (so DB always has IDs)
        if (is_string($value) && preg_match('/\\.(jpg|jpeg|png|gif|webp|mp4|webm|ogg)$/i', $value)) {
            return $this->ensureMediaFromUrl($value, $page);
        }

        if (is_string($value)) {
            return $this->cleanEmptyHtml($value);
        }

        // 6) UL/LI HTML list → convert to array of strings
        if (is_string($value) && str_contains($value, '<li>')) {
            preg_match_all('/<li>(.*?)<\/li>/s', $value, $matches);
            return array_map(function ($item) {
                return trim(strip_tags($item, '<strong><b><i><u><em>'));
            }, $matches[1]);
        }

        // 7) Nested arrays → recursive normalize (supports nested repeaters)
        // if (is_array($value)) {

        //     $isList = array_keys($value) === range(0, count($value) - 1);

        //     // Case A: array of objects (repeater / nested repeater)
        //     if ($isList) {
        //         $normalized = [];

        //         foreach ($value as $idx => $item) {
        //             $normalized[$idx] = $this->normalizeForSaving($item, null, $page);
        //         }

        //         return $normalized;
        //     }

        //     // Case B: associative object
        //     foreach ($value as $k => $v) {
        //         $oldKey = $k . '_old';
        //         $old    = $value[$oldKey] ?? null;

        //         if ($v === null) {
        //             $value[$k] = '';
        //             continue;
        //         }

        //         $value[$k] = $this->normalizeForSaving($v, $old, $page);
        //         unset($value[$oldKey]);
        //     }

        //     return $value;
        // }

        if (is_array($value)) {

            // 🔥 Detect numeric list EVEN IF keys are strings ("0","1")
            $keys = array_keys($value);
            $isList = !array_diff($keys, range(0, count($keys) - 1));

            // Case A: repeater / list → FORCE numeric array
            if ($isList) {
                $normalized = [];

                foreach ($value as $item) {
                    $normalized[] = $this->normalizeForSaving($item, null, $page);
                }

                // 🔒 THIS LINE GUARANTEES JSON ARRAY []
                return array_values($normalized);
            }

            // Case B: associative object
            foreach ($value as $k => $v) {
                $oldKey = $k . '_old';
                $old    = $value[$oldKey] ?? null;

                if ($v === null) {
                    $value[$k] = '';
                    continue;
                }

                $value[$k] = $this->normalizeForSaving($v, $old, $page);
                unset($value[$oldKey]);
            }

            return $value;
        }

        // 8) Everything else: text, numbers, HTML, etc. → store as is
        return $value;
    }

    private function cleanEmptyHtml(string $html): string
    {
        $clean = trim($html);

        $emptyPatterns = [
            '<br>',
            '<br/>',
            '<br />',
            '<p><br></p>',
            '<p><br /></p>',
            '<p>&nbsp;</p>',
            '&nbsp;',
        ];

        if (in_array($clean, $emptyPatterns, true)) {
            return '';
        }

        // visually empty HTML
        $textOnly = trim(
            str_replace("\xc2\xa0", ' ', strip_tags($clean))
        );

        return $textOnly === '' ? '' : $html;
    }

    public function destroy(Page $page)
    {
        try {
            $page->delete();
            return redirect()
                ->route('pages.index')
                ->with('success', 'Page deleted successfully.');
        } catch (Exception $e) {
            Log::error("Error deleting page: " . $e->getMessage());
            return back()->with('error', 'Unable to delete page.');
        }
    }
}
