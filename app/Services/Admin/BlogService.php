<?php

namespace App\Services\Admin;

use App\Models\Admin\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogService
{
    public static function generateSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (
            Blog::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public static function uploadImage($blog, $request, $field, $folder)
    {
        if (!$request->hasFile($field)) {
            return $blog?->{$field} ?? null;
        }

        // Delete old image
        if ($blog && $blog->{$field} && Storage::disk('public')->exists($blog->{$field})) {
            Storage::disk('public')->delete($blog->{$field});
        }

        return $request->file($field)->store($folder, 'public');
    }

    public static function create($validated, $request)
    {
        $validated['slug'] = self::generateSlug($validated['title']);

        $validated['images'] = self::uploadImage(
            null,
            $request,
            'images',
            'blog-images'
        );

        $validated['mobile_image'] = self::uploadImage(
            null,
            $request,
            'mobile_image',
            'blog-mobile-images'
        );

        return Blog::create($validated);
    }

    public static function update(Blog $blog, $validated, $request)
    {
        $validated['slug'] = self::generateSlug($validated['title'], $blog->id);

        $validated['images'] = self::uploadImage(
            $blog,
            $request,
            'images',
            'blog-images'
        );

        $validated['mobile_image'] = self::uploadImage(
            $blog,
            $request,
            'mobile_image',
            'blog-mobile-images'
        );

        $blog->update($validated);

        return $blog;
    }
}
