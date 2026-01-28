<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use App\Models\Admin\Campus;
use App\Models\Admin\Programs;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'campus_count'  => Campus::where('status', '1')->count(),
            'program_count' => Programs::where('status', '1')->count(),
            'blog_count'    => Blog::where('status', '1')->count(),
        ];

        return view('admin.home',compact('stats'));
    }
}
