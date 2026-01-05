@extends('layouts.admin')

@section('title', 'Create Section – Green Resources CMS')

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
                    <h1 class="admin-title">Create New Section</h1>
                    <p class="admin-subtitle">Page: {{ $page->title }}</p>
                </div>
                <a href="{{ route('sections.index', $page) }}" class="btn-secondary">Back to Sections</a>
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
                <form method="POST" action="{{ route('sections.store', $page) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <label for="type">
                            Section Type <span>*</span>
                        </label>
                        <select id="type" name="type" required onchange="updateSectionFields()">
                            <option value="">Select a type</option>
                            <option value="hero" {{ old('type') === 'hero' ? 'selected' : '' }}>Hero</option>
                            <option value="content_block" {{ old('type') === 'content_block' ? 'selected' : '' }}>Content Block</option>
                            <option value="highlight_list" {{ old('type') === 'highlight_list' ? 'selected' : '' }}>Highlight List</option>
                            <option value="timeline" {{ old('type') === 'timeline' ? 'selected' : '' }}>Timeline</option>
                            <option value="grid" {{ old('type') === 'grid' ? 'selected' : '' }}>Grid</option>
                        </select>
                        @error('type')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <div id="section-type-info" style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-left: 3px solid #059669; border-radius: 0.25rem;">
                            <strong style="display: block; margin-bottom: 0.5rem; color: #059669;">Section Type Guide:</strong>
                            <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                                <p style="margin: 0.5rem 0;"><strong>Hero:</strong> Large banner section for page headers with two-column layout (content + image). Best for landing sections and main call-to-action areas.</p>
                                <p style="margin: 0.5rem 0;"><strong>Content Block:</strong> Standard content section for paragraphs, text, and media. Use for general content, descriptions, and text with images.</p>
                                <p style="margin: 0.5rem 0;"><strong>Highlight List:</strong> Display a list of items/features with icons or bullets. Perfect for feature lists, benefits, checklists, and key points.</p>
                                <p style="margin: 0.5rem 0;"><strong>Timeline:</strong> Display chronological events or milestones in a vertical timeline format. Ideal for company history, project milestones, and event sequences.</p>
                                <p style="margin: 0.5rem 0;"><strong>Grid:</strong> Display multiple items in a responsive grid layout (cards, products, services). Use for product showcases, service cards, team members, and portfolio items.</p>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="title">
                            Section Title
                        </label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            placeholder="Section Title (optional)"
                        />
                        @error('title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="body">
                            Content Body
                        </label>
                        <textarea
                            id="body"
                            name="body"
                            rows="8"
                            placeholder="Enter your content here. HTML is supported."
                        >{{ old('body') }}</textarea>
                        @error('body')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            You can use HTML tags for formatting
                        </small>
                    </div>

                    <div class="field">
                        <label for="image">
                            Upload New Image
                        </label>
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            onchange="previewImage(this)"
                        />
                        @error('image')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Upload a new image file (Max: 10MB, Images only)
                        </small>
                        <div id="image-preview" style="margin-top: 1rem; display: none;">
                            <img id="preview-img" src="" alt="Preview" style="max-width: 300px; max-height: 200px; border-radius: 0.5rem; border: 1px solid #e5e7eb;" />
                        </div>
                    </div>

                    <div class="field" id="image-alt-field" style="display: none;">
                        <label for="image_alt_text">
                            Image Alt Text
                        </label>
                        <input
                            type="text"
                            id="image_alt_text"
                            name="image_alt_text"
                            value="{{ old('image_alt_text') }}"
                            placeholder="Describe the image for accessibility"
                        />
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Optional: Describe the image for screen readers and SEO
                        </small>
                    </div>

                    <div style="margin: 1.5rem 0; padding: 1rem; background: #f3f4f6; border-radius: 0.5rem; text-align: center;">
                        <span style="color: #6b7280; font-size: 0.875rem;">OR</span>
                    </div>

                    <div class="field">
                        <label for="media_id">
                            Select Existing Media
                        </label>
                        <select id="media_id" name="media_id">
                            <option value="">None</option>
                            @foreach($media ?? [] as $mediaItem)
                                <option value="{{ $mediaItem->id }}" {{ old('media_id') == $mediaItem->id ? 'selected' : '' }}>
                                    {{ $mediaItem->file_name }} ({{ $mediaItem->mime_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('media_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Choose an existing image from the media library
                        </small>
                    </div>

                    <div class="field">
                        <label for="order">
                            Display Order
                        </label>
                        <input
                            id="order"
                            name="order"
                            type="number"
                            value="{{ old('order', $page->sections()->max('order') + 1 ?? 0) }}"
                            min="0"
                        />
                        @error('order')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Lower numbers appear first (0 = first)
                        </small>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn-primary">Create Section</button>
                        <a href="{{ route('sections.index', $page) }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const altField = document.getElementById('image-alt-field');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            altField.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        altField.style.display = 'none';
    }
}

function updateSectionFields() {
    // This function can be used for dynamic field updates based on section type
    // Currently kept for compatibility
}
</script>
@endsection

