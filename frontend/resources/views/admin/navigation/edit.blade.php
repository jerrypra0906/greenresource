@extends('layouts.admin')

@section('title', 'Edit Navigation Item – Green Resources CMS')

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
                    <a href="{{ route('media.index') }}">Media</a>
                    <a href="{{ route('admin.navigation.index') }}" class="active">Navigation</a>
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
                    <h1 class="admin-title">Edit Navigation Item</h1>
                    <p class="admin-subtitle">Update menu item details</p>
                </div>
                <a href="{{ route('admin.navigation.index') }}" class="btn-secondary">Back to Navigation</a>
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
                <form method="POST" action="{{ route('admin.navigation.update', $navigation) }}">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="label">
                            Menu Label <span>*</span>
                        </label>
                        <input
                            id="label"
                            name="label"
                            type="text"
                            value="{{ old('label', $navigation->label) }}"
                            required
                            placeholder="e.g., About Us, Products, Contact"
                        />
                        @error('label')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field" id="page_id_field">
                        <label for="page_id">
                            Link to Page (Optional)
                        </label>
                        <select id="page_id" name="page_id" onchange="handlePageSelection()">
                            <option value="">None - Enter route manually</option>
                            @foreach($pages as $page)
                                <option 
                                    value="{{ $page->id }}" 
                                    data-route="{{ $pageRouteMap[$page->id] ?? '' }}"
                                    {{ old('page_id', $navigation->page_id) == $page->id ? 'selected' : '' }}
                                >
                                    {{ $page->title }} ({{ $page->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('page_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Select a page to automatically fill the route name below. Or leave empty to enter route manually.
                        </small>
                    </div>

                    <div class="field" id="target_url_field">
                        <label for="target_url">
                            Target URL / Route Name <span id="target_url_required" style="display: none;">*</span>
                        </label>
                        <input
                            id="target_url"
                            name="target_url"
                            type="text"
                            value="{{ old('target_url', $navigation->target_url) }}"
                            placeholder="e.g., company.about-us or https://example.com"
                            list="route-suggestions"
                        />
                        <datalist id="route-suggestions">
                            @foreach($routes as $routeName)
                                <option value="{{ $routeName }}">
                            @endforeach
                        </datalist>
                        @error('target_url')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small id="target_url_help" style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            <span id="parent_help" style="display: none;">Optional for parent menu items. Leave empty to use <code>#</code> (dropdown only). Select a page above to auto-fill.</span>
                            <span id="child_help">Required for submenu items. Select a page above to auto-fill, or enter a route name (e.g., <code>company.about-us</code>) or full URL manually.</span>
                        </small>
                    </div>

                    <div class="field">
                        <label for="parent_id">
                            Parent Menu Item
                        </label>
                        <select id="parent_id" name="parent_id" onchange="toggleTargetUrlField()">
                            <option value="">None (Top-level menu item)</option>
                            @foreach($parentItems as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $navigation->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->label }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Select a parent menu item to create a submenu, or leave as "None" for a top-level item
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
                            value="{{ old('order', $navigation->order) }}"
                            min="0"
                        />
                        @error('order')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Lower numbers appear first (0 = first position)
                        </small>
                    </div>

                    <div class="field">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input
                                type="checkbox"
                                name="visible"
                                value="1"
                                {{ old('visible', $navigation->visible) ? 'checked' : '' }}
                            />
                            <span>Visible in menu</span>
                        </label>
                        <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">
                            Uncheck to hide this item from the navigation menu
                        </small>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
                        <button type="submit" class="btn-primary">Update Navigation Item</button>
                        <a href="{{ route('admin.navigation.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
// Page route mapping from server
const pageRouteMap = @json($pageRouteMap);

function handlePageSelection() {
    const pageSelect = document.getElementById('page_id');
    const targetUrlField = document.getElementById('target_url');
    const selectedPageId = pageSelect.value;
    
    if (selectedPageId && pageRouteMap[selectedPageId]) {
        // Auto-fill route name when page is selected
        targetUrlField.value = pageRouteMap[selectedPageId];
    }
}

function toggleTargetUrlField() {
    const parentId = document.getElementById('parent_id').value;
    const targetUrlField = document.getElementById('target_url');
    const targetUrlRequired = document.getElementById('target_url_required');
    const parentHelp = document.getElementById('parent_help');
    const childHelp = document.getElementById('child_help');
    
    if (parentId === '') {
        // Top-level item (parent) - target_url is optional
        targetUrlField.removeAttribute('required');
        targetUrlRequired.style.display = 'none';
        parentHelp.style.display = 'inline';
        childHelp.style.display = 'none';
    } else {
        // Submenu item (child) - target_url is required
        targetUrlField.setAttribute('required', 'required');
        targetUrlRequired.style.display = 'inline';
        parentHelp.style.display = 'none';
        childHelp.style.display = 'inline';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTargetUrlField();
    // If page is pre-selected, auto-fill route
    const pageSelect = document.getElementById('page_id');
    if (pageSelect.value) {
        handlePageSelection();
    }
});
</script>
@endsection
