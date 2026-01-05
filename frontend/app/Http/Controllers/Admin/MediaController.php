<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $media = Media::orderBy('created_at', 'desc')->paginate(24);
        return view('admin.media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->store('media', 'public');

        Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->alt_text ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        return redirect()->route('media.index')
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Upload file via AJAX
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('media', 'public');

        $media = Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'alt_text' => $request->alt_text ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
        ]);

        return response()->json([
            'success' => true,
            'media' => $media,
            'url' => $media->url,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->route('media.index')
            ->with('success', 'Media deleted successfully.');
    }
}
