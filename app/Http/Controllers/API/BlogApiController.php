<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use Illuminate\Support\Facades\Log;

class BlogApiController extends Controller
{
    public function latestBlogs()
    {
        try {
            $blogs = Blog::where('status', 1)
                ->whereDate('publish_date', '<=', now()->toDateString())
                ->orderBy('publish_date', 'DESC')
                ->take(4)
                ->get()
                ->map(fn($blog) => [
                    'title'         => $blog->title,
                    'subtitle'      => $blog->subtitle,
                    'author'        => $blog->author,
                    'publish_date'  => optional($blog->publish_date)->format('Y-m-d'),
                    'blog_type'     => $blog->blog_type,
                    'slug'          => $blog->slug,
                    'image_url'     => $blog->image_url,
                    'content'       => $blog->decoded_content,
                    'is_featured'   => (bool) ($blog->feature_content ?? false),
                ]);

            $categories = BlogCategory::where('status', 1)
                ->orderBy('name', 'ASC')
                ->get()
                ->map(fn($category) => [
                    $category->name,
                ]);

            return response()->json([
                'status' => true,
                'data'   => [
                    'blogs'  => $blogs,
                    'categories' => $categories,
                ],
            ], 200);
        } catch (\Throwable $e) {

            Log::error('Latest Blogs API Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching blogs.',
            ], 500);
        }
    }

    public function blogDetail($slug)
    {
        try {
            $blog = Blog::where('slug', $slug)->first();

            if (!$blog) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Blog not found',
                ], 404);
            }

            $suggested = Blog::where('status', 1)
                ->where('id', '!=', $blog->id)
                ->where('category_id', $blog->category_id)
                ->orderBy('publish_date', 'DESC')
                ->take(3)
                ->get()
                ->map(fn($item) => [
                    'title'         => $item->title,
                    'subtitle'      => $item->subtitle,
                    'slug'          => $item->slug,
                    'image_url'     => $item->image_url,
                    'mobile_image_url' =>$item->mobile_image_url,
                    'author'        => $item->author,
                    'publish_date'  => optional($item->publish_date)->format('Y-m-d'),
                ]);
            return response()->json([
                'status' => true,
                'data' => [
                    'meta_title'       => $blog->meta_title,
                    'meta_description' => $blog->meta_description,
                    'meta_keywords'    => $blog->meta_keywords,

                    'content' => [
                        'sections' => [
                            'blog' => $blog->only([
                                'title',
                                'subtitle',
                                'author',
                                'publish_date',
                                'blog_type',
                                'slug',
                                'image_url',
                                'mobile_image_url', 
                                'decoded_content',
                                'is_featured',
                            ]),
                            'suggested_blogs' => $suggested,
                        ],
                    ],
                ],
            ], 200);
        } catch (\Throwable $e) {

            Log::error('Blog Detail API Error', [
                'slug'  => $slug,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching blog details.',
            ], 500);
        }
    }
}
