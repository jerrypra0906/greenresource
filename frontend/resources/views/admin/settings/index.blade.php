@extends('layouts.admin')

@section('title', 'Settings – Green Resources CMS')

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
                    <a href="{{ route('admin.settings.index') }}" class="active">Settings</a>
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
            <h1 class="admin-title">Site Settings</h1>
            <p class="admin-subtitle">Configure company information and contact form settings</p>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="admin-card">
                    <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Company Information</h2>
                    
                    <div class="field">
                        <label for="company_name">Company Name *</label>
                        <input type="text" id="company_name" name="company_name" value="{{ $settings['company_name'] }}" required />
                    </div>

                    <div class="field">
                        <label for="company_address">Address</label>
                        <textarea id="company_address" name="company_address" rows="3">{{ $settings['company_address'] }}</textarea>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="company_phone">Phone</label>
                            <input type="text" id="company_phone" name="company_phone" value="{{ $settings['company_phone'] }}" />
                        </div>
                        <div class="field">
                            <label for="company_email">Email *</label>
                            <input type="email" id="company_email" name="company_email" value="{{ $settings['company_email'] }}" required />
                        </div>
                    </div>
                </div>

                <div class="admin-card" style="margin-top: 1.5rem">
                    <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Contact Form Settings</h2>
                    
                    <div class="field">
                        <label for="contact_email_recipients">Recipient Email(s) *</label>
                        <input type="text" id="contact_email_recipients" name="contact_email_recipients" value="{{ $settings['contact_email_recipients'] }}" required />
                        <small style="color: #64748b; font-size: 0.85rem;">Separate multiple emails with commas</small>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="contact_email_cc">CC Email(s)</label>
                            <input type="text" id="contact_email_cc" name="contact_email_cc" value="{{ $settings['contact_email_cc'] }}" />
                        </div>
                        <div class="field">
                            <label for="contact_email_bcc">BCC Email(s)</label>
                            <input type="text" id="contact_email_bcc" name="contact_email_bcc" value="{{ $settings['contact_email_bcc'] }}" />
                        </div>
                    </div>
                </div>

                <div class="admin-card" style="margin-top: 1.5rem">
                    <h2 style="margin-top: 0; margin-bottom: 1.5rem;">Social Media Links</h2>
                    
                    <div class="field-row">
                        <div class="field">
                            <label for="social_facebook">Facebook URL</label>
                            <input type="url" id="social_facebook" name="social_facebook" value="{{ $settings['social_facebook'] }}" />
                        </div>
                        <div class="field">
                            <label for="social_twitter">Twitter URL</label>
                            <input type="url" id="social_twitter" name="social_twitter" value="{{ $settings['social_twitter'] }}" />
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label for="social_linkedin">LinkedIn URL</label>
                            <input type="url" id="social_linkedin" name="social_linkedin" value="{{ $settings['social_linkedin'] }}" />
                        </div>
                        <div class="field">
                            <label for="social_instagram">Instagram URL</label>
                            <input type="url" id="social_instagram" name="social_instagram" value="{{ $settings['social_instagram'] }}" />
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

