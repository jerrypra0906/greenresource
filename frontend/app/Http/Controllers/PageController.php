<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Display a page by slug
     */
    public function show($slug)
    {
        // Cache key for the page
        $cacheKey = "page.{$slug}";
        
        // Try to get from cache first (cache for 1 hour)
        $pageData = Cache::remember($cacheKey, 3600, function () use ($slug) {
            $page = Page::where('slug', $slug)
                ->where('status', 'published')
                ->with(['sections' => function($query) {
                    $query->orderBy('order');
                }, 'sections.media'])
                ->first();

            if (!$page) {
                return null;
            }

            return [
                'page' => $page,
                'banner' => $page->banner ?? null,
            ];
        });

        // If page not found in database, return 404
        if (!$pageData) {
            abort(404, 'Page not found');
        }

        return view('page', $pageData);
    }

    /**
     * Display home page
     */
    public function home()
    {
        // Use static home view by default for performance
        // Set USE_CMS_HOME=true in .env to enable CMS-driven home page
        if (!config('app.use_cms_home', false)) {
            return view('home', ['banner' => null]);
        }

        // Cache home page for 1 hour
        $cacheKey = 'page.home';
        
        $pageData = Cache::remember($cacheKey, 3600, function () {
            $page = Page::where('slug', 'home')
                ->where('status', 'published')
                ->with(['sections' => function($query) {
                    $query->orderBy('order');
                }, 'sections.media'])
                ->first();

            if (!$page) {
                return null;
            }

            return [
                'page' => $page,
                'banner' => $page->banner ?? null,
            ];
        });

        // If no page found in database or no sections, use default view
        if (!$pageData || $pageData['page']->sections->count() === 0) {
            return view('home', ['banner' => $pageData['banner'] ?? null]);
        }

        return view('page', $pageData);
    }
}

