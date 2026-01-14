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
                    @if(isset($menuItems) && $menuItems->count() > 0)
                        {{-- Database-driven navigation --}}
                        @foreach($menuItems as $item)
                            @php
                                $itemUrl = '#';
                                if ($item->target_url !== '#') {
                                    if (str_starts_with($item->target_url, 'http')) {
                                        $itemUrl = $item->target_url;
                                    } else {
                                        try {
                                            $itemUrl = route($item->target_url);
                                        } catch (\Exception $e) {
                                            $itemUrl = $item->target_url;
                                        }
                                    }
                                }
                            @endphp
                            @if($item->children->count() > 0)
                                <li class="nav-dropdown">
                                    <a href="{{ $itemUrl }}">{{ $item->label }}</a>
                                    <ul class="nav-dropdown-menu">
                                        @foreach($item->children as $child)
                                            @php
                                                $childUrl = '#';
                                                if ($child->target_url !== '#') {
                                                    if (str_starts_with($child->target_url, 'http')) {
                                                        $childUrl = $child->target_url;
                                                    } else {
                                                        try {
                                                            $childUrl = route($child->target_url);
                                                        } catch (\Exception $e) {
                                                            $childUrl = $child->target_url;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <li>
                                                <a href="{{ $childUrl }}">{{ $child->label }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $itemUrl }}">{{ $item->label }}</a>
                                </li>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback: Hardcoded navigation --}}
                        <li class="nav-dropdown">
                            <a href="#">Company</a>
                            <ul class="nav-dropdown-menu">
                                <li><a href="{{ route('company.about-us') }}">About Us</a></li>
                                <li><a href="{{ route('company.location') }}">Location</a></li>
                            </ul>
                        </li>
                        <li class="nav-dropdown">
                            <a href="#">Product</a>
                            <ul class="nav-dropdown-menu">
                                <li><a href="{{ route('product.feedstocks') }}">Feedstocks</a></li>
                                <li><a href="{{ route('product.methyl-ester') }}">Methyl Ester</a></li>
                                <li><a href="{{ route('product.other') }}">Other</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('contact-us.fulfill-form') }}">Contact Us</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="brand">
                        <img src="{{ asset('assets/Green Resources Logo white.png') }}" alt="Green Resources Logo" style="height: 40px;" />
                    </div>
                    <p class="footer-text">
                        A modern, sustainable organization committed to excellence
                        and environmental responsibility.
                    </p>
                </div>
                <div>
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('company.about-us') }}">About Us</a></li>
                        <li><a href="{{ route('product.feedstocks') }}">Products</a></li>
                        <li><a href="{{ route('contact-us.fulfill-form') }}">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer-heading">Connect</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact-us.fulfill-form') }}">Contact Form</a></li>
                        <li><a href="{{ route('contact-us.contacts') }}">Contacts</a></li>
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
