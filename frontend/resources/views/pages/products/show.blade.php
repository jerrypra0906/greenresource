@extends('layouts.app')

@section('title', $categoryTitle . ' – Green Resources')
@section('description', 'Explore our ' . strtolower($categoryTitle) . ' products and solutions.')

@section('content')
{{-- Section 1: Banner (Static - Never Changes) --}}
<section class="section banner-section" id="products-banner">
    <div class="container banner-container">
        <div class="about-banner">
            <div
                class="about-banner-background"
                style="background-image: url('{{ asset('assets/banners/products.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            ></div>
        </div>
    </div>
</section>

{{-- Section 2: Category Tabs (Static - Only Highlight Changes) --}}
<section class="section product-tabs-section">
    <div class="container">
        <div class="product-tabs" id="product-tabs" data-current-category="{{ $categoryKey }}">
            <a 
                href="{{ route('products.show', 'feedstocks') }}" 
                class="product-tab {{ $categoryKey === 'feedstocks' ? 'is-active' : '' }}"
                data-category="feedstocks"
            >
                FEEDSTOCKS
            </a>
            <a 
                href="{{ route('products.show', 'waste-based-fame') }}" 
                class="product-tab {{ $categoryKey === 'methyl' ? 'is-active' : '' }}"
                data-category="waste-based-fame"
            >
                WASTE-BASED FAME
            </a>
            <a 
                href="{{ route('products.show', 'others') }}" 
                class="product-tab {{ $categoryKey === 'others' ? 'is-active' : '' }}"
                data-category="others"
            >
                OTHERS
            </a>
        </div>
    </div>
</section>

{{-- Section 3: Product Detail Content (Dynamic - Only This Changes) --}}
<section class="section" id="product-detail-section">
    <div class="container">
        <div id="product-detail-container" class="product-detail-container">
            @include('pages.products.partials.detail', [
                'categoryKey' => $categoryKey,
                'categoryTitle' => $categoryTitle,
                'categoryDescription' => $categoryDescription,
                'products' => $products,
            ])
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Product Detail Container - Transition */
    .product-detail-container {
        position: relative;
        min-height: 400px;
    }

    .product-detail-content {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .product-detail-container.is-loading .product-detail-content {
        opacity: 0.6;
        pointer-events: none;
    }

    .product-detail-container.is-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            #f3f4f6 0%,
            #e5e7eb 50%,
            #f3f4f6 100%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 1rem;
        z-index: 1;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .product-detail-content,
        .product-detail-container.is-loading::after {
            transition: opacity 0.15s ease;
            animation: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';
    
    /**
     * Get current category slug from URL
     */
    function getCurrentCategorySlug() {
        const path = window.location.pathname;
        const match = path.match(/\/products\/([^\/]+)/);
        return match ? match[1] : null;
    }
    
    /**
     * Update tab button highlight
     */
    function updateTabHighlight(categorySlug) {
        const tabLinks = document.querySelectorAll('#product-tabs .product-tab');
        tabLinks.forEach(link => {
            const isActive = link.dataset.category === categorySlug;
            link.classList.toggle('is-active', isActive);
        });
    }
    
    /**
     * Load category detail via AJAX
     */
    function loadCategoryDetail(categorySlug, url) {
        const detailContainer = document.getElementById('product-detail-container');
        
        if (!detailContainer) {
            console.error('Product detail container not found');
            window.location.href = url;
            return;
        }
        
        // Update tab highlights immediately
        updateTabHighlight(categorySlug);
        
        // Add loading state
        detailContainer.classList.add('is-loading');
        
        // Fetch partial content
        fetch(url, {
            headers: {
                'X-Partial': 'product-detail',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Remove loading state
            detailContainer.classList.remove('is-loading');
            
            // Update content
            detailContainer.innerHTML = html;
            
            // Update URL without reload
            window.history.pushState({ category: categorySlug }, '', url);
            
            // Re-initialize AppImage components if needed
            if (window.handleImageLoad) {
                setTimeout(() => {
                    const images = detailContainer.querySelectorAll('.app-image');
                    images.forEach(img => {
                        if (img.complete && img.naturalHeight !== 0) {
                            window.handleImageLoad(img.id);
                        }
                    });
                }, 50);
            }
        })
        .catch(error => {
            console.error('Error loading category:', error);
            // Fallback: reload page normally
            detailContainer.classList.remove('is-loading');
            window.location.href = url;
        });
    }
    
    /**
     * Initialize product tabs functionality
     */
    function initProductTabs() {
        const tabsContainer = document.getElementById('product-tabs');
        const detailContainer = document.getElementById('product-detail-container');
        
        if (!tabsContainer || !detailContainer) {
            console.warn('Product tabs container not found');
            return;
        }
        
        const tabLinks = tabsContainer.querySelectorAll('.product-tab');
        
        // Progressive enhancement: intercept tab clicks for AJAX
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Only intercept if JavaScript is enabled and it's not the current tab
                const targetCategory = this.dataset.category;
                const currentSlug = getCurrentCategorySlug();
                
                if (targetCategory === currentSlug) {
                    e.preventDefault();
                    return false;
                }
                
                // Check if we should use AJAX (modern browser, not middle-click, etc.)
                if (e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0) {
                    return; // Let browser handle it normally
                }
                
                e.preventDefault();
                loadCategoryDetail(targetCategory, this.href);
            });
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(e) {
            const path = window.location.pathname;
            const match = path.match(/\/products\/([^\/]+)/);
            if (match) {
                const categorySlug = match[1];
                const activeLink = Array.from(tabLinks).find(link => link.dataset.category === categorySlug);
                if (activeLink) {
                    loadCategoryDetail(categorySlug, activeLink.href);
                }
            }
        });
    }
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProductTabs);
    } else {
        initProductTabs();
    }
})();
</script>
@endpush
@endsection
