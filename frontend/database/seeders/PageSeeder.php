<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates all required pages with case-insensitive, trimmed slug handling.
     */
    public function run(): void
    {
        // Helper function to normalize slug (case-insensitive, trimmed)
        $normalizeSlug = function ($slug) {
            return trim(strtolower($slug));
        };

        $pages = [
            [
                'slug' => $normalizeSlug('home'),
                'title' => 'Home',
                'meta_title' => 'Green Resources – Sustainable Solutions',
                'meta_description' => 'Green Resources is a modern, sustainable organization committed to excellence and environmental responsibility.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Welcome to Green Resources',
                    'subtitle' => 'Sustainable Solutions for a Better Future',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('company-about-us'),
                'title' => 'About Us',
                'meta_title' => 'About Us – Green Resources',
                'meta_description' => 'Learn about Green Resources, our mission, values, and commitment to sustainability.',
                'status' => 'published',
                'banner' => [
                    'title' => 'About Us',
                    'subtitle' => 'Our Story and Mission',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('company-location'),
                'title' => 'Location',
                'meta_title' => 'Location – Green Resources',
                'meta_description' => 'Find our office locations and contact information.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Our Location',
                    'subtitle' => 'Visit Us',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('company-sustainability'),
                'title' => 'Sustainability',
                'meta_title' => 'Sustainability – Green Resources',
                'meta_description' => 'Our commitment to sustainable practices and environmental responsibility.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Sustainability',
                    'subtitle' => 'Our Environmental Commitment',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('company-commercial-partner'),
                'title' => 'Commercial Partner',
                'meta_title' => 'Commercial Partner – Green Resources',
                'meta_description' => 'Partner with Green Resources for sustainable business solutions.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Commercial Partner',
                    'subtitle' => 'Partner With Us',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('product-feedstocks'),
                'title' => 'Feedstocks',
                'meta_title' => 'Feedstocks – Green Resources Products',
                'meta_description' => 'Explore our range of sustainable feedstock products.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Feedstocks',
                    'subtitle' => 'Sustainable Feedstock Solutions',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('product-methyl-ester'),
                'title' => 'Methyl Ester',
                'meta_title' => 'Methyl Ester – Green Resources Products',
                'meta_description' => 'High-quality methyl ester products for various applications.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Methyl Ester',
                    'subtitle' => 'Premium Quality Products',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('product-other'),
                'title' => 'Other Products',
                'meta_title' => 'Other Products – Green Resources',
                'meta_description' => 'Discover our other sustainable product offerings.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Other Products',
                    'subtitle' => 'Additional Solutions',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('contact-us-fulfill-form'),
                'title' => 'Fulfill Form',
                'meta_title' => 'Contact Form – Green Resources',
                'meta_description' => 'Get in touch with Green Resources. Fill out our contact form.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Contact Us',
                    'subtitle' => 'Fill Out Our Form',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => $normalizeSlug('contact-us-contacts'),
                'title' => 'Contacts',
                'meta_title' => 'Contacts – Green Resources',
                'meta_description' => 'Contact information for Green Resources offices and departments.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Contacts',
                    'subtitle' => 'Get in Touch',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
