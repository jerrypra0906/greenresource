<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only load page count for sections, not all sections data
        $pages = Page::withCount('sections')->orderBy('title')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:pages,slug',
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'banner_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Normalize slug (trim and lowercase for case-insensitive handling)
        $normalizedSlug = trim(strtolower($validated['slug']));
        
        // Check for duplicate (case-insensitive)
        $existing = Page::whereRaw('LOWER(TRIM(slug)) = ?', [$normalizedSlug])->first();
        if ($existing) {
            return back()->withErrors(['slug' => 'A page with this slug already exists.']);
        }
        
        $data = [
            'slug' => $normalizedSlug,
            'title' => trim($validated['title']),
            'meta_title' => $validated['meta_title'] ? trim($validated['meta_title']) : null,
            'meta_description' => $validated['meta_description'] ? trim($validated['meta_description']) : null,
            'status' => $validated['status'],
        ];
        
        // Handle banner data - use request input directly since validation may exclude empty arrays
        $banner = $request->input('banner', []);
        if (!is_array($banner)) {
            $banner = [];
        }
        
        // Handle banner image upload first (takes precedence)
        if ($request->hasFile('banner_image_file')) {
            $file = $request->file('banner_image_file');
            $fileName = 'banners/' . Str::slug($validated['slug']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('media', $fileName, 'public');
            if ($filePath) {
                $banner['image'] = $filePath;
            }
        }
        
        // Clean up empty banner fields
        $banner = array_filter($banner, function($value) {
            return $value !== null && $value !== '';
        });
        
        // Set banner only if it has content
        if (!empty($banner)) {
            $data['banner'] = $banner;
        }

        Page::create($data);

        return redirect()->route('pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $page->load('sections.media');
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $page->load('sections.media');
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'banner_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Normalize slug (trim and lowercase for case-insensitive handling)
        $normalizedSlug = trim(strtolower($validated['slug']));
        
        // Check for duplicate (case-insensitive), excluding current page
        $existing = Page::where('id', '!=', $page->id)
            ->whereRaw('LOWER(TRIM(slug)) = ?', [$normalizedSlug])
            ->first();
        if ($existing) {
            return back()->withErrors(['slug' => 'A page with this slug already exists.']);
        }
        
        $data = [
            'slug' => $normalizedSlug,
            'title' => trim($validated['title']),
            'meta_title' => $validated['meta_title'] ? trim($validated['meta_title']) : null,
            'meta_description' => $validated['meta_description'] ? trim($validated['meta_description']) : null,
            'status' => $validated['status'],
        ];
        
        // Handle banner data - use request input directly since validation may exclude empty arrays
        $banner = $request->input('banner', []);
        if (!is_array($banner)) {
            $banner = [];
        }
        
        // Handle banner image upload first (takes precedence)
        if ($request->hasFile('banner_image_file')) {
            // Delete old banner image if exists
            if ($page->banner && isset($page->banner['image'])) {
                $oldPath = $page->banner['image'];
                if (strpos($oldPath, 'storage/') === 0) {
                    $oldPath = str_replace('storage/', '', $oldPath);
                }
                Storage::disk('public')->delete($oldPath);
            }
            
            $file = $request->file('banner_image_file');
            $fileName = 'banners/' . Str::slug($validated['slug']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('media', $fileName, 'public');
            if ($filePath) {
                $banner['image'] = $filePath;
            }
        }
        
        // Clean up empty banner fields
        $banner = array_filter($banner, function($value) {
            return $value !== null && $value !== '';
        });
        
        // Set banner (null if empty to allow clearing)
        $data['banner'] = !empty($banner) ? $banner : null;
        
        $page->update($data);
        
        // Clear page cache (non-blocking)
        Cache::forget("page.{$page->slug}");
        Cache::forget('page.home');

        return redirect()->route('pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Preview the page (even if draft)
     */
    public function preview(Page $page)
    {
        // Refresh the page from database to get latest changes
        $page->refresh();
        $page->load(['sections' => function($query) {
            $query->orderBy('order');
        }, 'sections.media']);
        // Banner image path is stored correctly in database
        $banner = $page->banner ?? null;
        
        return view('page', compact('page', 'banner'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // Delete banner image if exists
        if ($page->banner && isset($page->banner['image']) && strpos($page->banner['image'], 'storage/') === 0) {
            $oldPath = str_replace('storage/', '', $page->banner['image']);
            Storage::disk('public')->delete($oldPath);
        }
        
        $slug = $page->slug;
        $page->delete();
        
        // Clear page cache
        Cache::forget("page.{$slug}");
        Cache::forget('page.home'); // Also clear home cache if this was home page

        return redirect()->route('pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
