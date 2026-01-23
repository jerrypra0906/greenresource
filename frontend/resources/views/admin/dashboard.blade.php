@extends('layouts.admin')

@section('title', 'Admin Dashboard – Green Resources CMS')

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
                    <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
                    <a href="{{ route('pages.index') }}">Pages</a>
                    <a href="{{ route('media.index') }}">Media</a>
                    <a href="{{ route('admin.navigation.index') }}">Navigation</a>
                    <a href="{{ route('admin.inquiries.index') }}">Inquiries</a>
                    <a href="{{ route('admin.settings.index') }}">Settings</a>
                    <a href="{{ route('admin.users.index') }}">Users</a>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; font-size: inherit; font-family: inherit; padding: 0; text-decoration: underline; margin: 0;">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <h1 class="admin-title">Dashboard</h1>
            <p class="admin-subtitle">Welcome to the Green Resources CMS</p>

            <div class="admin-grid">
                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">📄</div>
                        <h3 class="card-title">Pages</h3>
                    </div>
                    <p class="card-body">Manage website pages and content sections.</p>
                    <a href="{{ route('pages.index') }}" class="btn-primary">Manage Pages</a>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">🖼️</div>
                        <h3 class="card-title">Media Library</h3>
                    </div>
                    <p class="card-body">Upload and manage images, documents, and assets.</p>
                    <a href="{{ route('media.index') }}" class="btn-primary">Manage Media</a>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">📧</div>
                        <h3 class="card-title">Inquiries</h3>
                    </div>
                    <p class="card-body">View and manage contact form submissions.</p>
                    <a href="{{ route('admin.inquiries.index') }}" class="btn-primary">View Inquiries</a>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">⚙️</div>
                        <h3 class="card-title">Settings</h3>
                    </div>
                    <p class="card-body">Configure site settings, navigation, and contact form recipients.</p>
                    <a href="{{ route('admin.settings.index') }}" class="btn-primary">Manage Settings</a>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">👥</div>
                        <h3 class="card-title">User Management</h3>
                    </div>
                    <p class="card-body">Create and manage admin users, editors, and viewers.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn-primary">Manage Users</a>
                </div>

                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-icon">🔒</div>
                        <h3 class="card-title">Security Status</h3>
                    </div>
                    <div class="card-body">
                        <ul class="card-list">
                            <li><strong>Stored XSS Protection:</strong> Rich text content is sanitized via HTMLPurifier.</li>
                            <li><strong>Login Rate Limiting:</strong> Admin login limited to 5 attempts per minute per IP.</li>
                            <li><strong>Security Headers:</strong> Global middleware adds X-Frame-Options, X-Content-Type-Options, and other headers.</li>
                            <li><strong>CSRF Protection:</strong> Enabled on all forms via Laravel middleware.</li>
                        </ul>
                        <p style="margin-top: 0.75rem; font-size: 0.85rem; color: #6b7280;">
                            For a full report, see <code>SECURITY_REVIEW.md</code> and <code>PENETRATION_TEST_RESULTS.md</code> in the project.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

