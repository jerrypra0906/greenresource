@extends('layouts.admin')

@section('title', 'Edit Section – Green Resources CMS')

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
                    <h1 class="admin-title">Edit Section</h1>
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
                <form method="POST" action="{{ route('sections.update', ['page' => $page, 'section' => $section]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="type">
                            Section Type <span>*</span>
                        </label>
                        <select id="type" name="type" required>
                            <option value="hero" {{ old('type', $section->type) === 'hero' ? 'selected' : '' }}>Hero</option>
                            <option value="content_block" {{ old('type', $section->type) === 'content_block' ? 'selected' : '' }}>Content Block</option>
                            <option value="highlight_list" {{ old('type', $section->type) === 'highlight_list' ? 'selected' : '' }}>Highlight List</option>
                            <option value="timeline" {{ old('type', $section->type) === 'timeline' ? 'selected' : '' }}>Timeline</option>
                            <option value="grid" {{ old('type', $section->type) === 'grid' ? 'selected' : '' }}>Grid</option>
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
                            value="{{ old('title', $section->title) }}"
                            placeholder="Section Title (optional)"
                        />
                        @error('title')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label for="body">
                                Content Body
                            </label>
                            <div style="display: flex; gap: 0.5rem;">
                                <button type="button" onclick="openImageInsertModal()" style="padding: 0.4rem 0.8rem; background: #059669; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.875rem;">
                                    📷 Insert Image
                                </button>
                                <button type="button" onclick="openMediaLibraryModal()" style="padding: 0.4rem 0.8rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.875rem;">
                                    🖼️ Choose from Library
                                </button>
                            </div>
                        </div>
                        <textarea
                            id="body"
                            name="body"
                            rows="8"
                            placeholder="Enter your content here. HTML is supported. Use the buttons above to insert images."
                        >{{ old('body', $section->body) }}</textarea>
                        @error('body')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            You can use HTML tags for formatting. Click "Insert Image" to upload and insert images directly into the content.
                        </small>
                    </div>

                    @if($section->media)
                        <div class="field" style="margin-bottom: 1.5rem;">
                            <label>Current Image</label>
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ $section->media->url }}" alt="{{ $section->media->alt_text ?? 'Current image' }}" style="max-width: 300px; max-height: 200px; border-radius: 0.5rem; border: 1px solid #e5e7eb;" />
                                <p style="margin-top: 0.5rem; color: #6b7280; font-size: 0.875rem;">
                                    {{ $section->media->file_name }}
                                </p>
                            </div>
                        </div>
                    @endif

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
                            Upload a new image to replace the current one (Max: 10MB, Images only)
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
                                <option value="{{ $mediaItem->id }}" {{ old('media_id', $section->media_id) == $mediaItem->id ? 'selected' : '' }}>
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
                            value="{{ old('order', $section->order) }}"
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
                        <button type="submit" class="btn-primary">Update Section</button>
                        <a href="{{ route('sections.index', $page) }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Image Insert Modal -->
<div id="imageInsertModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 0.5rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; font-size: 1.25rem;">Upload and Insert Image</h2>
            <button onclick="closeImageInsertModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <div class="field">
            <label for="body-image-upload">Upload Image</label>
            <input type="file" id="body-image-upload" accept="image/*" />
            <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                Max file size: 10MB
            </small>
        </div>
        
        <div class="field">
            <label for="body-image-alt">Alt Text (optional)</label>
            <input type="text" id="body-image-alt" placeholder="Describe the image" />
        </div>
        
        <div id="body-image-preview" style="margin: 1rem 0; display: none;">
            <img id="body-preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 0.5rem;" />
        </div>
        
        <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
            <button onclick="insertUploadedImage()" style="padding: 0.5rem 1rem; background: #059669; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                Insert Image
            </button>
            <button onclick="closeImageInsertModal()" style="padding: 0.5rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Media Library Modal -->
<div id="mediaLibraryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 0.5rem; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; font-size: 1.25rem;">Choose Image from Library</h2>
            <button onclick="closeMediaLibraryModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <div id="media-library-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            @foreach($media ?? [] as $mediaItem)
                @if(strpos($mediaItem->mime_type, 'image/') === 0)
                    <div onclick="insertImageFromLibrary('{{ $mediaItem->url }}', '{{ $mediaItem->alt_text ?? '' }}')" style="cursor: pointer; border: 2px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#059669'" onmouseout="this.style.borderColor='#e5e7eb'">
                        <img src="{{ $mediaItem->url }}" alt="{{ $mediaItem->alt_text ?? '' }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.25rem;" />
                        <p style="margin: 0.5rem 0 0; font-size: 0.75rem; color: #6b7280; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $mediaItem->file_name }}</p>
                    </div>
                @endif
            @endforeach
        </div>
        
        <button onclick="closeMediaLibraryModal()" style="padding: 0.5rem 1rem; background: #6b7280; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
            Cancel
        </button>
    </div>
</div>

<script>
let uploadedImageData = null;

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

// Image insertion functions
let imageUploadListenerAdded = false;

function openImageInsertModal() {
    document.getElementById('imageInsertModal').style.display = 'flex';
    uploadedImageData = null;
    document.getElementById('body-image-upload').value = '';
    document.getElementById('body-image-alt').value = '';
    document.getElementById('body-image-preview').style.display = 'none';
    
    // Preview uploaded image (only add listener once)
    if (!imageUploadListenerAdded) {
        document.getElementById('body-image-upload').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('body-preview-img').src = event.target.result;
                    document.getElementById('body-image-preview').style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
        imageUploadListenerAdded = true;
    }
}

function closeImageInsertModal() {
    document.getElementById('imageInsertModal').style.display = 'none';
}

function openMediaLibraryModal() {
    document.getElementById('mediaLibraryModal').style.display = 'flex';
}

function closeMediaLibraryModal() {
    document.getElementById('mediaLibraryModal').style.display = 'none';
}

function insertUploadedImage() {
    const fileInput = document.getElementById('body-image-upload');
    const altText = document.getElementById('body-image-alt').value;
    
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Please select an image file first.');
        return;
    }
    
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    if (altText) {
        formData.append('alt_text', altText);
    }
    
    // Show loading state
    const insertBtn = event.target;
    const originalText = insertBtn.textContent;
    insertBtn.textContent = 'Uploading...';
    insertBtn.disabled = true;
    
    fetch('{{ route("media.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            insertImageIntoBody(data.url, altText || data.media.alt_text || '');
            closeImageInsertModal();
        } else {
            alert('Failed to upload image. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while uploading the image.');
    })
    .finally(() => {
        insertBtn.textContent = originalText;
        insertBtn.disabled = false;
    });
}

function insertImageFromLibrary(url, altText) {
    insertImageIntoBody(url, altText);
    closeMediaLibraryModal();
}

function insertImageIntoBody(imageUrl, altText) {
    const textarea = document.getElementById('body');
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, cursorPos);
    const textAfter = textarea.value.substring(cursorPos);
    
    const imageHtml = `<img src="${imageUrl}" alt="${altText.replace(/"/g, '&quot;')}" style="max-width: 100%; height: auto;" />`;
    
    textarea.value = textBefore + imageHtml + textAfter;
    
    // Set cursor position after inserted image
    const newCursorPos = cursorPos + imageHtml.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
    textarea.focus();
}
</script>
@endsection

