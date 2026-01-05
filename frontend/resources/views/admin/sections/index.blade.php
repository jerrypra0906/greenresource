@extends('layouts.admin')

@section('title', 'Manage Sections – Green Resources CMS')

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
                    <h1 class="admin-title">Manage Sections</h1>
                    <p class="admin-subtitle">{{ $page->title }}</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('sections.create', $page) }}" class="btn-primary">Add New Section</a>
                    <a href="{{ route('pages.edit', $page) }}" class="btn-secondary">Back to Page</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-card">
                @if($sections->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Order</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Content Preview</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $section)
                                <tr>
                                    <td>
                                        <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $section->order }}</code>
                                    </td>
                                    <td>
                                        <code style="background: #eff6ff; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $section->type }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $section->title ?? 'No title' }}</strong>
                                    </td>
                                    <td>
                                        @if($section->body)
                                            <span style="color: #6b7280; font-size: 0.875rem;">
                                                {{ Str::limit(strip_tags($section->body), 50) }}
                                            </span>
                                        @else
                                            <span style="color: #9ca3af;">No content</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ route('sections.edit', ['page' => $page, 'section' => $section]) }}" class="btn-small">Edit</a>
                                            <form method="POST" action="{{ route('sections.destroy', ['page' => $page, 'section' => $section]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this section?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-small" style="background: #ef4444; color: white; border: none;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 3rem;">
                        <p style="color: #6b7280; margin-bottom: 1.5rem;">No sections yet for this page.</p>
                        <a href="{{ route('sections.create', $page) }}" class="btn-primary">Add Your First Section</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection

