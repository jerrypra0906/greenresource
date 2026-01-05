<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use App\Models\Media;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Page $page)
    {
        $sections = $page->sections()->with('media')->orderBy('order')->get();
        return view('admin.sections.index', compact('page', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Page $page)
    {
        // Load media list once for the view (limit to 100 most recent)
        $media = \App\Models\Media::orderBy('created_at', 'desc')->limit(100)->get();
        return view('admin.sections.create', compact('page', 'media'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Page $page)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'media_id' => 'nullable|exists:media,id',
            'image' => 'nullable|file|image|max:10240', // 10MB max, images only
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->all();
        // Sanitize rich text body to prevent stored XSS
        $data['body'] = HtmlSanitizer::clean($request->input('body'));

        // Handle file upload if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->store('media', 'public');

            $media = Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => $request->input('image_alt_text') ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);

            $data['media_id'] = $media->id;
        }

        // Remove image and image_alt_text from data array as they're not in the sections table
        unset($data['image'], $data['image_alt_text']);

        $page->sections()->create($data);
        
        // Clear page cache
        Cache::forget("page.{$page->slug}");
        Cache::forget('page.home');

        return redirect()->route('pages.show', $page)
            ->with('success', 'Section created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page, Section $section)
    {
        $section->load('media');
        // Load media list once for the view (limit to 100 most recent)
        $media = \App\Models\Media::orderBy('created_at', 'desc')->limit(100)->get();
        return view('admin.sections.edit', compact('page', 'section', 'media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page, Section $section)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'media_id' => 'nullable|exists:media,id',
            'image' => 'nullable|file|image|max:10240', // 10MB max, images only
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->all();
        // Sanitize rich text body to prevent stored XSS
        $data['body'] = HtmlSanitizer::clean($request->input('body'));

        // Handle file upload if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->store('media', 'public');

            $media = Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => $request->input('image_alt_text') ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);

            $data['media_id'] = $media->id;
        }

        // Remove image and image_alt_text from data array as they're not in the sections table
        unset($data['image'], $data['image_alt_text']);

        $section->update($data);
        
        // Clear page cache
        Cache::forget("page.{$page->slug}");
        Cache::forget('page.home');

        return redirect()->route('pages.show', $page)
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, Section $section)
    {
        $section->delete();
        
        // Clear page cache
        Cache::forget("page.{$page->slug}");
        Cache::forget('page.home');

        return redirect()->route('pages.show', $page)
            ->with('success', 'Section deleted successfully.');
    }

    /**
     * Reorder sections
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:sections,id',
            'sections.*.order' => 'required|integer',
        ]);

        foreach ($request->sections as $item) {
            Section::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
