<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Services\Admin\BlogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:blog.view|blog.create|blog.edit')->only(['index', 'show']);
        $this->middleware('permission:blog.create')->only(['create', 'store']);
        $this->middleware('permission:blog.edit')->only(['edit', 'update']);
        // $this->middleware('permission:blog.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = Blog::query();

            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('subtitle', 'LIKE', "%{$search}%")
                        ->orWhere('blog_type', 'LIKE', "%{$search}%")
                        ->orWhere('author', 'LIKE', "%{$search}%");
                });
            }

            $blogs = $query->orderBy('id', 'desc')->paginate(config('pagination.per_page'));

            // Keep search when switching pages
            $blogs->appends($request->only('search'));

            return view('admin.blog.list', compact('blogs'));
        } catch (Exception $e) {
            Log::error("Error fetching blogs: " . $e->getMessage());
            return back()->with('error', 'Failed to load blogs.');
        }
    }

    public function create()
    {
        try {
            $categories = BlogCategory::where('status', 1)->orderBy('name', 'ASC')->get();
            return view('admin.blog.add', compact('categories'));
        } catch (Exception $e) {
            Log::error("Error displaying blog form: " . $e->getMessage());
            return redirect()->route('blog.index')
                ->with('error', 'Failed to load blog form.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meta_title'      => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string|max:255',
            'meta_keywords'   => 'nullable',
            'title'           => 'required|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'author'          => 'nullable|string|max:255',
            'publish_date'    => 'nullable|date',
            'status'          => 'required|in:0,1',
            'feature_content' => 'nullable|in:0,1',
            'category_id'     => 'required|string|max:255',
            'content'         => 'required|string',

            'images'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'mobile_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $validated['feature_content'] = $request->has('feature_content') ? 1 : 0;

        DB::beginTransaction();
        try {
            BlogService::create($validated, $request);
            DB::commit();

            return redirect()->route('blog.index')
                ->with('success', 'Blog created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error saving blog: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Something went wrong while saving the blog.');
        }
    }

    public function edit(Blog $blog)
    {
        try {
            $categories = BlogCategory::where('status', 1)->orderBy('name', 'ASC')->get();
            return view('admin.blog.add', compact('blog','categories'));
        } catch (Exception $e) {
            Log::error("Error displaying blog form: " . $e->getMessage());
            return redirect()->route('blog.index')
                ->with('error', 'Failed to load blog form.');
        }
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'meta_title'      => 'nullable|string|max:255',
            'meta_description'=> 'nullable|string|max:255',
            'meta_keywords'   => 'nullable',
            'title'           => 'required|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'author'          => 'nullable|string|max:255',
            'publish_date'    => 'nullable|date',
            'status'          => 'required|in:0,1',
            'feature_content' => 'nullable|in:0,1',
            'category_id'     => 'required|string|max:255',
            'content'         => 'required|string',
            'images'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $validated['feature_content'] = $request->has('feature_content') ? 1 : 0;

        DB::beginTransaction();
        try {
            BlogService::update($blog, $validated, $request);
            DB::commit();

            return redirect()->route('blog.index')
                ->with('success', 'Blog updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating blog: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Something went wrong while updating the blog.');
        }
    }

    public function show(Blog $blog)
    {
        return view('admin.blog.show', compact('blog'));
    }
}