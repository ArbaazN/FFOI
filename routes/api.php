<?php

use App\Http\Controllers\API\BlogApiController;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\UtmApiController;
use App\Http\Controllers\API\WebsiteApiController;
use App\Http\Controllers\API\ContactController;

use Illuminate\Support\Facades\Route;

//Blogs
Route::middleware('api_key')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/allBlogs', [BlogApiController::class, 'latestBlogs']);
    Route::get('/blog/{slug}', [BlogApiController::class, 'blogDetail']);

    Route::get('/utm/{name}', [UtmApiController::class, 'getUtmLink']);
    Route::get('/settings', [WebsiteApiController::class, 'index']);

    //pages
    // Route::get('/pages', [PageController::class, 'index']);
    Route::get('/{slug}', [PageController::class, 'handle'])->where('slug', '.*');

});

Route::post('contact/save', [ContactController::class, 'store']);
