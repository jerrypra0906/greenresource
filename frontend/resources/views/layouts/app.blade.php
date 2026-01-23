<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Green Resources – Corporate Website & CMS')</title>
    <meta name="description" content="@yield('description', 'Green Resources is a modern, sustainable organization with a corporate website and CMS designed for clarity, credibility, and engagement.')" />
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <meta name="theme-color" content="#2d5016" />
    <meta name="msapplication-TileColor" content="#2d5016" />
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}" />
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    
    {{-- Preload critical banner images for current page --}}
    @if(request()->routeIs('home'))
        <link rel="preload" as="image" href="{{ asset('assets/banners/home.png') }}" />
    @elseif(request()->routeIs('products.*'))
        <link rel="preload" as="image" href="{{ asset('assets/banners/products.png') }}" />
    @elseif(request()->routeIs('contact'))
        <link rel="preload" as="image" href="{{ asset('assets/banners/contact.png') }}" />
    @elseif(request()->routeIs('company.about'))
        <link rel="preload" as="image" href="{{ asset('assets/banners/about.png') }}" />
    @elseif(request()->routeIs('company.sustainability'))
        <link rel="preload" as="image" href="{{ asset('assets/banners/sustainability.png') }}" />
    @endif
    
    @stack('styles')
    @stack('preload')
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
                            <!-- <li><a href="{{ route('company.location') }}" class="{{ request()->routeIs('company.location') ? 'active' : '' }}">Location</a></li> -->
                            <li><a href="{{ route('company.sustainability') }}" class="{{ request()->routeIs('company.sustainability') ? 'active' : '' }}">Sustainability</a></li>
                            <!-- <li><a href="{{ route('company.partner') }}" class="{{ request()->routeIs('company.partner') ? 'active' : '' }}">Commercial Partner</a></li> -->
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
                            <li><a href="{{ route('products.show', 'feedstocks') }}" class="{{ request()->routeIs('products.show') && request()->segment(2) === 'feedstocks' ? 'active' : '' }}">Feedstocks</a></li>
                            <li><a href="{{ route('products.show', 'methyl-ester') }}" class="{{ request()->routeIs('products.show') && request()->segment(2) === 'methyl-ester' ? 'active' : '' }}">Methyl Ester</a></li>
                            <li><a href="{{ route('products.show', 'others') }}" class="{{ request()->routeIs('products.show') && request()->segment(2) === 'others' ? 'active' : '' }}">Others</a></li>
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
