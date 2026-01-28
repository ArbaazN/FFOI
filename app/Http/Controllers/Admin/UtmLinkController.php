<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\UtmLinks;
use Illuminate\Http\Request;

class UtmLinkController extends Controller
{
    public function __construct()
    {
        // Resource permissions
        $this->middleware('permission:utm.view|utm.edit')->only(['index', 'show']);
        $this->middleware('permission:utm.create')->only(['create', 'store']);
        $this->middleware('permission:utm.edit')->only(['edit', 'update']);
        $this->middleware('permission:utm.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $links = UtmLinks::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('original_url', 'like', "%{$search}%")
                    ->orWhere('utm_source', 'like', "%{$search}%")
                    ->orWhere('utm_campaign', 'like', "%{$search}%");
            })
                ->orderBy('created_at', 'desc')
                ->paginate(config('pagination.per_page'));

            return view('admin.utm_links.index', compact('links', 'search'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $utmLink = new UtmLinks();
        return view('admin.utm_links.create', compact('utmLink'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['full_url'] = $this->buildUtmUrl(
            $data['original_url'],
            $data['utm_source'] ?? null,
            $data['utm_medium'] ?? null,
            $data['utm_campaign'] ?? null,
            $data['utm_term'] ?? null,
            $data['utm_content'] ?? null
        );

        UtmLinks::create($data);

        return redirect()
            ->route('utm-links.index')
            ->with('success', 'UTM link created successfully.');
    }

    public function edit(UtmLinks $utmLink)
    {
        return view('admin.utm_links.create', compact('utmLink'));
    }

    public function update(Request $request, UtmLinks $utmLink)
    {
        $data = $this->validateData($request);

        $data['full_url'] = $this->buildUtmUrl(
            $data['original_url'],
            $data['utm_source'] ?? null,
            $data['utm_medium'] ?? null,
            $data['utm_campaign'] ?? null,
            $data['utm_term'] ?? null,
            $data['utm_content'] ?? null
        );

        $utmLink->update($data);

        return redirect()
            ->route('utm-links.index')
            ->with('success', 'UTM link updated successfully.');
    }

    public function destroy(UtmLinks $utmLink)
    {
        $utmLink->delete();

        return redirect()
            ->route('utm-links.index')
            ->with('success', 'UTM link deleted successfully.');
    }

    // ----------------- Helpers -----------------

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name'          => ['nullable', 'string', 'max:255'],
            'original_url'  => ['required', 'url'],
            'utm_source'    => ['nullable', 'string', 'max:255'],
            'utm_medium'    => ['nullable', 'string', 'max:255'],
            'utm_campaign'  => ['nullable', 'string', 'max:255'],
            'utm_term'      => ['nullable', 'string', 'max:255'],
            'utm_content'   => ['nullable', 'string', 'max:255'],
            'is_active'     => ['nullable'], // checkbox
            'notes'         => ['nullable', 'string'],
        ], [
            'original_url.required' => 'Base URL is required.',
            'original_url.url'      => 'Base URL must be a valid URL.',
        ]);
    }

    protected function buildUtmUrl(
        string $baseUrl,
        ?string $source,
        ?string $medium,
        ?string $campaign,
        ?string $term = null,
        ?string $content = null
    ): string {
        $params = [];

        if (!empty($source)) {
            $params['utm_source'] = $source;
        }
        if (!empty($medium)) {
            $params['utm_medium'] = $medium;
        }
        if (!empty($campaign)) {
            $params['utm_campaign'] = $campaign;
        }
        if (!empty($term)) {
            $params['utm_term'] = $term;
        }
        if (!empty($content)) {
            $params['utm_content'] = $content;
        }

        if (empty($params)) {
            return $baseUrl;
        }

        $queryString = http_build_query($params);

        // Append properly whether URL already has ?
        if (str_contains($baseUrl, '?')) {
            return $baseUrl . '&' . $queryString;
        }

        return $baseUrl . '?' . $queryString;
    }
}
