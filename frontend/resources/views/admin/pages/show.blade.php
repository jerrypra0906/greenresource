@extends('layouts.admin')

@section('title', 'View Page – Green Resources CMS')

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
                    <h1 class="admin-title">{{ $page->title }}</h1>
                    <p class="admin-subtitle">Page Details</p>
                </div>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('pages.edit', $page) }}" class="btn-primary">Edit Page</a>
                    <a href="{{ route('pages.preview', $page) }}" target="_blank" class="btn-secondary" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">
                        👁️ Preview Page
                    </a>
                    <a href="{{ route('pages.index') }}" class="btn-secondary">Back to Pages</a>
                </div>
            </div>

            <div class="admin-card">
                <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div style="font-weight: 600; color: #374151;">Slug:</div>
                    <div><code>{{ $page->slug }}</code></div>

                    <div style="font-weight: 600; color: #374151;">Title:</div>
                    <div>{{ $page->title }}</div>

                    <div style="font-weight: 600; color: #374151;">Status:</div>
                    <div>
                        <span class="badge {{ $page->status === 'published' ? 'badge-live' : 'badge-draft' }}">
                            {{ ucfirst($page->status) }}
                        </span>
                    </div>

                    <div style="font-weight: 600; color: #374151;">Meta Title:</div>
                    <div>{{ $page->meta_title ?? 'Not set' }}</div>

                    <div style="font-weight: 600; color: #374151;">Meta Description:</div>
                    <div>{{ $page->meta_description ?? 'Not set' }}</div>

                    <div style="font-weight: 600; color: #374151;">Created:</div>
                    <div>{{ $page->created_at->format('M d, Y H:i') }}</div>

                    <div style="font-weight: 600; color: #374151;">Updated:</div>
                    <div>{{ $page->updated_at->format('M d, Y H:i') }}</div>
                </div>

                @if($page->banner)
                    <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem; margin-top: 1.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Banner Configuration</h3>
                        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem;">
                            <div style="font-weight: 600; color: #374151;">Title:</div>
                            <div>{{ $page->banner['title'] ?? 'Not set' }}</div>

                            <div style="font-weight: 600; color: #374151;">Subtitle:</div>
                            <div>{{ $page->banner['subtitle'] ?? 'Not set' }}</div>

                            <div style="font-weight: 600; color: #374151;">Image:</div>
                            <div><code>{{ $page->banner['image'] ?? 'Not set' }}</code></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="admin-card" style="margin-top: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 600;">Page Sections</h2>
                    <a href="{{ route('sections.index', $page) }}" class="btn-primary">Manage Sections</a>
                </div>
                @if($page->sections->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($page->sections as $section)
                                <tr>
                                    <td><code>{{ $section->type }}</code></td>
                                    <td>{{ $section->title ?? 'No title' }}</td>
                                    <td>{{ $section->order }}</td>
                                    <td>
                                        <a href="{{ route('sections.edit', ['page' => $page, 'section' => $section]) }}" class="btn-small">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #6b7280; padding: 2rem; text-align: center;">
                        No sections yet. <a href="{{ route('sections.create', $page) }}">Add your first section</a>
                    </p>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection

