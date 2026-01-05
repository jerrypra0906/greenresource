@extends('layouts.admin')

@section('title', 'Admin Login – Green Resources CMS')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-logo-mark">GR</div>
            <div>
                <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.18em; color: #9ca3af">
                    Admin
                </div>
                <div style="font-weight: 600; font-size: 0.95rem">
                    Green Resources CMS
                </div>
            </div>
        </div>

        <h1 class="auth-title">Sign in to continue</h1>
        <p class="auth-subtitle">
            Access the content management system for pages, sections, media, and
            inquiries.
        </p>

        <form class="auth-form" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="field">
                <label for="email">
                    Work email
                    <span>*</span>
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    inputmode="email"
                    required
                    value="{{ old('email') }}"
                />
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="field">
                <label for="password">
                    Password
                    <span>*</span>
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    required
                />
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field" style="margin-top: 0.5rem">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="remember" style="cursor: pointer;" />
                    <span style="font-size: 0.85rem">Remember me</span>
                </label>
            </div>

            <div class="auth-meta">
                <span>Single sign-on can be added in a future phase.</span>
                <a href="#">Forgot password?</a>
            </div>

            @if(session('error'))
                <div class="alert alert-error" style="margin-top: 1rem">
                    {{ session('error') }}
                </div>
            @endif

            <button
                class="btn-primary"
                type="submit"
                style="width: 100%; margin-top: 1rem"
            >
                Sign in
            </button>
        </form>

        <div class="auth-footer">
            This is a prototype login screen. Actual authentication, RBAC, and
            security controls will follow the technical architecture.
        </div>
    </div>
</div>
@endsection

