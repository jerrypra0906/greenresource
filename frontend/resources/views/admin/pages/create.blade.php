@extends('layouts.admin')

@section('title', 'Create Page – Green Resources CMS')

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
                    <a href="{{ route('pages.index') }}" class="active">Pages</a>
                    <a href="{{ route('media.index') }}">Media</a>
                    <a href="{{ route('admin.navigation.index') }}">Navigation</a>
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
                    <h1 class="admin-title">Create New Page</h1>
                    <p class="admin-subtitle">Add a new page to your website</p>
                </div>
                <a href="{{ route('pages.index') }}" class="btn-secondary">Back to Pages</a>
            </div>

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 1.5rem">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="admin-card">
                <form method="POST" action="{{ route('pages.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <label for="slug">
                            Slug <span>*</span>
                        </label>
                        <input
                            id="slug"
                            name="slug"
                            type="text"
                            value="{{ old('slug') }}"
                            required
                            placeholder="e.g., home, about-us"
                        />
                        @error('slug')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            URL-friendly identifier for this page (must be unique)
                        </small>
                    </div>

                    <div class="field">
                        <label for="title">
                            Title <span>*</span>
                        </label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            required
                            placeholder="Page Title"
                        />
                        @error('title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="meta_title">
                            Meta Title
                        </label>
                        <input
                            id="meta_title"
                            name="meta_title"
                            type="text"
                            value="{{ old('meta_title') }}"
                            placeholder="SEO Meta Title"
                        />
                        @error('meta_title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Title for search engines (leave empty to use page title)
                        </small>
                    </div>

                    <div class="field">
                        <label for="meta_description">
                            Meta Description
                        </label>
                        <textarea
                            id="meta_description"
                            name="meta_description"
                            rows="3"
                            placeholder="SEO Meta Description"
                        >{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Description for search engines (recommended: 150-160 characters)
                        </small>
                    </div>

                    <div class="field">
                        <label for="status">
                            Status <span>*</span>
                        </label>
                        <select id="status" name="status" required>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label>
                            Banner Configuration
                        </label>
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; background: #f9fafb;">
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                                <div style="font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">📐 Recommended Banner Image Specifications</div>
                                <ul style="margin: 0; padding-left: 1.5rem; color: #1e3a8a; font-size: 0.875rem;">
                                    <li><strong>Size:</strong> 1920 x 600 pixels (recommended)</li>
                                    <li><strong>Aspect Ratio:</strong> 16:5 (3.2:1)</li>
                                    <li><strong>File Format:</strong> JPG, PNG, WebP</li>
                                    <li><strong>Max File Size:</strong> 5MB</li>
                                    <li><strong>Optimization:</strong> Compress images for web to reduce load time</li>
                                </ul>
                            </div>
                            
                            <div class="field" style="margin-bottom: 1rem;">
                                <label for="banner_title" style="font-size: 0.875rem; font-weight: 600;">Banner Title</label>
                                <input
                                    id="banner_title"
                                    name="banner[title]"
                                    type="text"
                                    value="{{ old('banner.title') }}"
                                    placeholder="Banner Title"
                                />
                            </div>
                            
                            <div class="field" style="margin-bottom: 1rem;">
                                <label for="banner_subtitle" style="font-size: 0.875rem; font-weight: 600;">Banner Subtitle</label>
                                <input
                                    id="banner_subtitle"
                                    name="banner[subtitle]"
                                    type="text"
                                    value="{{ old('banner.subtitle') }}"
                                    placeholder="Banner Subtitle"
                                />
                            </div>
                            
                            <div class="field" style="margin-bottom: 1rem;">
                                <label for="banner_image_file" style="font-size: 0.875rem; font-weight: 600;">Upload Banner Image</label>
                                <input
                                    id="banner_image_file"
                                    name="banner_image_file"
                                    type="file"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                    onchange="previewBannerImage(this)"
                                />
                                @error('banner_image_file')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                                    Upload a banner image
                                </small>
                            </div>
                            
                            <div id="banner_preview" style="margin-bottom: 1rem; display: none;">
                                <label style="font-size: 0.875rem; font-weight: 600; display: block; margin-bottom: 0.5rem;">Banner Preview</label>
                                <div style="border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; background: white; text-align: center;">
                                    <img 
                                        id="banner_preview_img" 
                                        src="" 
                                        alt="Banner Preview" 
                                        style="max-width: 100%; max-height: 300px; border-radius: 0.5rem; display: none; margin: 0 auto;"
                                    />
                                    <div id="banner_preview_placeholder" style="display: block; color: #9ca3af; padding: 2rem;">
                                        No banner image selected
                                    </div>
                                </div>
                            </div>
                            
                            <div class="field">
                                <label for="banner_image" style="font-size: 0.875rem; font-weight: 600;">Or Use Existing Image Path</label>
                                <input
                                    id="banner_image"
                                    name="banner[image]"
                                    type="text"
                                    value="{{ old('banner.image') }}"
                                    placeholder="assets/HEADER GREEN RESOURCES.png"
                                    onchange="updateBannerPreview(this.value)"
                                />
                                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                                    Enter path relative to public directory (e.g., assets/image.png) or leave empty if uploading new image
                                </small>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
                        <button type="submit" class="btn-primary">Create Page</button>
                        <a href="{{ route('pages.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function previewBannerImage(input) {
    const preview = document.getElementById('banner_preview');
    const previewImg = document.getElementById('banner_preview_img');
    const placeholder = document.getElementById('banner_preview_placeholder');
    const pathInput = document.getElementById('banner_image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.style.display = 'block';
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            placeholder.style.display = 'none';
            // Clear path input when uploading new file
            pathInput.value = '';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function updateBannerPreview(path) {
    const preview = document.getElementById('banner_preview');
    const previewImg = document.getElementById('banner_preview_img');
    const placeholder = document.getElementById('banner_preview_placeholder');
    const fileInput = document.getElementById('banner_image_file');
    
    if (path) {
        preview.style.display = 'block';
        previewImg.src = path.startsWith('http') ? path : '/' + path;
        previewImg.style.display = 'block';
        placeholder.style.display = 'none';
        // Clear file input when using path
        fileInput.value = '';
    } else {
        preview.style.display = 'none';
        previewImg.style.display = 'none';
        placeholder.style.display = 'block';
    }
}
</script>
@endsection

