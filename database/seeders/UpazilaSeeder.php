<?php

namespace Database\Seeders;

use App\Models\Upazila;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UpazilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure storage directory exists
        $targetDir = storage_path('app/public/upazilas');
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        // Copy sample images from blogs if available for realistic test visuals
        $blogImages = [
            'blog1.jpg', 'blog2.jpg', 'blog3.jpg', 'blog4.jpg',
            'blog5.jpg', 'blog6.jpg', 'blog7.jpg'
        ];
        $sourceDir = storage_path('app/public/blogs');

        $sampleImages = [];
        $filesToCreate = [
            'info_banner.jpg' => 0,
            'info_1.jpg' => 1,
            'info_2.jpg' => 2,
            'info_3.jpg' => 3,
            'contact_banner.jpg' => 4,
            'contact_1.jpg' => 5,
            'contact_2.jpg' => 6,
            'contact_3.jpg' => 0,
            'places_banner.jpg' => 1,
            'places_1.jpg' => 2,
            'places_2.jpg' => 3,
            'places_3.jpg' => 4,
        ];

        foreach ($filesToCreate as $destName => $sourceIdx) {
            $destPath = $targetDir . DIRECTORY_SEPARATOR . $destName;
            $sourceFile = $sourceDir . DIRECTORY_SEPARATOR . ($blogImages[$sourceIdx] ?? 'blog1.jpg');
            if (File::exists($sourceFile) && !File::exists($destPath)) {
                File::copy($sourceFile, $destPath);
            }
        }

        $pages = [
            [
                'title' => 'উপজেলা সম্পর্কিত তথ্য (Upazila Informations)',
                'slug' => 'upazila-informations',
                'banner_image' => 'upazilas/info_banner.jpg',
                'image_1' => 'upazilas/info_1.jpg',
                'image_2' => 'upazilas/info_2.jpg',
                'image_3' => 'upazilas/info_3.jpg',
                'detail_1' => '<p class="lead"><strong>রামগতি উপজেলা</strong> বাংলাদেশের লক্ষ্মীপুর জেলার একটি ঐতিহ্যবাহী ও নদীমাতৃক উপকূলীয় উপজেলা। এটি মেঘনা নদীর অববাহিকায় ও বঙ্গোপসাগরের কোলঘেঁষে অবস্থিত।</p><p>উত্তরে কমলনগর উপজেলা, দক্ষিণে বঙ্গোপসাগর ও হাতিয়া চ্যানেল, পূর্বে নোয়াখালী জেলার সুবর্ণচর উপজেলা এবং পশ্চিমে মেঘনা নদী ও ভোলা জেলা অবস্থিত। উপজেলার মোট আয়তন প্রায় ৫৭৪.৭৩ বর্গ কিলোমিটার। উপজেলার প্রশাসনিক প্রাণকেন্দ্র আলেকজান্ডারে অবস্থিত। প্রকৃতির অপার নৈসর্গিক সৌন্দর্য, মেঘনা নদীর মনোরম সূর্যাস্ত এবং বিস্তৃত চরাঞ্চল রামগতিকে অনন্য রূপ দিয়েছে।</p>',
                'detail_2' => '<p><strong>ইতিহাস ও অর্থনীতি:</strong> রামগতি একটি প্রাচীন জনপদ। মেঘনা নদীর ভাঙা-গড়ার খেলায় বহুবার এই জনপদের ভূ-প্রকৃতি পরিবর্তিত হয়েছে। ১৯৭১ সালের মহান মুক্তিযুদ্ধে রামগতির মুক্তিকামী বীর মুক্তিযোদ্ধারা সম্মুখ সমরে সাহসী ভূমিকা পালন করেছিলেন।</p><p>রামগতির প্রধান অর্থনৈতিক চালিকাশক্তি হলো মেঘনার রূপালী ইলিশ আহরণ, উপকূলীয় কৃষি, মহিষের বাথান ও গবাদিপশু পালন। এখানকার মহিষের খাঁটি দুধের দই ও তাজা ইলিশ দেশজুড়ে অত্যন্ত সমাদৃত ও বিখ্যাত।</p>',
                'detail_3' => '<p><strong>প্রশাসনিক অবকাঠামো ও নাগরিক সুবিধা:</strong> রামগতি উপজেলায় ১টি পৌরসভা ও ৮টি ইউনিয়ন পরিষদ রয়েছে। ইউনিয়নগুলো হলো: চর আলেকজান্ডার, চর বাদাম, চর পোড়াগাছা, চর রমিজ, চর গাজী, বড়খেরী, বালুচর এবং চর আলগী।</p><ul><li><strong>উপজেলা সদর:</strong> আলেকজান্ডার, রামগতি</li><li><strong>পৌরসভা:</strong> রামগতি পৌরসভা</li><li><strong>ইউনিয়ন সংখ্যা:</strong> ৮টি</li><li><strong>ডাকঘর কোড:</strong> আলেকজান্ডার - ৩৭৩০, রামগতি - ৩৭৩১</li><li><strong>প্রধান অর্থনৈতিক ক্ষেত্র:</strong> ইলিশ মাছ আহরণ, সয়াবিন, ধান ও বাদাম চাষ</li></ul>',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'উপজেলা জরুরী যোগাযোগ (Upazila Contacts)',
                'slug' => 'upazila-contacts',
                'banner_image' => 'upazilas/contact_banner.jpg',
                'image_1' => 'upazilas/contact_1.jpg',
                'image_2' => 'upazilas/contact_2.jpg',
                'image_3' => 'upazilas/contact_3.jpg',
                'detail_1' => '<p class="lead">রামগতি উপজেলার নাগরিক সেবা, দাপ্তরিক কাজ ও প্রশাসনিক সহায়তার জন্য সরাসরি উপজেলা নির্বাহী অফিসারের কার্যালয় অথবা উপজেলা পরিষদ ভবনে যোগাযোগ করা যায়।</p><table class="table table-bordered"><thead><tr class="table-light"><th>দপ্তর / পদবী</th><th>যোগাযোগ নম্বর</th><th>অবস্থান</th></tr></thead><tbody><tr><td>উপজেলা নির্বাহী অফিসার (ইউএনও)</td><td>০১৭১১-৬৬৫০০০ / ০২৩৩৪-৭২০০০</td><td>উপজেলা কমপ্লেক্স, আলেকজান্ডার</td></tr><tr><td>উপজেলা পরিষদ চেয়ারম্যান কার্যালয়</td><td>০১৮১৯-৭৬৫০৯৮</td><td>উপজেলা পরিষদ ভবন</td></tr><tr><td>সহকারী কমিশনার (ভূমি) - এসি ল্যান্ড</td><td>০১৭১৩-০০০০০০</td><td>উপজেলা ভূমি অফিস</td></tr><tr><td>উপজেলা কৃষি অফিসার</td><td>০১৭২৭-০০০০০০</td><td>কৃষি সম্প্রসারণ অধিদপ্তর</td></tr></tbody></table>',
                'detail_2' => '<p><strong>আইনশৃঙ্খলা রক্ষা ও জরুরী পুলিশি সহায়তা:</strong> অপরাধ দমন এবং সার্বক্ষণিক নিরাপত্তার জন্য রামগতি থানা পুলিশ প্রস্তুত রয়েছে। জরুরী পরিস্থিতিতে সরাসরি কল করুন:</p><ul><li><strong>রামগতি থানা ডিউটি অফিসার:</strong> ০১৩২০-XXXXXX</li><li><strong>ভারপ্রাপ্ত কর্মকর্তা (ওসি):</strong> ০১৩২০-XXXXXX</li><li><strong>জাতীয় জরুরী সেবা হটলাইন:</strong> ৯৯৯ (টোল-ফ্রি পুলিশ, ফায়ার সার্ভিস ও অ্যাম্বুলেন্স)</li><li><strong>নারী ও শিশু নির্যাতন প্রতিরোধ সেল:</strong> ১০৯</li><li><strong>দুদক জাতীয় হটলাইন:</strong> ১০৬</li></ul>',
                'detail_3' => '<p><strong>চিকিৎসা সেবা, এ্যাম্বুলেন্স ও বিদ্যুৎ সহায়তা:</strong> জরুরী স্বাস্থ্যসেবা ও বিদ্যুৎ সংক্রান্ত অভিযোগের জন্য নিচের নম্বরে যোগাযোগ করুন:</p><table class="table table-bordered"><tbody><tr><td><strong>রামগতি উপজেলা স্বাস্থ্য কমপ্লেক্স:</strong></td><td>০২৩৩৪-XXXXXX, চর আলেকজান্ডার</td></tr><tr><td><strong>সরকারী জরুরী অ্যাম্বুলেন্স:</strong></td><td>০১৭১৪-XXXXXX</td></tr><tr><td><strong>রামগতি ফায়ার সার্ভিস স্টেশন:</strong></td><td>০২৩৩৪-XXXXXX / ০১৭XX-XXXXXX</td></tr><tr><td><strong>লক্ষ্মীপুর পল্লী বিদ্যুৎ (রামগতি অভিযোগ কেন্দ্র):</strong></td><td>০১৭৬৯-XXXXXX</td></tr></tbody></table>',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'উপজেলার দর্শনীয় স্থানসমূহ (Upazila Famous Places)',
                'slug' => 'upazila-famous-places',
                'banner_image' => 'upazilas/places_banner.jpg',
                'image_1' => 'upazilas/places_1.jpg',
                'image_2' => 'upazilas/places_2.jpg',
                'image_3' => 'upazilas/places_3.jpg',
                'detail_1' => '<p class="lead"><strong>আলেকজান্ডার মেঘনা নদী তীর ও বেড়িবাঁধ:</strong> রামগতি উপজেলার প্রধান পর্যটন আকর্ষণ হলো আলেকজান্ডারের মেঘনা নদী তীর সংরক্ষণ বাঁধ।</p><p>মেঘনার উত্তাল জলরাশি, বিস্তৃত বাঁধের উপর দিয়ে বয়ে যাওয়া নির্মল হাওয়া এবং মোহনার বুকে সূর্যাস্ত দেখতে প্রতিদিন শত শত পর্যটক এখানে আসেন। নদীর তীরে বসে তাজা ইলিশ ভাজা খাওয়া এবং চিলড্রেন পার্ক ও ওয়াকওয়েতে সময় কাটানো এখানকার জনপ্রিয় অভিজ্ঞতা।</p>',
                'detail_2' => '<p><strong>চর আলেকজান্ডার বাতিঘর ও মিনি কক্সবাজার:</strong> মেঘনা নদী ও বঙ্গোপসাগরের নৌযান চলাচলের জন্য স্থাপিত ঐতিহাসিক বাতিঘর এবং এর সংলগ্ন বিস্তীর্ণ চরাঞ্চল স্থানীয়দের কাছে "মিনি কক্সবাজার" নামে পরিচিত।</p><p>বিস্তীর্ণ বালুময় চর, কেওড়া বনের শ্যামল ছায়া এবং শীতকালে হাজার হাজার অতিথি পাখির কলকাকলিতে এটি মুখরিত থাকে। ফটোগ্রাফি ও ক্যাম্পিংয়ের জন্য এটি অত্যন্ত জনপ্রিয় স্পট।</p>',
                'detail_3' => '<p><strong>ঐতিহ্যবাহী রামদয়াল মৎস্য ঘাট ও চর আবদুল্লাহ:</strong> রামগতির রামদয়াল ও বড়খেরী মৎস্য অবতরণ কেন্দ্র হলো উপকূলীয় ইলিশ বাণিজ্যের প্রাণকেন্দ্র। শত শত মাছ ধরার ট্রলারের আসা-যাওয়া ও জেলেদের কর্মব্যস্ততা এক মনোমুগ্ধকর পরিবেশ সৃষ্টি করে।</p><p>এছাড়া বঙ্গোপসাগরের কোলঘেঁষে জেগে ওঠা দ্বীপ <strong>চর আবদুল্লাহ</strong> তে মহিষের সুবিশাল বাথান ও চরাঞ্চলের জীবনযাত্রা এক অনন্য অ্যাডভেঞ্চারমূলক অভিজ্ঞতা প্রদান করে।</p><ul><li><strong>ভ্রমণের সেরা সময়:</strong> অক্টোবর থেকে মার্চ মাস।</li><li><strong>যাতায়াত:</strong> লক্ষ্মীপুর জেলা সদর থেকে সরাসরি সিএনজি অথবা বাসে আলেকজান্ডার আসা যায়।</li></ul>',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Upazila::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}

