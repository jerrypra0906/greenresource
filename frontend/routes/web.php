<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes - Dynamic Pages from CMS
use App\Http\Controllers\PageController;

// Temporarily disable response caching to debug performance
Route::group([], function () {
    Route::get('/', [PageController::class, 'home'])->name('home');

    // Company Routes
    Route::prefix('company')->group(function () {
        Route::get('/about-us', function () {
            return (new PageController())->show('company-about-us');
        })->name('company.about-us');
        
        Route::get('/location', function () {
            return (new PageController())->show('company-location');
        })->name('company.location');
    });

    // Products Routes
    Route::prefix('products')->group(function () {
        Route::get('/feedstocks', function () {
            return (new PageController())->show('products-feedstocks');
        })->name('products.feedstocks');
        
        Route::get('/methyl-ester', function () {
            return (new PageController())->show('products-methyl-ester');
        })->name('products.methyl-ester');
        
        Route::get('/others', function () {
            return (new PageController())->show('products-others');
        })->name('products.others');
    });

    // News and Event Routes
    Route::prefix('news-and-event')->group(function () {
        Route::get('/news', function () {
            return (new PageController())->show('news-and-event-news');
        })->name('news-and-event.news');
        
        Route::get('/event', function () {
            return (new PageController())->show('news-and-event-event');
        })->name('news-and-event.event');
    });

    // Contact Routes
    Route::get('/contact', function () {
        return (new PageController())->show('contact');
    })->name('contact');
});
// Re-enable with: Route::middleware(['cache.response:3600'])->group(function () {

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])
    ->name('contact.submit');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->name('admin.login');

    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])
        ->middleware('throttle:5,1') // Brute force protection: max 5 attempts per minute per IP
        ->name('admin.login.submit');

    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // CMS Routes - Pages
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
        Route::get('/pages/{page}/preview', [App\Http\Controllers\Admin\PageController::class, 'preview'])
            ->name('pages.preview');
        
        // CMS Routes - Sections (nested under pages)
        Route::get('/pages/{page}/sections', [App\Http\Controllers\Admin\SectionController::class, 'index'])
            ->name('sections.index');
        Route::get('/pages/{page}/sections/create', [App\Http\Controllers\Admin\SectionController::class, 'create'])
            ->name('sections.create');
        Route::post('/pages/{page}/sections', [App\Http\Controllers\Admin\SectionController::class, 'store'])
            ->name('sections.store');
        Route::get('/pages/{page}/sections/{section}/edit', [App\Http\Controllers\Admin\SectionController::class, 'edit'])
            ->name('sections.edit');
        Route::put('/pages/{page}/sections/{section}', [App\Http\Controllers\Admin\SectionController::class, 'update'])
            ->name('sections.update');
        Route::delete('/pages/{page}/sections/{section}', [App\Http\Controllers\Admin\SectionController::class, 'destroy'])
            ->name('sections.destroy');
        Route::post('/sections/reorder', [App\Http\Controllers\Admin\SectionController::class, 'reorder'])
            ->name('sections.reorder');

        // CMS Routes - Media
        Route::resource('media', App\Http\Controllers\Admin\MediaController::class);
        Route::post('/media/upload', [App\Http\Controllers\Admin\MediaController::class, 'upload'])
            ->name('media.upload');

        // CMS Routes - Inquiries
        Route::get('/inquiries', [App\Http\Controllers\Admin\InquiryController::class, 'index'])
            ->name('admin.inquiries.index');
        Route::get('/inquiries/{inquiry}', [App\Http\Controllers\Admin\InquiryController::class, 'show'])
            ->name('admin.inquiries.show');
        Route::patch('/inquiries/{inquiry}/status', [App\Http\Controllers\Admin\InquiryController::class, 'updateStatus'])
            ->name('admin.inquiries.update-status');

        // CMS Routes - Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
            ->name('admin.settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])
            ->name('admin.settings.update');

        // CMS Routes - Users (Admin Management)
        Route::resource('users', App\Http\Controllers\Admin\UserController::class)
            ->names([
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'show' => 'admin.users.show',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy',
            ]);
    });
});

