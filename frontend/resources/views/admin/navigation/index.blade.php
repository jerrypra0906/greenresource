@extends('layouts.admin')

@section('title', 'Navigation Menu – Green Resources CMS')

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
                    <h1 class="admin-title">Navigation Menu</h1>
                    <p class="admin-subtitle">Manage website navigation menu items</p>
                </div>
                <a href="{{ route('admin.navigation.create') }}" class="btn-primary">Add Menu Item</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="margin-bottom: 1.5rem">
                    {{ session('error') }}
                </div>
            @endif

            <div class="admin-card">
                @forelse($items as $item)
                    <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem; background: #f9fafb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <div>
                                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #111827;">
                                    {{ $item->label }}
                                    @if(!$item->visible)
                                        <span class="badge badge-draft" style="margin-left: 0.5rem;">Hidden</span>
                                    @endif
                                </h3>
                                <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.875rem;">
                                    Route: <code>{{ $item->target_url }}</code>
                                    @if($item->page)
                                        | Page: <a href="{{ route('pages.edit', $item->page) }}" style="color: #059669; text-decoration: underline;">{{ $item->page->title }}</a>
                                    @endif
                                    | Order: {{ $item->order }}
                                </p>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.navigation.edit', $item) }}" class="btn-small">Edit</a>
                                <form method="POST" action="{{ route('admin.navigation.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small" style="background: #ef4444; color: white; border: none;">Delete</button>
                                </form>
                            </div>
                        </div>
                        
                        @if($item->children->count() > 0)
                            <div style="margin-top: 1rem; padding-left: 1.5rem; border-left: 3px solid #059669;">
                                <div style="font-size: 0.875rem; font-weight: 600; color: #059669; margin-bottom: 0.5rem;">Submenu Items:</div>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    @foreach($item->children as $child)
                                        <li style="padding: 0.75rem; background: white; border-radius: 0.375rem; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <span style="font-weight: 500;">{{ $child->label }}</span>
                                                @if(!$child->visible)
                                                    <span class="badge badge-draft" style="margin-left: 0.5rem; font-size: 0.75rem;">Hidden</span>
                                                @endif
                                                <span style="color: #6b7280; font-size: 0.875rem; margin-left: 0.5rem;">
                                                    → <code>{{ $child->target_url }}</code>
                                                    @if($child->page)
                                                        | Page: <a href="{{ route('pages.edit', $child->page) }}" style="color: #059669; text-decoration: underline;">{{ $child->page->title }}</a>
                                                    @endif
                                                    (Order: {{ $child->order }})
                                                </span>
                                            </div>
                                            <div style="display: flex; gap: 0.5rem;">
                                                <a href="{{ route('admin.navigation.edit', $child) }}" class="btn-small" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Edit</a>
                                                <form method="POST" action="{{ route('admin.navigation.destroy', $child) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this submenu item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-small" style="background: #ef4444; color: white; border: none; font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem;">
                        <p style="color: #6b7280; margin-bottom: 1rem;">No navigation items found.</p>
                        <a href="{{ route('admin.navigation.create') }}" class="btn-primary">Create First Menu Item</a>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 2rem; padding: 1.5rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 600; color: #1e40af;">📝 Note</h3>
                <p style="margin: 0; color: #1e3a8a; font-size: 0.875rem; line-height: 1.6;">
                    After creating or updating navigation items, make sure to update the layout template to use database-driven navigation 
                    (see <code>docs/ADDING_SUBMENU_GUIDE.md</code> for instructions). Currently, the menu is hardcoded in the Blade template.
                </p>
            </div>
        </div>
    </main>
</div>
@endsection
