<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates menu structure with case-insensitive, trimmed handling.
     */
    public function run(): void
    {
        // Helper function to find or create navigation item (case-insensitive, trimmed)
        $findOrCreate = function ($label, $targetUrl, $order, $parentId = null, $visible = true) {
            $normalizedLabel = trim($label);
            $normalizedSlug = Str::slug(strtolower($normalizedLabel));
            
            return NavigationItem::updateOrCreate(
                [
                    'label' => $normalizedLabel,
                    'parent_id' => $parentId,
                ],
                [
                    'target_url' => $targetUrl,
                    'order' => $order,
                    'visible' => $visible,
                ]
            );
        };

        // Clear existing navigation items (optional - comment out if you want to preserve existing)
        // NavigationItem::truncate();

        // 1. Home (top-level)
        $home = $findOrCreate('Home', 'home', 1);

        // 2. Company (parent with submenu)
        $company = $findOrCreate('Company', '#', 2);
        
        // Company submenu items
        $findOrCreate('About Us', 'company.about-us', 1, $company->id);
        $findOrCreate('Location', 'company.location', 2, $company->id);
        $findOrCreate('Sustainability', 'company.sustainability', 3, $company->id);
        $findOrCreate('Commercial Partner', 'company.commercial-partner', 4, $company->id);

        // 3. Product (parent with submenu)
        $product = $findOrCreate('Product', '#', 3);
        
        // Product submenu items
        $findOrCreate('Feedstocks', 'product.feedstocks', 1, $product->id);
        $findOrCreate('Methyl Ester', 'product.methyl-ester', 2, $product->id);
        $findOrCreate('Other', 'product.other', 3, $product->id);

        // 4. Contact Us (parent with submenu)
        $contactUs = $findOrCreate('Contact Us', '#', 4);
        
        // Contact Us submenu items
        $findOrCreate('Fulfill Form', 'contact-us.fulfill-form', 1, $contactUs->id);
        $findOrCreate('Contacts', 'contact-us.contacts', 2, $contactUs->id);
    }
}
