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
use App\Http\Controllers\Admin\WebinarController;
use App\Http\Controllers\Admin\MemberShipController;
use App\Http\Controllers\Admin\EnquiryController;
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
        Route::post('blog/upload-editor-image', [BlogController::class, 'uploadEditorImage'])
                    ->name('blog.uploadEditorImage');

        Route::get('webinar/upcomingsession', [WebinarController::class,'sessionList'])->name('webinar.session.list');
        Route::get('webinar/upcomingsession/create/{id?}', [WebinarController::class,'sessionCreate'])->name('webinar.session.create');
        Route::post('webinar/upcomingsession/store', [WebinarController::class,'sessionStore'])->name('webinar.session.store');
        Route::post('webinar/upcomingsession/update/{id?}', [WebinarController::class,'sessionUpdate'])->name('webinar.session.update');

        Route::get('webinar/session-details', [WebinarController::class,'sessionDetailList'])->name('webinar.session.detail.list');
        Route::get('webinar/session-details/create', [WebinarController::class,'sessionDetailsAdd'])->name('webinar.session.detail.create');
        Route::get('webinar/session-details/edit/{id?}', [WebinarController::class,'sessionDetailsAdd'])->name('webinar.session.detail.edit');
        Route::get('webinar/session-details/{id}/registrations', [WebinarController::class,'sessionRegistrationList'])->name('webinar.session.detail.registrations');
        Route::post('webinar/session-details/store', [WebinarController::class,'sessionDetailStore'])->name('webinar.session.detail.store');
        Route::post('webinar/session-details/update/{id?}', [WebinarController::class,'sessionDetailUpdate'])->name('webinar.detail.session.update');

        Route::get('webinar/registrations', [WebinarController::class,'registrationList'])->name('webinar.registration.list');
        Route::get('webinar', [WebinarController::class,'webinarList'])->name('webinar.list');
        Route::get('webinar/{id}/registrations', [WebinarController::class,'webinarRegistrationList'])->name('webinar.registrations');
        Route::get('webinar/create/{id?}', [WebinarController::class,'webinarAdd'])->name('webinar.create');
        Route::post('webinar/store', [WebinarController::class,'webinarStore'])->name('webinar.store');
        Route::post('webinar/update/{id?}', [WebinarController::class,'webinarUpdate'])->name('webinar.update');
       

        Route::get('membership/type', [MemberShipController::class,'typeList'])->name('membership.type.list');
        Route::get('membership/type/create/{id?}', [MemberShipController::class,'typeCreate'])->name('membership.type.create');
        Route::post('membership/type/store', [MemberShipController::class,'typeStore'])->name('membership.type.store');
        Route::post('membership/type/update/{id?}', [MemberShipController::class,'typeUpdate'])->name('membership.type.update');

        Route::get('membership/benefit', [MemberShipController::class,'benefitList'])->name('membership.benefit.list');
        Route::get('membership/benefit/create/{id?}', [MemberShipController::class,'benefitCreate'])->name('membership.benefit.create');
        Route::post('membership/benefit/store', [MemberShipController::class,'benefitStore'])->name('membership.benefit.store');
        Route::post('membership/benefit/update/{id?}', [MemberShipController::class,'benefitUpdate'])->name('membership.benefit.update');

        Route::get('membership/list', [MemberShipController::class,'memberList'])->name('membership.list');
        Route::get('membership/create/{id?}', [MemberShipController::class,'memberCreate'])->name('membership.create');
        Route::post('membership/store', [MemberShipController::class,'memberStore'])->name('membership.store');
        Route::post('membership/update/{id?}', [MemberShipController::class,'memberUpdate'])->name('membership.update');

        Route::get('enquiries/contact-us', [EnquiryController::class, 'contactUs'])->name('enquiries.contact-us');
        Route::get('enquiries/contact-us/{id}', [EnquiryController::class, 'showContactUs'])->name('enquiries.contact-us.show');
        Route::get('enquiries/partner-with-us', [EnquiryController::class, 'partnerWithUs'])->name('enquiries.partner-with-us');
        Route::get('enquiries/partner-with-us/{id}', [EnquiryController::class, 'showPartnerWithUs'])->name('enquiries.partner-with-us.show');

        
        Route::get('settings', [WebsiteSettingController::class, 'index'])->name('settings');
        Route::post('settings/update', [WebsiteSettingController::class, 'update'])->name('settings.update');

        Route::resource('utm-links', UtmLinkController::class)->except('show');
    });
// });
