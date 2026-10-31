<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\HomepageCarousel;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminUser = User::updateOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => '12345678',
            'is_admin' => true,
        ]);

        $permissions = [
            'access admin panel',
            'view',
            'create',
            'edit',
            'delete',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage services',
            'manage team members',
            'manage blogs',
            'manage galleries',
            'manage contacts',
            'Web_Settings',
            'manage branches',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $branchAdminRole = Role::firstOrCreate(['name' => 'Branch Admin']);
        $productAdminRole = Role::firstOrCreate(['name' => 'Product Admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions([
            'access admin panel',
            'view',
            'create',
            'edit',
            'delete',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage services',
            'manage team members',
            'manage blogs',
            'manage galleries',
            'manage contacts',
            'Web_Settings',
        ]);
        $branchAdminRole->syncPermissions(['access admin panel', 'view', 'create', 'edit', 'manage branches']);
        $productAdminRole->syncPermissions(['access admin panel', 'view', 'create', 'edit', 'manage products']);
        $editorRole->syncPermissions(['access admin panel', 'view', 'create', 'edit']);
        $userRole->syncPermissions(['view']);

        $adminUser->assignRole($superAdminRole);

        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'SolarTech Services',
            'meta_title' => 'SolarTech Services - Renewable Energy Solutions',
            'meta_description' => 'SolarTech Services offers reliable solar installation, maintenance, and renewable solutions for homes and businesses.',
            'seo_keywords' => 'solar, renewable energy, solar service, solar panel',
            'hero_title' => 'Power Your Future with Solar Energy',
            'hero_subtitle' => 'Smart, efficient, and affordable solar systems for modern homes and industries.',
            'company_intro' => 'We help clients reduce electricity costs and carbon impact through complete solar engineering and service support.',
            'cta_title' => 'Start Saving Energy Today',
            'cta_text' => 'Request a free solar consultation from our experts and get a custom solution.',
            'cta_button_text' => 'Book Free Consultation',
            'cta_button_link' => '/contact',
            'about_title' => 'About SolarTech Services',
            'about_content' => 'SolarTech Services is a trusted renewable energy company focused on quality installations and long-term customer success.',
            'mission' => 'Deliver affordable and sustainable energy solutions with honest service.',
            'vision' => 'Become the leading clean energy partner in the region.',
            'history' => 'Started as a local engineering team, we have grown into a full-service solar solutions provider.',
            'contact_email' => 'info@solartechservices.com',
            'contact_phone' => '+880 1700-000000',
            'contact_address' => 'House 12, Road 5, Dhaka, Bangladesh',
            'google_map_embed' => 'https://maps.google.com/maps?q=Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed',
            'social_links' => [
                'facebook' => 'https://facebook.com',
                'linkedin' => 'https://linkedin.com',
                'youtube' => 'https://youtube.com',
            ],
        ]);

        About::updateOrCreate(['id' => 1], [
            'title' => 'About SolarTech Services',
            'page_details' => '<p>We design and deliver reliable renewable energy systems for homes and businesses with a focus on quality, safety, and long-term value.</p>',
            'details1' => '<p>Our team handles complete project execution from site survey and design to installation and maintenance.</p>',
            'details2' => '<p>We use quality components and engineering-backed planning to maximize energy yield and system life.</p>',
            'details3' => '<p>Every client receives tailored recommendations based on power consumption, roof structure, and budget.</p>',
            'details4' => '<p>Our support team stays connected after deployment to ensure smooth operations and faster issue resolution.</p>',
            'key_values' => ['Craftsmanship', 'Innovation', 'Sustainability', 'Customer-Centric'],
            'years_experience' => 12,
            'establishment_year' => 2014,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 1], [
            'title' => 'বাসা-বাড়িতে সিসিটিভি ক্যামেরা স্থাপন, মেরামত ও পাইকারি-খুচরা বিক্রয়',
            'subtitle' => 'বাসা-বাড়ি, দোকান ও প্রতিষ্ঠানে দক্ষ টেকনিশিয়ান দ্বারা সিসিটিভি ক্যামেরা ফিটিং, মোবাইল লাইভ ভিউ সেটআপ ও দ্রুত মেরামত সার্ভিস। এছাড়াও সকল সিসিটিভি মালামাল পাইকারি ও খুচরা বিক্রয় করা হয়।',
            'image' => 'hero-slider/cctv-slide.jpg',
            'is_active' => true,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 2], [
            'title' => 'সরকারি ও বেসরকারি চাকরির নির্ভুল অনলাইন আবেদন',
            'subtitle' => 'বিসিএস, প্রাথমিক শিক্ষক, ব্যাংক, রেলওয়ে ও মন্ত্রণালয়ের সকল চাকরির আবেদন, ছবি ও স্বাক্ষর সাইজিং এবং চালান ফি পেমেন্ট ঘরে বসেই সম্পন্ন করুন।',
            'image' => 'hero-slider/slide1.jpg',
            'is_active' => true,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 3], [
            'title' => 'ই-পাসপোর্ট, এনআইডি সংশোধন ও জন্মনিবন্ধন সেবা',
            'subtitle' => 'নতুন পাসপোর্ট আবেদন, রিনিউয়াল, পুলিশ ক্লিয়ারেন্স এবং জাতীয় পরিচয়পত্রের (NID) যেকোনো তথ্য সংশোধন ও অনলাইন কপি উত্তোলনে অভিজ্ঞ ও নির্ভরযোগ্য টিম।',
            'image' => 'hero-slider/slide2.jpg',
            'is_active' => true,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 4], [
            'title' => 'কলেজ-বিশ্ববিদ্যালয় ভর্তি আবেদন ও হাই-কোয়ালিটি প্রিন্টিং',
            'subtitle' => 'একাদশ শ্রেণি ও সকল বিশ্ববিদ্যালয়ে ভর্তি ফরম পূরণ, ডিজিটাল স্টুডিও ছবি, দ্রুতগতির কালার প্রিন্ট, ফটোকপি ও সরকারি সার্ভিস বুক প্রস্তুতকরণ সেবা।',
            'image' => 'hero-slider/slide3.jpg',
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 8; $i++) {
            Product::updateOrCreate(['slug' => 'solar-product-' . $i], [
                'title' => 'Solar Product ' . $i,
                'short_description' => 'High-efficiency solar product ' . $i . ' for reliable performance.',
                'description' => 'Detailed information about Solar Product ' . $i . '. This item is part of the reusable Laravel template demo data.',
                'price' => 1200 + ($i * 45),
                'is_featured' => $i <= 4,
                'status' => true,
                'sort_order' => $i,
            ]);

            Service::updateOrCreate(['slug' => 'solar-service-' . $i], [
                'title' => 'Solar Service ' . $i,
                'short_description' => 'Professional solar service ' . $i . ' tailored to client needs.',
                'description' => 'Detailed information about Solar Service ' . $i . '. Includes consultation, implementation, and maintenance support.',
                'is_featured' => $i <= 4,
                'status' => true,
                'sort_order' => $i,
            ]);
        }

        $this->call(BlogSeeder::class);

        for ($i = 1; $i <= 5; $i++) {

            TeamMember::updateOrCreate(['name' => 'Team Member ' . $i], [
                'designation' => 'Solar Engineer ' . $i,
                'bio' => 'Experienced in designing and deploying scalable solar systems.',
                'facebook_url' => 'https://facebook.com',
                'linkedin_url' => 'https://linkedin.com',
                'twitter_url' => 'https://twitter.com',
                'sort_order' => $i,
                'status' => true,
            ]);
        }

        for ($i = 1; $i <= 6; $i++) {
            Gallery::updateOrCreate(['title' => 'Project Photo ' . $i], [
                'type' => 'photo',
                'description' => 'Gallery photo item ' . $i,
                'status' => true,
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            Gallery::updateOrCreate(['title' => 'Project Video ' . $i], [
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Gallery video item ' . $i,
                'status' => true,
            ]);
        }
    }
}
