<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Media;
use App\Models\Admin\Page;
use App\Services\Admin\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    //
    protected $pages;
    public function __construct(PageService $pages)
    {
        $this->pages = $pages;
    }

    public function index()
    {
        try {
            // Fetch all published pages
            $pages = Page::where('status', 'published')
                ->orderBy('menu_order', 'ASC')
                ->get();

            // Helper for mapping a page with target + href
            $mapPage = fn($p) => [
                "name"   => $p->title,
                "href"   => "/" . $p->slug,
                "target" => $p->target ?? "_self"
            ];

            $mapPage1 = fn($p) => [
                "title"   => $p->title,
                "href"   => "/" . $p->slug,
                "target" => $p->target ?? "_self"
            ];

            // Group pages by slug prefix
            $group = fn($prefix) =>
            $pages->filter(fn($p) => str_starts_with($p->slug, $prefix))
                ->map($mapPage)
                ->values();

            // Helper for single page
            $page = fn($slug) => $pages->firstWhere("slug", $slug);

            // Build menu JSON
            $menu = [
                [
                    "title"  => "ASBS Experience",
                    "target" => "_self",
                    "dropdownData" => [
                        $mapPage($page("about-us")),
                        $mapPage($page("contact-us")),
                        $mapPage($page("placements")),
                        $mapPage($page("events-and-engagement")),

                        [
                            "name" => "Learning Approach",
                            "subDropdownData" => $group("learning-experience/")
                        ],

                        [
                            "name" => "Faculty",
                            "subDropdownData" => $group("faculty/")
                        ],
                    ]
                ],

                [
                    "title"  => "Programs & Campuses",
                    "target" => "_self",
                    "dropdownData" => [
                        [
                            "name" => "Who Should Apply",
                            "subDropdownData" => $group("who-should-apply/")
                        ],
                        [
                            "name" => "Campuses",
                            "subDropdownData" => $group("campuses/")
                        ],
                        [
                            "name" => "MMS Programs",
                            "subDropdownData" => $group("programs/")
                        ],
                        [
                            "name" => "PDGM Programs",
                            "subDropdownData" => $group("programs/")
                        ],
                    ]
                ],

                [
                    "target" => "blank",
                    "title" => "Admissions 2026-28",
                    "href" => ""
                    // onClick: () => setOpenAdmissionModal(true),
                ],
                // Single page direct links
                $mapPage1($page("partner-with-us")),
                $mapPage1($page("blogs")),
            ];

            return response()->json([
                "status" => true,
                "menu"   => $menu
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status"  => false,
                "message" => "Something went wrong",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    public function handle($slug)
    {
        $parts = explode('/', $slug);

        if (count($parts) === 1) {
            $finalSlug = Str::slug($parts[0]);
        } else {
            $module = $parts[0];
            $name   = $parts[1];

            $finalSlug = $module . '/' . Str::slug($name);
        }

        try {
            $page = Page::where('slug', $finalSlug)->first();

            if (!$page) {
                return response()->json([
                    'status' => false,
                    'message' => 'Page not found.',
                    'slug' => $finalSlug
                ], 404);
            }

            $content = json_decode($page->content, true);

            // Convert sections array → object using "type"
            $sectionsObject = [];

            if (isset($content['sections']) && is_array($content['sections'])) {
                foreach ($content['sections'] as $section) {
                    if (($section['status'] ?? 'enable') !== 'enable') {
                        continue;
                    }
                    if (isset($section['type'])) {
                        $resolvedData = $this->resolveMediaInData($section['data'] ?? []);
                        $sectionsObject[$section['type']] = $resolvedData;
                    }
                }
            }

            return response()->json([
                'status' => true,
                'data'   => [
                    // 'id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'meta_title' => $page->meta_title,
                    'meta_description' => $page->meta_description,
                    'meta_keys' => $page->meta_keys,
                    // 'original_content' => $page->original_content,
                    'content' => [
                        'sections' => $sectionsObject
                    ],
                    // 'created_at' => $page->created_at,
                    // 'updated_at' => $page->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    private function resolveMediaInData($data)
    {
        // Media-related field names
        $mediaKeys = [
            'image',
            'iconImg',
            'iconImage',
            'multipleImages',
            'backgroundImage',
            'profileImg',
            'companyLogo',
            'video',
            'backgroundVideo',
            'bannerImage',
            'partnerMultipleImages',
            'logoMultipleImages',
            'backgroundMobileImage'
        ];

        // Normalize null
        if ($data === null) {
            return '';
        }

        // Arrays (including repeaters and galleries)
        if (is_array($data)) {

            $clean = [];

            foreach ($data as $key => $value) {

                // 🔹 Remove CMS-only suffix "Input"
                if (is_string($key)) {
                    foreach (['Input', 'Textarea'] as $suffix) {
                        if (str_ends_with($key, $suffix)) {
                            $key = substr($key, 0, -strlen($suffix));
                            break;
                        }
                    }
                }

                // 🔹 MULTIPLE IMAGES (array of media IDs)
                if (
                    in_array($key, $mediaKeys, true) &&
                    is_array($value)
                ) {
                    $clean[$key] = Media::whereIn('id', $value)
                        ->pluck('file_url')
                        ->values()
                        ->toArray();
                    continue;
                }

                // 🔹 SINGLE IMAGE (media ID)
                if (
                    in_array($key, $mediaKeys, true) &&
                    is_numeric($value)
                ) {
                    $clean[$key] = Media::where('id', $value)->value('file_url');
                    continue;
                }

                // 🔹 Numeric (real numbers)
                if (is_numeric($value)) {
                    $clean[$key] = (int) $value;
                    continue;
                }

                // 🔹 Recurse
                $clean[$key] = $this->resolveMediaInData($value);
            }

            return $clean;
        }

        // Everything else
        return $data;
    }
}
