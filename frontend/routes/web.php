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
            return view('pages.company.about');
        })->name('company.about');
        
        Route::get('/location', function () {
            return (new PageController())->show('company-location');
        })->name('company.location');
        
        Route::get('/sustainability', function () {
            return view('pages.company.sustainability');
        })->name('company.sustainability');
        
        Route::get('/commercial-partner', function () {
            return (new PageController())->show('company-commercial-partner');
        })->name('company.partner');
    });

    // Products Routes
    Route::prefix('products')->group(function () {
        Route::get('/', function () {
            return view('pages.products.index');
        })->name('products.index');
        
        Route::get('/feedstocks', function () {
            return view('pages.products.feedstocks');
        })->name('products.feedstocks');
        
        Route::get('/methyl-ester', function () {
            return view('pages.products.methyl');
        })->name('products.methyl');
        
        Route::get('/others', function () {
            return view('pages.products.others');
        })->name('products.others');
    });

    // Contact Us Route
    Route::get('/contact-us', function () {
        return view('contact-us');
    })->name('contact');

    // Contact Us Sub-routes
    Route::prefix('contact-us')->group(function () {
        Route::get('/fulfill-form', function () {
            return (new PageController())->show('contact-us-fulfill-form');
        })->name('contact-us.fulfill-form');
        
        Route::get('/contacts', function () {
            return (new PageController())->show('contact-us-contacts');
        })->name('contact-us.contacts');
    });
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

        // CMS Routes - Navigation
        Route::resource('navigation', App\Http\Controllers\Admin\NavigationController::class)
            ->names([
                'index' => 'admin.navigation.index',
                'create' => 'admin.navigation.create',
                'store' => 'admin.navigation.store',
                'edit' => 'admin.navigation.edit',
                'update' => 'admin.navigation.update',
                'destroy' => 'admin.navigation.destroy',
            ]);

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

