@extends('layouts.admin')

@section('title', 'Users – Green Resources CMS')

@push('styles')
<style>
    .user-table-row:hover {
        background: #f9fafb !important;
    }
    .btn-small:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-danger:hover {
        background: #fecaca !important;
        border-color: #fca5a5 !important;
    }
</style>
@endpush

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
                    <a href="{{ route('admin.inquiries.index') }}">Inquiries</a>
                    <a href="{{ route('admin.settings.index') }}">Settings</a>
                    <a href="{{ route('admin.users.index') }}" class="active">Users</a>
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
                    <h1 class="admin-title">User Management</h1>
                    <p class="admin-subtitle">Manage admin users, editors, and viewers</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>➕</span>
                    <span>Create New User</span>
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; border-radius: 0.375rem;">
                    <strong>✓</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; border-radius: 0.375rem;">
                    <strong>✗</strong> {{ session('error') }}
                </div>
            @endif

            @if($users->count() > 0)
                <div class="admin-card" style="padding: 0; overflow: hidden;">
                    <div style="background: #f9fafb; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 1.25rem;">👥</span>
                                <span style="font-weight: 600; color: #111827;">Total Users: <strong style="color: #10b981;">{{ $users->count() }}</strong></span>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                @php
                                    $roleCounts = $users->groupBy('role')->map->count();
                                @endphp
                                <span style="padding: 0.375rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500;">
                                    Admin: {{ $roleCounts->get('admin', 0) }}
                                </span>
                                <span style="padding: 0.375rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500;">
                                    Editor: {{ $roleCounts->get('editor', 0) }}
                                </span>
                                <span style="padding: 0.375rem 0.75rem; background: #f3f4f6; color: #374151; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500;">
                                    Viewer: {{ $roleCounts->get('viewer', 0) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="table" style="margin: 0;">
                            <thead>
                                <tr style="background: #f9fafb;">
                                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: #374151; text-align: left; border-bottom: 2px solid #e5e7eb;">User</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: #374151; text-align: left; border-bottom: 2px solid #e5e7eb;">Role</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: #374151; text-align: left; border-bottom: 2px solid #e5e7eb;">Last Login</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: #374151; text-align: left; border-bottom: 2px solid #e5e7eb;">Created</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 600; color: #374151; text-align: right; border-bottom: 2px solid #e5e7eb;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="user-table-row" style="border-bottom: 1px solid #e5e7eb; transition: background 0.15s;">
                                        <td style="padding: 1.25rem 1.5rem;">
                                            <div style="display: flex; align-items: center; gap: 1rem;">
                                                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.125rem; flex-shrink: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: #111827; margin-bottom: 0.25rem;">{{ $user->name }}</div>
                                                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 1.25rem 1.5rem;">
                                            @if($user->role === 'admin')
                                                <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: #dbeafe; color: #1e40af; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                                                    <span>👑</span>
                                                    <span>Admin</span>
                                                </span>
                                            @elseif($user->role === 'editor')
                                                <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                                                    <span>✏️</span>
                                                    <span>Editor</span>
                                                </span>
                                            @else
                                                <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: #f3f4f6; color: #374151; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                                                    <span>👁️</span>
                                                    <span>Viewer</span>
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 1.25rem 1.5rem; color: #6b7280;">
                                            @if($user->last_login_at)
                                                <div style="font-weight: 500; color: #111827;">{{ $user->last_login_at->format('M d, Y') }}</div>
                                                <div style="font-size: 0.875rem; color: #9ca3af;">{{ $user->last_login_at->format('H:i') }}</div>
                                            @else
                                                <span style="color: #9ca3af; font-style: italic;">Never</span>
                                            @endif
                                        </td>
                                        <td style="padding: 1.25rem 1.5rem; color: #6b7280;">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; flex-wrap: wrap;">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-small" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.15s;">
                                                    <span>✏️</span>
                                                    <span>Edit</span>
                                                </a>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-small btn-danger" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.15s;">
                                                        <span>🗑️</span>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="admin-card" style="text-align: center; padding: 4rem 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">👥</div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">No users found</h3>
                    <p style="color: #6b7280; margin-bottom: 2rem;">Get started by creating your first user.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn-primary">Create New User</a>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection

