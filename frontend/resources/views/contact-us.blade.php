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
                style="background-image: linear-gradient(135deg, rgba(4, 101, 84, 0.8) 0%, rgba(5, 150, 105, 0.6) 100%), url('{{ asset('assets/banners/contact.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
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
                            <p>3 Church St #13-02</p>
                            <p>Singapore 049483</p>
                        </div>
                    </div>
                    
                    <div class="contact-panel-row">
                        <svg class="contact-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <div>
                            <p>Tel: +65 6571 6500</p>
                            <p>Fax: +65 6224 6625</p>
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
