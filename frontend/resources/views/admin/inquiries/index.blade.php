@extends('layouts.admin')

@section('title', 'Inquiries – Green Resources CMS')

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
            <h1 class="admin-title">Inquiries</h1>
            <p class="admin-subtitle">View and manage contact form submissions</p>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <div class="admin-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                            <tr>
                                <td>{{ $inquiry->name }}</td>
                                <td>{{ $inquiry->email }}</td>
                                <td>{{ $inquiry->company ?? '-' }}</td>
                                <td>{{ Str::limit($inquiry->subject, 40) }}</td>
                                <td>
                                    <span class="badge {{ $inquiry->status === 'new' ? 'badge-live' : ($inquiry->status === 'handled' ? 'badge-draft' : '') }}">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                </td>
                                <td>{{ $inquiry->submitted_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn-small">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    No inquiries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($inquiries->hasPages())
                    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                        {{ $inquiries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection

