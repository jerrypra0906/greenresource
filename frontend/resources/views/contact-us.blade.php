@extends('layouts.app')

@section('title', 'Contact Us – Green Resources')
@section('description', 'Get in touch with Green Resources for corporate, partnership, or sustainability inquiries.')

@section('content')
{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div
                class="about-banner-background"
                style="background-image: url('{{ asset('assets/banners/contact.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            ></div>
        </div>
    </div>
</section>

{{-- Section 2: Contact Two-Column Layout --}}
<section class="section">
    <div class="container">
        <div class="contact-layout">
            {{-- Left Column: Green Panel --}}
            <div class="contact-panel">
                <h2 class="contact-panel-heading">GET IN TOUCH<br>WITH US</h2>
                <p class="contact-panel-text">
                    Reach out to our team to explore potential collaboration opportunities. We look forward to connecting with you and will respond at the earliest opportunity.
                </p>
                
                <div class="contact-panel-info">
                    <p class="contact-panel-company">Green Resources Pte Ltd</p>
                    
                    <div class="contact-panel-row">
                        <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <div>
                            <p>Samsung Hub</p>
                            <p>3 Church St #11-02</p>
                            <p>Singapore 049483</p>
                        </div>
                    </div>
                    
                    <div class="contact-panel-row">
                        <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2" ry="2"></rect>
                            <polyline points="3 7 12 13 21 7"></polyline>
                        </svg>
                        <div>
                            <p>grbio-ops@kpn-corp.com</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Contact Form --}}
            <div class="contact-form-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="contact-form" action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-field">
                        <label for="name">Name <span class="required">*</span></label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="Your full name" />
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="email">Email <span class="required">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="your@email.com" />
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="subject">Inquiry <span class="required">*</span></label>
                        <input id="subject" name="subject" type="text" required value="{{ old('subject') }}" placeholder="Subject of your inquiry" />
                        @error('subject')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" required placeholder="Your message...">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn-primary contact-submit" type="submit">
                        SUBMIT YOUR INQUIRY
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
