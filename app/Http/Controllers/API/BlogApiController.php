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
            $blogs = Blog::with('category')->where('status', 1)
                ->whereDate('publish_date', '<=', now()->toDateString())
                ->orderBy('publish_date', 'DESC')
                ->take(4)
                ->get()
                ->map(fn($blog) => [
                    'title'         => $blog->title,
                    'subtitle'      => $blog->subtitle,
                    'author'        => $blog->author,
                    'publish_date'  => optional($blog->publish_date)->format('Y-m-d'),
                    'blog_type'     => $blog->category->name,
                    'slug'          => $blog->slug,
                    'image_url'     => $blog->image_url,
                    // 'content'       => $blog->decoded_content,
                    'feature_content'   => (bool) ($blog->feature_content ?? false),
                    'author_image_url'  => $blog->author_image_url,
                    'author_desc'       => $blog->author_desc,
                    'fb_url'            => $blog->fb_url,
                    'twitter_url'       => $blog->twitter_url,
                    'insta_url'         => $blog->insta_url,
                    'linkedIn_url'      => $blog->linkedIn_url,
                    'yt_url'            => $blog->yt_url,
                    'faqs'              => collect(json_decode($blog->faqs_question ?? '[]'))
                                                ->map(function ($question, $index) use ($blog) {
                                                    $answers = json_decode($blog->faqs_answer ?? '[]');
                                                    return [
                                                        'question' => $question,
                                                        'answer' => $answers[$index] ?? ''
                                                    ];
                                                }),
                ]);

            // $categories = BlogCategory::where('status', 1)
            //     ->orderBy('name', 'ASC')
            //     ->get()
            //     ->map(fn($category) => [
            //         $category->name,
            //     ]);
            $categories = BlogCategory::where('status', 1)
                        ->orderBy('name', 'ASC')
                        ->get()
                        ->map(function ($category) {

                            $blogs = Blog::where('category_id', $category->id)
                                ->where('status', 1)
                                ->whereDate('publish_date', '<=', now()->toDateString())
                                ->orderBy('publish_date', 'DESC')
                                ->get()
                                ->map(function ($blog) {
                                    return [
                                        'title'         => $blog->title,
                                        'subtitle'      => $blog->subtitle,
                                        'author'        => $blog->author,
                                        'slug'  => $blog->slug,
                                        'image_url' => $blog->image_url,
                                        'publish_date' => optional($blog->publish_date)->format('Y-m-d'),
                                        'author_image_url'  => $blog->author_image_url,
                                        'author_desc'       => $blog->author_desc,
                                        'fb_url'            => $blog->fb_url,
                                        'twitter_url'       => $blog->twitter_url,
                                        'insta_url'         => $blog->insta_url,
                                        'linkedIn_url'      => $blog->linkedIn_url,
                                        'yt_url'            => $blog->yt_url,
                                    ];
                                });

                            return [
                                'name'  => $category->name,
                                'blogs' => $blogs
                            ];
                        });

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
            $blog = Blog::with('category')->where('slug', $slug)->first();
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
                                'slug',
                                'image_url',
                                'mobile_image_url', 
                                'decoded_content',
                                'feature_content',
                                'author_image_url',
                                'author_desc',
                                'fb_url',
                                'twitter_url',
                                'insta_url',
                                'linkedIn_url',
                                'yt_url',
                                'faqs_question',
                                'faqs_answer'
                            ])
                             + [
                                'blog_type' => $blog->category->name ?? null
                            ],
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

