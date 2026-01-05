@extends('layouts.admin')

@section('title', 'Pages – Green Resources CMS')

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
                    <h1 class="admin-title">Pages</h1>
                    <p class="admin-subtitle">Manage website pages and content</p>
                </div>
                <a href="{{ route('pages.create') }}" class="btn-primary">Create New Page</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>{{ $page->title }}</td>
                                <td><code>{{ $page->slug }}</code></td>
                                <td>
                                    <span class="badge {{ $page->status === 'published' ? 'badge-live' : 'badge-draft' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </td>
                                <td>{{ $page->updated_at->format('M d, Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <a href="{{ route('pages.edit', $page) }}" class="btn-small">Edit</a>
                                        <a href="{{ route('pages.show', $page) }}" class="btn-small">View</a>
                                        <a href="{{ route('pages.preview', $page) }}" target="_blank" class="btn-small" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Preview</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">
                                    No pages found. <a href="{{ route('pages.create') }}">Create your first page</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection

