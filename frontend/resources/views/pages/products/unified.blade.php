@extends('layouts.app')

@section('title', 'Products – Green Resources')
@section('description', 'Explore our range of sustainable products including feedstocks, methyl ester, and other solutions.')

@php
// All product data embedded in page
$allProducts = [
    'feedstocks' => [
        'key' => 'feedstocks',
        'title' => 'FEEDSTOCKS',
        'description' => "Feedstock refers to the primary raw materials used in the production of energy, particularly renewable and bio-based fuels. As an energy company specializing in feedstock supply, we provide sustainable materials sourced from renewable resources, waste, and industrial residues.\n\nOur feedstocks meet strict quality, traceability, and sustainability standards, supported by integrated logistics and reliable supply networks to ensure consistent delivery and support the global energy transition.",
        'products' => [
            ['name' => 'POME / RPOME', 'desc' => 'Palm Oil Mill Effluent – a byproduct of palm oil processing used as renewable feedstock.'],
            ['name' => 'UCO / RUCO', 'desc' => 'Used Cooking Oil – recycled cooking oil collected for biodiesel production.'],
            ['name' => 'EFBO', 'desc' => 'Empty Fruit Bunch Oil – extracted from palm empty fruit bunches.'],
            ['name' => 'SBEO', 'desc' => 'Soybean Oil – plant-based oil from soybean processing.'],
            ['name' => 'Trans-esterified Residue', 'desc' => 'Residue generated from biodiesel transesterification process.'],
        ],
    ],
    'methyl' => [
        'key' => 'methyl',
        'title' => 'METHYL ESTER',
        'description' => "Methyl Ester, commonly known as biodiesel, is a clean-burning renewable fuel produced through the transesterification of vegetable oils, animal fats, or recycled greases. It serves as a sustainable alternative to petroleum-based diesel.\n\nOur methyl ester products meet international quality standards and are compatible with existing diesel engines and infrastructure, making them ideal for reducing carbon emissions in transportation and industrial applications.",
        'products' => [
            ['name' => 'UCO / RUCO', 'desc' => 'Used Cooking Oil – recycled cooking oil collected for biodiesel production.'],
            ['name' => 'EFBO', 'desc' => 'Empty Fruit Bunch Oil – extracted from palm empty fruit bunches.'],
        ],
    ],
    'others' => [
        'key' => 'others',
        'title' => 'OTHERS',
        'description' => "Beyond our core feedstock and methyl ester offerings, we supply a range of complementary products derived from the biofuel production process. These byproducts and derivatives support various industrial applications.\n\nOur diversified product portfolio enables us to maximize resource utilization while providing valuable materials to industries including cosmetics, pharmaceuticals, and chemical manufacturing.",
        'products' => [
            ['name' => 'CRUDE GLYCERIN', 'desc' => 'Byproduct of biodiesel production used in cosmetics and pharmaceuticals.'],
            ['name' => 'PF-AD', 'desc' => 'Essential components for soap, detergent, and chemical industries.'],
        ],
    ],
];

// Determine initial active tab from URL query parameter
$initialTab = request()->query('tab', 'feedstocks');
if (!in_array($initialTab, ['feedstocks', 'methyl', 'others'])) {
    $initialTab = 'feedstocks';
}
@endphp

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

{{-- Section 2: Description (Static) --}}
<section class="section">
    <div class="container">
        <div class="products-description">
            <p class="products-description-text">
                All our products, including POME, PME, and UCO, bear the prestigious ISCC certification. Green Resources proudly offers a seamless integrated value chain that encompasses origination, precise logistical arrangements, secure storage, and efficient transportation to our valued clients across the Asia-Pacific region and Europe.
            </p>
            <p class="products-description-text">
                Our proven track record in exporting to these regions underscores our unwavering commitment to reliability and uncompromising quality.
            </p>
        </div>
    </div>
</section>

{{-- Section 3: Tabs (Static - Only Highlight Changes) --}}
<section class="section product-tabs-section">
    <div class="container">
        <div class="product-tabs" id="product-tabs" data-initial-tab="{{ $initialTab }}">
            <button 
                type="button"
                class="product-tab product-tab-btn" 
                data-tab="feedstocks"
                aria-selected="{{ $initialTab === 'feedstocks' ? 'true' : 'false' }}"
            >
                FEEDSTOCKS
            </button>
            <button 
                type="button"
                class="product-tab product-tab-btn" 
                data-tab="methyl"
                aria-selected="{{ $initialTab === 'methyl' ? 'true' : 'false' }}"
            >
                METHYL ESTER
            </button>
            <button 
                type="button"
                class="product-tab product-tab-btn" 
                data-tab="others"
                aria-selected="{{ $initialTab === 'others' ? 'true' : 'false' }}"
            >
                OTHERS
            </button>
        </div>
    </div>
</section>

{{-- Section 4: Product Detail Content (Dynamic - Only This Changes) --}}
<section class="section" id="product-detail-section">
    <div class="container">
        <div id="product-detail-container" class="product-detail-container">
            {{-- Content will be dynamically updated via JavaScript --}}
            @include('pages.products.partials.category-detail', [
                'categoryKey' => $initialTab,
                'categoryTitle' => $allProducts[$initialTab]['title'],
                'categoryDescription' => $allProducts[$initialTab]['description'],
                'products' => $allProducts[$initialTab]['products'],
            ])
        </div>
    </div>
</section>

{{-- Embed product data in page for JavaScript access --}}
<script id="products-data" type="application/json">
@json($allProducts)
</script>

@push('styles')
<style>
    /* Product Tabs - Button Style (override existing .product-tab for buttons) */
    .product-tab-btn {
        flex: 1;
        max-width: 280px;
        padding: 1rem 2rem;
        background-color: #7cb342;
        color: #fff;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s ease;
        font-family: inherit;
    }

    .product-tab-btn:hover {
        background-color: #558b2f;
    }

    .product-tab-btn[aria-selected="true"],
    .product-tab-btn.is-active {
        background-color: #33691e;
    }

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

    .product-detail-content.fade-out {
        opacity: 0;
        transform: translateY(-10px);
        pointer-events: none;
    }

    .product-detail-content.fade-in {
        opacity: 1;
        transform: translateY(0);
    }

    /* Loading skeleton for product detail */
    .product-detail-skeleton {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        min-height: 400px;
        background: linear-gradient(
            90deg,
            #f3f4f6 0%,
            #e5e7eb 50%,
            #f3f4f6 100%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 1rem;
    }

    .product-detail-skeleton.loading {
        display: block;
    }

    @media (prefers-reduced-motion: reduce) {
        .product-detail-content,
        .product-detail-skeleton {
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
    
    // Get product data from embedded JSON
    const productsData = JSON.parse(document.getElementById('products-data').textContent);
    const tabsContainer = document.getElementById('product-tabs');
    const detailContainer = document.getElementById('product-detail-container');
    const tabButtons = tabsContainer.querySelectorAll('.product-tab-btn');
    
    // Get initial tab from data attribute or URL
    let currentTab = tabsContainer.dataset.initialTab || 'feedstocks';
    
    // Initialize active tab
    updateTabHighlight(currentTab);
    
    // Handle tab button clicks
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tab = this.dataset.tab;
            if (tab && tab !== currentTab) {
                switchTab(tab, false);
            }
        });
    });
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(e) {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'feedstocks';
        if (tab !== currentTab && productsData[tab]) {
            switchTab(tab, true); // true = skip URL update (already updated by browser)
        }
    });
    
    /**
     * Switch to a different product tab
     * @param {string} tab - Tab key (feedstocks, methyl, others)
     * @param {boolean} skipUrlUpdate - Skip URL update (for browser navigation)
     */
    function switchTab(tab, skipUrlUpdate = false) {
        if (!productsData[tab] || tab === currentTab) return;
        
        // Update current tab
        currentTab = tab;
        
        // Update tab highlight immediately
        updateTabHighlight(tab);
        
        // Update URL without page reload (unless skipping)
        if (!skipUrlUpdate) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({ tab: tab }, '', url);
        }
        
        // Update content with fade transition
        updateContent(tab);
    }
    
    /**
     * Update tab button highlight
     */
    function updateTabHighlight(tab) {
        tabButtons.forEach(btn => {
            const isActive = btn.dataset.tab === tab;
            btn.setAttribute('aria-selected', isActive);
            btn.classList.toggle('is-active', isActive);
        });
    }
    
    /**
     * Update product detail content with smooth transition
     */
    function updateContent(tab) {
        const data = productsData[tab];
        if (!data) return;
        
        // Find existing content wrapper
        let contentWrapper = detailContainer.querySelector('.product-detail-content');
        
        // Create skeleton loader
        const skeleton = document.createElement('div');
        skeleton.className = 'product-detail-skeleton loading';
        detailContainer.appendChild(skeleton);
        
        // Fade out existing content
        if (contentWrapper) {
            contentWrapper.classList.add('fade-out');
        }
        
        // After fade out, update content
        setTimeout(() => {
            // Remove old content
            if (contentWrapper) {
                contentWrapper.remove();
            }
            
            // Create new content wrapper
            const newWrapper = document.createElement('div');
            newWrapper.className = 'product-detail-content fade-out';
            
            // Generate HTML for new content
            newWrapper.innerHTML = generateProductDetailHTML(data);
            
            // Remove skeleton and add new content
            skeleton.remove();
            detailContainer.appendChild(newWrapper);
            
            // Trigger fade in
            requestAnimationFrame(() => {
                newWrapper.classList.remove('fade-out');
                newWrapper.classList.add('fade-in');
            });
            
            // Re-initialize AppImage components if needed
            if (window.handleImageLoad) {
                setTimeout(() => {
                    const images = newWrapper.querySelectorAll('.app-image');
                    images.forEach(img => {
                        if (img.complete && img.naturalHeight !== 0) {
                            window.handleImageLoad(img.id);
                        }
                    });
                }, 50);
            }
        }, 150); // Half of transition duration
    }
    
    /**
     * Generate HTML for product detail section
     */
    function generateProductDetailHTML(data) {
        const imageMap = {
            'feedstocks': 'assets/images/feedstocks-palm-oil.png',
            'methyl': 'assets/images/methyl-ester.png',
            'others': 'assets/images/others.jpeg',
        };
        const imagePath = imageMap[data.key] || 'assets/images/products.png';
        const productCount = data.products.length;
        
        let productsHTML = '';
        
        if (data.key === 'feedstocks' && productCount >= 5) {
            // Feedstocks: 3 cards top, 2 cards bottom
            const topProducts = data.products.slice(0, 3);
            const bottomProducts = data.products.slice(3, 2);
            
            productsHTML = `
                <div class="product-top-row">
                    ${topProducts.map(p => `
                        <div class="product-card-item">
                            <h3 class="product-card-title">${escapeHtml(p.name)}</h3>
                            <p class="product-card-desc">${escapeHtml(p.desc)}</p>
                        </div>
                    `).join('')}
                </div>
                <div class="product-bottom-row-wrapper">
                    <div class="product-bottom-row">
                        ${bottomProducts.map(p => `
                            <div class="product-card-item">
                                <h3 class="product-card-title">${escapeHtml(p.name)}</h3>
                                <p class="product-card-desc">${escapeHtml(p.desc)}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else {
            // Methyl Ester and Others: 2 cards centered
            productsHTML = `
                <div class="product-two-column">
                    ${data.products.map(p => `
                        <div class="product-card-item">
                            <h3 class="product-card-title">${escapeHtml(p.name)}</h3>
                            <p class="product-card-desc">${escapeHtml(p.desc)}</p>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        const descriptionParagraphs = data.description.split('\n\n').filter(p => p.trim());
        
        // Get base URL for assets
        const baseUrl = '{{ asset("") }}'.replace(/\/$/, '');
        const fullImagePath = baseUrl + '/' + imagePath;
        
        return `
            <div class="category-box">
                <div class="category-title-col">
                    <h2 class="category-title">${escapeHtml(data.title)}</h2>
                </div>
                <div class="category-desc-col">
                    ${descriptionParagraphs.map(p => `<p class="category-desc">${escapeHtml(p)}</p>`).join('')}
                </div>
            </div>
            <div class="product-layout">
                <div class="product-image">
                    <div class="app-image-container app-image-fill" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                        <div class="app-image-skeleton" data-skeleton="product-img-${data.key}" aria-hidden="true">
                            <div class="app-image-shimmer"></div>
                        </div>
                        <img
                            id="product-img-${data.key}"
                            src="${fullImagePath}"
                            alt="${escapeHtml(data.title)} - Product Image"
                            loading="lazy"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                            class="app-image"
                            onload="window.handleImageLoad && window.handleImageLoad('product-img-${data.key}')"
                        />
                    </div>
                </div>
                <div class="product-cards-container">
                    ${productsHTML}
                </div>
            </div>
        `;
    }
    
    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
</script>
@endpush
@endsection
