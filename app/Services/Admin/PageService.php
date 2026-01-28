<?php

namespace App\Services\Admin;

use App\Models\Page;

class PageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getPageBySlug($slug)
    {
        return Page::where('slug', $slug)->firstOrFail();
    }
}
