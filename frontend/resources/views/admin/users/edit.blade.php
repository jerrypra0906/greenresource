@extends('layouts.admin')

@section('title', 'Edit User – Green Resources CMS')

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
                    <h1 class="admin-title">Edit User</h1>
                    <p class="admin-subtitle">Update user information and permissions</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>←</span>
                    <span>Back to Users</span>
                </a>
            </div>

            <div class="admin-card" style="max-width: 700px;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #111827; font-size: 1.125rem; margin-bottom: 0.25rem;">{{ $user->name }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $user->email }}</div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form" style="padding: 2rem;">
                @csrf
                @method('PUT')

                <div class="field" style="margin-bottom: 1.5rem;">
                    <label for="name" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        Name
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="{{ old('name', $user->name) }}"
                        autofocus
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                    />
                    @error('name')
                        <span class="field-error" style="display: block; color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        Email
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="{{ old('email', $user->email) }}"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                    />
                    @error('email')
                        <span class="field-error" style="display: block; color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        New Password
                        <small style="font-weight: 400; color: #6b7280;">(leave blank to keep current password)</small>
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        minlength="8"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                    />
                    @error('password')
                        <span class="field-error" style="display: block; color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; display: block;">
                        Minimum 8 characters. Only fill if you want to change the password.
                    </small>
                </div>

                <div class="field" style="margin-bottom: 1.5rem;">
                    <label for="password_confirmation" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        Confirm New Password
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        minlength="8"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; transition: border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                    />
                </div>

                <div class="field" style="margin-bottom: 1.5rem;">
                    <label for="role" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                        Role
                        <span style="color: #ef4444;">*</span>
                    </label>
                    <select id="role" name="role" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; background: white; transition: border-color 0.15s, box-shadow 0.15s;"
                        onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.1)';"
                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';">
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>👑 Admin - Full access to all features</option>
                        <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>✏️ Editor - Can create and edit content</option>
                        <option value="viewer" {{ old('role', $user->role) === 'viewer' ? 'selected' : '' }}>👁️ Viewer - Read-only access</option>
                    </select>
                    @error('role')
                        <span class="field-error" style="display: block; color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                    <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; display: block; padding: 0.75rem; background: #f9fafb; border-radius: 0.375rem; border-left: 3px solid #10b981;">
                        <strong style="color: #374151;">Role Permissions:</strong><br>
                        <strong>Admin:</strong> Full access to all features including user management<br>
                        <strong>Editor:</strong> Can create and edit content, manage media<br>
                        <strong>Viewer:</strong> Read-only access to view content
                    </small>
                </div>

                    <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <span>✓</span>
                            <span>Update User</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

