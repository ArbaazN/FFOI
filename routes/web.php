<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UtmLinkController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use Illuminate\Support\Facades\Route;


// Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        //Users
        Route::get('/users/{user}/permission', [UserController::class, 'editPermission'])
            ->name('users.permission');

        Route::post('/users/{user}/permission', [UserController::class, 'updatePermission'])
            ->name('users.permission.update');

        Route::resource('users', UserController::class)->names('users');

        Route::get('/campuses/{campus}/duplicate', [CampusController::class, 'duplicateForm'])->name('campuses.duplicate');
        Route::post('/campuses/{campus}/duplicate', [CampusController::class, 'duplicateStore'])->name('campuses.duplicate.store');
        Route::resource('campuses', CampusController::class)->names('campuses');

        Route::post('/program/{program}/duplicate', [ProgramController::class, 'duplicateStore'])->name('program.duplicate.store');
        Route::resource('program', ProgramController::class)->names('programs');

        Route::resource('pages', PageController::class)->names('pages');

        Route::resource('blog/category', BlogCategoryController::class)->names('blog.categories');
        Route::resource('blog', BlogController::class)->names('blog');

        Route::get('settings', [WebsiteSettingController::class, 'index'])->name('settings');
        Route::post('settings/update', [WebsiteSettingController::class, 'update'])->name('settings.update');

        Route::resource('utm-links', UtmLinkController::class)->except('show');
    });
// });
