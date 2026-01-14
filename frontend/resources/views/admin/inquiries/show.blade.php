@extends('layouts.admin')

@section('title', 'View Inquiry – Green Resources CMS')

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
                    <a href="{{ route('admin.navigation.index') }}">Navigation</a>
                    <a href="{{ route('admin.inquiries.index') }}" class="active">Inquiries</a>
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
            <div style="margin-bottom: 1.5rem;">
                <a href="{{ route('admin.inquiries.index') }}" class="btn-ghost" style="display: inline-block;">← Back to Inquiries</a>
            </div>

            <h1 class="admin-title">Inquiry Details</h1>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-card">
                <div style="margin-bottom: 1.5rem;">
                    <strong>Status:</strong>
                    <span class="badge {{ $inquiry->status === 'new' ? 'badge-live' : ($inquiry->status === 'handled' ? 'badge-draft' : '') }}" style="margin-left: 0.5rem;">
                        {{ ucfirst($inquiry->status) }}
                    </span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <strong>Name:</strong>
                        <p>{{ $inquiry->name }}</p>
                    </div>
                    <div>
                        <strong>Email:</strong>
                        <p><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></p>
                    </div>
                    @if($inquiry->company)
                    <div>
                        <strong>Company:</strong>
                        <p>{{ $inquiry->company }}</p>
                    </div>
                    @endif
                    @if($inquiry->phone)
                    <div>
                        <strong>Phone:</strong>
                        <p><a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a></p>
                    </div>
                    @endif
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <strong>Subject:</strong>
                    <p>{{ $inquiry->subject }}</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <strong>Message:</strong>
                    <p style="white-space: pre-wrap;">{{ $inquiry->message }}</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <strong>Submitted:</strong>
                    <p>{{ $inquiry->submitted_at->format('F j, Y \a\t g:i A') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.inquiries.update-status', $inquiry) }}" style="margin-top: 2rem;">
                    @csrf
                    @method('PATCH')
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <label>
                            <strong>Update Status:</strong>
                            <select name="status" style="margin-left: 0.5rem; padding: 0.5rem;">
                                <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                                <option value="handled" {{ $inquiry->status === 'handled' ? 'selected' : '' }}>Handled</option>
                                <option value="archived" {{ $inquiry->status === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </label>
                        <button type="submit" class="btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

