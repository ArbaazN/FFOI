<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class BlogCategoryController extends Controller
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
            $query = BlogCategory::query();

            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            }

            $categories = $query->orderBy('name', 'asc')->paginate(config('pagination.per_page'));

            // Keep search when switching pages
            $categories->appends($request->only('search'));

            return view('admin.blog.categorylist', compact('categories'));
        } catch (Exception $e) {
            Log::error("Error fetching blogs: " . $e->getMessage());
            return back()->with('error', 'Failed to load blogs.');
        }
    }

    public function create()
    {
        return view('admin.blog.addcategory');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|unique:blog_categories,name',
            'status' => 'required|boolean'
        ]);

        try {
            $data['slug'] = Str::slug($request->name);

            BlogCategory::create($data);

            return redirect()
                ->route('blog.categories.index')
                ->with('success', 'Blog Category created successfully.');
        } catch (Exception $e) {
            Log::error('Blog Category Create Failed: ' . $e->getMessage(), [
                'data' => $data
            ]);

            return back()
                ->with('error', 'Failed to create blog category. Please try again.')
                ->withInput();
        }
    }


    public function edit(BlogCategory $category)
    {
        return view('admin.blog.addcategory', compact('category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $data = $request->validate([
            'name'   => 'required|unique:blog_categories,name,' . $category->id,
            'status' => 'required|boolean'
        ]);

        try {
            $data['slug'] = Str::slug($request->name);

            $category->update($data);

            return redirect()
                ->route('blog.categories.index')
                ->with('success', 'Blog Category updated successfully.');
        } catch (Exception $e) {
            Log::error('Blog Category Update Failed: ' . $e->getMessage(), [
                'id'   => $category->id,
                'data' => $data
            ]);

            return back()
                ->with('error', 'Failed to update blog category. Please try again.')
                ->withInput();
        }
    }

    public function destroy(BlogCategory $blog_category)
    {
        $blog_category->delete();
        return back()->with('success', 'Deleted Successfully');
    }
}
