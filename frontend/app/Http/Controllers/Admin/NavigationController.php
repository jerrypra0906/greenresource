<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = NavigationItem::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('order');
            }, 'children.page', 'page'])
            ->orderBy('order')
            ->get();
        
        return view('admin.navigation.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentItems = NavigationItem::whereNull('parent_id')
            ->orderBy('order')
            ->get();
        
        $pages = Page::where('status', 'published')
            ->orderBy('title')
            ->get();
        
        $routes = $this->getAvailableRoutes();
        $pageRouteMap = $this->getPageRouteMap($pages);
        
        return view('admin.navigation.create', compact('parentItems', 'pages', 'routes', 'pageRouteMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'target_url' => 'nullable|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'order' => 'nullable|integer|min:0',
            'visible' => 'boolean',
            'parent_id' => 'nullable|exists:navigation_items,id',
        ]);

        // Normalize label (trim and case-insensitive check)
        $normalizedLabel = trim($validated['label']);
        
        // Check for duplicate (case-insensitive) within same parent
        $existing = NavigationItem::where('parent_id', $validated['parent_id'] ?? null)
            ->whereRaw('LOWER(TRIM(label)) = ?', [strtolower($normalizedLabel)])
            ->first();
        
        if ($existing) {
            return back()->withErrors(['label' => 'A navigation item with this label already exists under the selected parent.']);
        }

        // Handle page_id - if page is selected, auto-generate route name
        $pageId = $validated['page_id'] ?? null;
        $targetUrl = trim($validated['target_url'] ?? '');
        
        if ($pageId) {
            $page = Page::find($pageId);
            if ($page) {
                // Auto-generate route name from page slug
                $targetUrl = $this->slugToRouteName($page->slug);
            }
        }
        
        // For parent items (no parent_id), target_url is optional - default to '#'
        // For child items (has parent_id), target_url is required
        $isParent = empty($validated['parent_id']);
        
        if ($isParent) {
            // Parent item - target_url optional, default to '#'
            $targetUrl = $targetUrl ?: '#';
        } else {
            // Child item - target_url required
            if (empty($targetUrl)) {
                return back()->withErrors(['target_url' => 'Target URL is required for submenu items. Select a page or enter a route name.']);
            }
        }

        NavigationItem::create([
            'label' => $normalizedLabel,
            'target_url' => $targetUrl,
            'page_id' => $pageId,
            'order' => $validated['order'] ?? 0,
            'visible' => $request->has('visible') ? (bool)$validated['visible'] : true,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->route('admin.navigation.index')
            ->with('success', 'Navigation item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NavigationItem $navigation)
    {
        $parentItems = NavigationItem::whereNull('parent_id')
            ->where('id', '!=', $navigation->id)
            ->orderBy('order')
            ->get();
        
        $pages = Page::where('status', 'published')
            ->orderBy('title')
            ->get();
        
        $routes = $this->getAvailableRoutes();
        $pageRouteMap = $this->getPageRouteMap($pages);
        
        return view('admin.navigation.edit', compact('navigation', 'parentItems', 'pages', 'routes', 'pageRouteMap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NavigationItem $navigation)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'target_url' => 'nullable|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'order' => 'nullable|integer|min:0',
            'visible' => 'boolean',
            'parent_id' => 'nullable|exists:navigation_items,id|different:' . $navigation->id,
        ]);

        // Prevent setting a child as parent (circular reference)
        if ($validated['parent_id']) {
            $parent = NavigationItem::find($validated['parent_id']);
            if ($parent && $parent->parent_id === $navigation->id) {
                return back()->withErrors(['parent_id' => 'Cannot set a child item as parent.']);
            }
        }

        // Normalize label (trim and case-insensitive check)
        $normalizedLabel = trim($validated['label']);
        
        // Check for duplicate (case-insensitive) within same parent, excluding current item
        $existing = NavigationItem::where('parent_id', $validated['parent_id'] ?? null)
            ->where('id', '!=', $navigation->id)
            ->whereRaw('LOWER(TRIM(label)) = ?', [strtolower($normalizedLabel)])
            ->first();
        
        if ($existing) {
            return back()->withErrors(['label' => 'A navigation item with this label already exists under the selected parent.']);
        }

        // Handle page_id - if page is selected, auto-generate route name
        $pageId = $validated['page_id'] ?? null;
        $targetUrl = trim($validated['target_url'] ?? '');
        
        if ($pageId) {
            $page = Page::find($pageId);
            if ($page) {
                // Auto-generate route name from page slug
                $targetUrl = $this->slugToRouteName($page->slug);
            }
        }
        
        // For parent items (no parent_id), target_url is optional - default to '#'
        // For child items (has parent_id), target_url is required
        $isParent = empty($validated['parent_id']);
        
        if ($isParent) {
            // Parent item - target_url optional, default to '#'
            $targetUrl = $targetUrl ?: '#';
        } else {
            // Child item - target_url required
            if (empty($targetUrl)) {
                return back()->withErrors(['target_url' => 'Target URL is required for submenu items. Select a page or enter a route name.']);
            }
        }

        $navigation->update([
            'label' => $normalizedLabel,
            'target_url' => $targetUrl,
            'page_id' => $pageId,
            'order' => $validated['order'] ?? 0,
            'visible' => $request->has('visible') ? (bool)$validated['visible'] : true,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->route('admin.navigation.index')
            ->with('success', 'Navigation item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NavigationItem $navigation)
    {
        // Check if item has children
        if ($navigation->children()->count() > 0) {
            return redirect()->route('admin.navigation.index')
                ->with('error', 'Cannot delete navigation item with submenu items. Please delete or reassign submenu items first.');
        }

        $navigation->delete();

        return redirect()->route('admin.navigation.index')
            ->with('success', 'Navigation item deleted successfully.');
    }

    /**
     * Get available routes for the dropdown
     */
    private function getAvailableRoutes()
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if ($name && strpos($name, 'admin.') !== 0) {
                $routes[$name] = $name;
            }
        }
        ksort($routes);
        return $routes;
    }

    /**
     * Convert page slug to route name
     * Examples:
     * - home → home
     * - company-about-us → company.about-us
     * - company-location → company.location
     * - product-feedstocks → product.feedstocks
     * - contact-us-fulfill-form → contact-us.fulfill-form
     */
    private function slugToRouteName($slug)
    {
        // Special case: home
        if ($slug === 'home') {
            return 'home';
        }
        
        // Route prefixes (in order of specificity - longer first)
        $prefixes = [
            'contact-us' => 'contact-us',
            'company' => 'company',
            'product' => 'product',
        ];
        
        // Check for multi-word prefixes first (contact-us)
        foreach ($prefixes as $prefixKey => $prefixValue) {
            if (strpos($slug, $prefixKey . '-') === 0) {
                $remaining = substr($slug, strlen($prefixKey) + 1); // +1 for hyphen
                if ($remaining) {
                    return $prefixValue . '.' . $remaining;
                }
                return $prefixValue;
            }
        }
        
        // Fallback: first segment is prefix, rest is suffix
        $parts = explode('-', $slug);
        if (count($parts) >= 2) {
            $prefix = $parts[0];
            $suffix = implode('-', array_slice($parts, 1));
            return $prefix . '.' . $suffix;
        }
        
        return $slug;
    }

    /**
     * Get mapping of page IDs to route names
     */
    private function getPageRouteMap($pages)
    {
        $map = [];
        foreach ($pages as $page) {
            $map[$page->id] = $this->slugToRouteName($page->slug);
        }
        return $map;
    }
}
