<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Green Resources – Corporate Website & CMS')</title>
    <meta name="description" content="@yield('description', 'Green Resources is a modern, sustainable organization with a corporate website and CMS designed for clarity, credibility, and engagement.')" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    @stack('styles')
</head>
<body class="page">
    <header>
        <div class="container">
            <nav class="nav">
                <a href="{{ route('home') }}" class="brand">
                    <img src="{{ asset('assets/Green Resources Logo.png') }}" alt="Green Resources Logo" />
                </a>
                <button
                    class="nav-toggle"
                    type="button"
                    aria-label="Toggle navigation"
                    data-nav-toggle
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="nav-links" data-nav-links>
                    {{-- Hardcoded navigation --}}
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    
                    {{-- Company: Dropdown only (not clickable) --}}
                    <li class="nav-dropdown nav-dropdown-only">
                        <button type="button" class="nav-dropdown-trigger" aria-expanded="false" aria-haspopup="true" aria-label="Company menu">
                            Company
                            <span class="nav-dropdown-caret" aria-hidden="true">▼</span>
                        </button>
                        <ul class="nav-dropdown-menu">
                            <li><a href="{{ route('company.about') }}" class="{{ request()->routeIs('company.about') ? 'active' : '' }}">About Us</a></li>
                            <li><a href="{{ route('company.location') }}" class="{{ request()->routeIs('company.location') ? 'active' : '' }}">Location</a></li>
                            <li><a href="{{ route('company.sustainability') }}" class="{{ request()->routeIs('company.sustainability') ? 'active' : '' }}">Sustainability</a></li>
                            <li><a href="{{ route('company.partner') }}" class="{{ request()->routeIs('company.partner') ? 'active' : '' }}">Commercial Partner</a></li>
                        </ul>
                    </li>
                    
                    {{-- Products: Clickable link + dropdown trigger --}}
                    <li class="nav-dropdown nav-dropdown-with-link">
                        <div class="nav-dropdown-wrapper">
                            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
                            <button type="button" class="nav-dropdown-caret-btn" aria-expanded="false" aria-haspopup="true" aria-label="Products menu">
                                <span class="nav-dropdown-caret" aria-hidden="true">▼</span>
                            </button>
                        </div>
                        <ul class="nav-dropdown-menu">
                            <li><a href="{{ route('products.feedstocks') }}" class="{{ request()->routeIs('products.feedstocks') ? 'active' : '' }}">Feedstocks</a></li>
                            <li><a href="{{ route('products.methyl') }}" class="{{ request()->routeIs('products.methyl') ? 'active' : '' }}">Methyl Ester</a></li>
                            <li><a href="{{ route('products.others') }}" class="{{ request()->routeIs('products.others') ? 'active' : '' }}">Others</a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid footer-grid-two">
                <div>
                    <div class="brand">
                        <img src="{{ asset('assets/Green Resources Logo white.png') }}" alt="Green Resources Logo" style="height: 40px;" />
                    </div>
                    <p class="footer-text">
                        A modern, sustainable organization committed to excellence
                        and environmental responsibility.
                    </p>
                </div>
                <div class="footer-links-right">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('company.about') }}">About Us</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Green Resources. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
