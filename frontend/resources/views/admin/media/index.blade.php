@extends('layouts.admin')

@section('title', 'Media Library – Green Resources CMS')

@section('content')
<div class="admin-wrapper">
    <header class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-mark">GR</span>
                    <span>Green Resources CMS</span>
                </a>
                <div class="admin-nav-links">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('pages.index') }}">Pages</a>
                    <a href="{{ route('media.index') }}" class="active">Media</a>
                    <a href="{{ route('admin.inquiries.index') }}">Inquiries</a>
                    <a href="{{ route('admin.settings.index') }}">Settings</a>
                    <a href="{{ route('admin.users.index') }}">Users</a>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; padding: 0;">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 class="admin-title">Media Library</h1>
                    <p class="admin-subtitle">Upload and manage images, documents, and assets</p>
                </div>
                <a href="{{ route('media.create') }}" class="btn-primary">Upload Media</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-card">
                <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(0,0,0,0.1);">
                    @csrf
                    <div class="field">
                        <label for="file">Upload File</label>
                        <input type="file" id="file" name="file" required />
                        <small style="color: #64748b; font-size: 0.85rem;">Max file size: 10MB</small>
                    </div>
                    <div class="field">
                        <label for="alt_text">Alt Text (optional)</label>
                        <input type="text" id="alt_text" name="alt_text" />
                    </div>
                    <button type="submit" class="btn-primary">Upload</button>
                </form>

                @if($media->count() > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                        @foreach($media as $item)
                            <div style="border: 1px solid rgba(0,0,0,0.1); border-radius: 0.5rem; padding: 1rem; text-align: center;">
                                @if(str_starts_with($item->mime_type, 'image/'))
                                    <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 0.25rem; margin-bottom: 0.5rem;" />
                                @else
                                    <div style="width: 100%; height: 150px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; margin-bottom: 0.5rem;">
                                        📄
                                    </div>
                                @endif
                                <p style="font-size: 0.85rem; margin: 0.5rem 0; word-break: break-word;">{{ Str::limit($item->file_name, 20) }}</p>
                                <form method="POST" action="{{ route('media.destroy', $item) }}" style="margin-top: 0.5rem;" onsubmit="return confirm('Are you sure you want to delete this media?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small" style="background: #dc2626; color: white; border-color: #dc2626;">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    @if($media->hasPages())
                        <div style="margin-top: 2rem; display: flex; justify-content: center;">
                            {{ $media->links() }}
                        </div>
                    @endif
                @else
                    <p style="text-align: center; padding: 2rem; color: #64748b;">No media files uploaded yet.</p>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection

