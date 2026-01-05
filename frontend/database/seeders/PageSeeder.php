<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'home',
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
                'slug' => 'company-about-us',
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
                'slug' => 'company-location',
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
                'slug' => 'products-feedstocks',
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
                'slug' => 'products-methyl-ester',
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
                'slug' => 'products-others',
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
                'slug' => 'news-and-event-news',
                'title' => 'News',
                'meta_title' => 'News – Green Resources',
                'meta_description' => 'Stay updated with the latest news and updates from Green Resources.',
                'status' => 'published',
                'banner' => [
                    'title' => 'News',
                    'subtitle' => 'Latest Updates',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => 'news-and-event-event',
                'title' => 'Events',
                'meta_title' => 'Events – Green Resources',
                'meta_description' => 'Join us at our upcoming events and conferences.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Events',
                    'subtitle' => 'Upcoming Events',
                    'image' => 'assets/HEADER GREEN RESOURCES.png'
                ]
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'meta_title' => 'Contact Us – Green Resources',
                'meta_description' => 'Get in touch with Green Resources. We\'d love to hear from you.',
                'status' => 'published',
                'banner' => [
                    'title' => 'Contact Us',
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

