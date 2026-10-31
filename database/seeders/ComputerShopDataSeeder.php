<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Setting;
use App\Models\UsefulLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ComputerShopDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure permissions exist
        $permissions = [
            'manage orders',
            'manage useful links',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Assign all permissions to Admin role and admin users
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $adminUsers = User::where('is_admin', true)->get();
        foreach ($adminUsers as $admin) {
            $admin->syncRoles(['admin']);
            $admin->syncPermissions(Permission::all());
        }

        // 2. Update Settings with Shop identity from card & audio
        $setting = Setting::first();
        if (! $setting) {
            $setting = new Setting();
        }

        $setting->fill([
            'site_name' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার',
            'primary_color' => '#059669',
            'secondary_color' => '#334155',
            'accent_color' => '#dc2626',
            'text_color' => '#0f172a',
            'bg_color' => '#ffffff',
            'meta_title' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার - অনলাইন সেবা ও কম্পিউটার সেন্টার',
            'meta_description' => 'সরকারি ও বেসরকারি চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি সংশোধন, কলেজ ও বিশ্ববিদ্যালয়ে ভর্তি আবেদন, ফটোকপি ও ছবি প্রিন্ট। ঘরে বসেই অর্ডার করুন এবং কাজের অগ্রগতি ট্র্যাক করুন।',
            'seo_keywords' => 'চাকরির আবেদন, জন্মনিবন্ধন আবেদন, পাসপোর্ট আবেদন, এনআইডি সংশোধন, ভর্তি আবেদন, সাইবার ক্যাফে, রামগতি, লক্ষ্মীপুর',
            'hero_title' => 'প্রয়োজনীয় সকল অনলাইন আবেদন ও কম্পিউটার সেবা এক ঠিকানায়',
            'hero_subtitle' => 'চাকরি, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি ও ভর্তি আবেদন ঘরে বসেই অনলাইনে অর্ডার দিন এবং রিয়েল-টাইমে কাজের অগ্রগতি ট্র্যাক করুন।',
            'company_intro' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার লক্ষ্মীপুর জেলার রামগতি উপজেলার আলেকজান্ডারে বিশ্বস্ততার সাথে সকল প্রকার অনলাইন সেবা, কম্পোজ, ফটোকপি ও আবেদন সেবা প্রদান করে আসছে।',
            'cta_title' => 'কোনো আবেদনের সাহায্য প্রয়োজন?',
            'cta_text' => 'আমাদের দোকানে সরাসরি আসুন অথবা অনলাইনেই প্রয়োজনীয় তথ্য ও ফাইল পাঠিয়ে নিশ্চিন্তে আবেদন সম্পন্ন করুন।',
            'cta_button_text' => 'এখনই অর্ডার করুন',
            'cta_button_link' => '/orders/create',
            'about_title' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার সম্পর্কে',
            'about_content' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার হলো ডিজিটাল সেবা ও অনলাইন অ্যাপ্লিকেশনের এক নির্ভরযোগ্য কেন্দ্র। আমাদের অভিজ্ঞ টিম অত্যন্ত সতর্কতা ও নির্ভুলতার সাথে সরকারি চাকরি, পাসপোর্ট, এনআইডি, জন্ম নিবন্ধন এবং কলেজ-বিশ্ববিদ্যালয় ভর্তির আবেদন করে থাকে।',
            'mission' => 'সকল নাগরিক ও ছাত্র-ছাত্রীদের কাছে নির্ভুল ও দ্রুততম ডিজিটাল সেবা পৌঁছে দেওয়া।',
            'vision' => 'অঞ্চলের শীর্ষস্থানীয় ও আধুনিক প্রযুক্তিনির্ভর ওয়ান-স্টপ কম্পিউটার সেবা কেন্দ্র হিসেবে গড়ে তোলা।',
            'history' => 'রামগতির প্রাণকেন্দ্র আলেকজান্ডার জনতা বাজারে দীর্ঘদিনের অভিজ্ঞতা নিয়ে আমরা সততা ও সুনামের সাথে সেবা দিয়ে আসছি।',
            'contact_email' => 'abrahman9196@gmail.com',
            'contact_phone' => '01711-665098, 01717-074428',
            'contact_address' => 'জনতা বাজার, মেইন রোড, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর।',
            'google_map_embed' => 'https://maps.google.com/maps?q=Alexander,Ramgati,Lakshmipur&t=&z=14&ie=UTF8&iwloc=&output=embed',
            'bkash_number' => '01711-665098 (Personal - Send Money)',
            'nagad_number' => '01717-074428 (Personal - Send Money)',
            'rocket_number' => '01711-665098-9 (Personal)',
            'payment_instructions' => "১. আপনার বিকাশ বা নগদ অ্যাপ থেকে উপরোক্ত নম্বরে 'Send Money' করুন।\n২. আবেদনের নির্ধারিত ফি পরিমাণ টাকা পাঠান।\n৩. লেনদেন সফল হলে Transaction ID (TrxID) এবং যে নম্বর থেকে পাঠিয়েছেন তা নিচের ফর্মে লিখে অর্ডার কনফার্ম করুন।",
            'emergency_notice' => 'অনলাইনে যেকোনো চাকরির আবেদন বা ভর্তি আবেদনের জন্য শেষ সময়ের অপেক্ষা না করে আজই অর্ডার করুন!',
        ]);
        $setting->save();

        // 3. Populate Services
        $services = [
            [
                'title' => 'সরকারি ও বেসরকারি চাকরির আবেদন',
                'slug' => 'job-application-online',
                'short_description' => 'বিসিএস, প্রাইমারি শিক্ষক, ব্যাংক, মন্ত্রণালয় ও সকল সরকারি-বেসরকারি প্রতিষ্ঠানের নির্ভুল অনলাইন চাকরির আবেদন ও এডমিট কার্ড ডাউনলোড।',
                'description' => '<p>আমাদের দক্ষ ও অভিজ্ঞ অপারেটর দ্বারা সকল প্রকার সরকারি চাকরির (Teletalk, BPSC, বিভিন্ন মন্ত্রণালয় ও অধিদপ্তর) এবং বেসরকারি প্রতিষ্ঠানের চাকরির আবেদন সম্পন্ন করা হয়। ভুলহীন কোটা সিলেকশন, সঠিক সাইজের ছবি ও স্বাক্ষর আপলোড এবং তাৎক্ষণিক আবেদন কপি প্রদান করা হয়।</p><h5>সেবার সুবিধাসমূহ:</h5><ul><li>সঠিক ক্যাটাগরি ও কোটা নির্বাচন</li><li>পাসপোর্ট সাইজ ছবি ও সাক্ষর নির্ধারিত রেজুলেশনে রিসাইজ</li><li>টেলিটক ফি প্রদান বা পেমেন্ট সহায়তা</li><li>আবেদন কপি ও প্রবেশপত্র সরাসরি সাইট থেকে ডাউনলোড সুবিধা</li></ul>',
                'service_fee' => 150.00,
                'govt_fee' => 0.00,
                'delivery_time' => '২ - ৬ ঘণ্টা',
                'required_documents' => "১. পাসপোর্ট সাইজের সদ্য তোলা ছবি\n২. সাদা কাগজে করা পরিষ্কার স্বাক্ষর\n৩. শিক্ষাগত যোগ্যতার সনদ/তথ্য (রোল, রেজিস্ট্রেশন, জিপিএ/বিভাগ)\n৪. জাতীয় পরিচয়পত্র (NID) বা জন্মনিবন্ধন নম্বর\n৫. সার্কুলার বা পদের নাম",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-briefcase-fill',
                'sort_order' => 1,
            ],
            [
                'title' => 'ই-পাসপোর্ট নতুন আবেদন ও রি-ইস্যু',
                'slug' => 'e-passport-application',
                'short_description' => 'ই-পাসপোর্টের নতুন আবেদন, তথ্য সংশোধন, মেয়াদোত্তীর্ণ পাসপোর্ট রি-ইস্যু ও পুলিশ ক্লিয়ারেন্স আবেদন।',
                'description' => '<p>ই-পাসপোর্ট করার ক্ষেত্রে তথ্য মিল রাখা অত্যন্ত সংবেদনশীল কাজ। পিতা-মাতার নাম, স্থায়ী ঠিকানা ও জরুরি যোগাযোগের তথ্য বাংলাদেশ পাসপোর্ট অধিদপ্তরের সর্বশেষ নিয়ম অনুযায়ী নিখুঁতভাবে পূরণ করা হয়।</p><h5>আমরা যা করি:</h5><ul><li>ই-পাসপোর্ট ফরম নির্ভুলভাবে এন্ট্রি</li><li>পাসপোর্ট ফি ব্যাংকে চালানের সঠিক নির্দেশনা</li><li>অ্যাপয়েন্টমেন্ট শিডিউল নির্ধারণ</li><li>পুলিশ ক্লিয়ারেন্স সার্টিফিকেট আবেদন</li></ul>',
                'service_fee' => 300.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১ কর্মদিবস',
                'required_documents' => "১. জাতীয় পরিচয়পত্র (NID) অথবা অনলাইন জন্ম নিবন্ধন (স্মার্ট জন্মনিবন্ধন)\n২. পূর্ববর্তী পাসপোর্ট (যদি থাকে - রি-ইস্যুর ক্ষেত্রে)\n৩. পিতা-মাতার NID নম্বর ও পেশা\n৪. বর্তমান ও স্থায়ী ঠিকানা",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-passport-fill',
                'sort_order' => 2,
            ],
            [
                'title' => 'অনলাইন জন্ম নিবন্ধন আবেদন ও তথ্য সংশোধন',
                'slug' => 'birth-registration-correction',
                'short_description' => 'নতুন ডিজিটাল জন্ম নিবন্ধন আবেদন, পিতা-মাতার নাম সংশোধন, বয়স বা বানান সংশোধন ও ইংরেজি ভার্সন আবেদন।',
                'description' => '<p>ইউনিয়ন পরিষদ ও পৌরসভা এলাকার সকল নতুন জন্ম নিবন্ধন আবেদন ও জটিল ভুল সংশোধন আমাদের মাধ্যমে খুব সহজেই সম্পন্ন করতে পারবেন।</p>',
                'service_fee' => 200.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১-২ কর্মদিবস',
                'required_documents' => "১. পূর্বের জন্মনিবন্ধন নম্বর ও জন্ম তারিখ\n২. পিতা ও মাতার অনলাইন জন্মনিবন্ধন বা NID\n৩. টিকার কার্ড / ইপিআই কার্ড (শিশুদের ক্ষেত্রে)\n৪. শিক্ষাগত যোগ্যতার সনদ (PSC/JSC/SSC)\n৫. হোল্ডিং ট্যাক্স বা বিদ্যুৎ বিলের কপি",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-file-earmark-person-fill',
                'sort_order' => 3,
            ],
            [
                'title' => 'জাতীয় পরিচয়পত্র (NID) আবেদন, সংশোধন ও রি-প্রিন্ট',
                'slug' => 'nid-card-services',
                'short_description' => 'নতুন ভোটার আবেদন, NID কার্ডে নাম-পিতার নাম-বয়স সংশোধন, হারানো NID রিপ্রিন্ট ও স্মার্ট কার্ড স্ট্যাটাস।',
                'description' => '<p>বাংলাদেশ নির্বাচন কমিশনের নিয়ম অনুযায়ী নির্ভুল প্রমাণপত্র সংযুক্ত করে NID সংশোধন আবেদন এবং হারিয়ে যাওয়া আইডি কার্ডের অনলাইন কপি তাৎক্ষণিক উত্তোলন।</p>',
                'service_fee' => 250.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১ কর্মদিবস',
                'required_documents' => "১. NID নম্বর ও জন্ম তারিখ\n২. যে তথ্য সংশোধন করতে চান তার স্বপক্ষে ডকুমেন্ট (SSC সনদ, জন্মনিবন্ধন, পাসপোর্ট, বিয়ের কাবিননামা ইত্যাদি)\n৩. হারিয়ে গেলে জিডি (GD) এর কপি",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-person-badge-fill',
                'sort_order' => 4,
            ],
            [
                'title' => 'একাদশ শ্রেণি ও বিশ্ববিদ্যালয়ে অনলাইন ভর্তি আবেদন',
                'slug' => 'college-university-admission',
                'short_description' => 'XI Class Admission, জাতীয় বিশ্ববিদ্যালয় (NU), ঢাকা বিশ্ববিদ্যালয় অধিভুক্ত ৭ কলেজ ও সকল পাবলিক বিশ্ববিদ্যালয়ে ভর্তি আবেদন।',
                'description' => '<p>এসএসসি ও এইচএসসি উত্তীর্ণ শিক্ষার্থীদের কলেজ চয়েস ও বিশ্ববিদ্যালয় ভর্তি আবেদন সাবধানতার সাথে পছন্দের ক্রমানুসারে পূরণ করা হয়।</p>',
                'service_fee' => 150.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১-৩ ঘণ্টা',
                'required_documents' => "১. এসএসসি / এইচএসসি রোল নম্বর ও রেজিস্ট্রেশন নম্বর\n২. পাসের সাল ও শিক্ষা বোর্ড\n৩. পাসপোর্ট সাইজ ছবি\n৪. পছন্দের কলেজ/বিশ্ববিদ্যালয়ের তালিকা ও বিষয়ক্রম",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-mortarboard-fill',
                'sort_order' => 5,
            ],
            [
                'title' => 'সার্ভিস বুক, কম্পিউটার কম্পোজ ও টাইপিং',
                'slug' => 'service-book-computer-compose',
                'short_description' => 'সরকারি ও বেসরকারি কর্মকর্তা-কর্মচারীদের সার্ভিস বুক প্রস্তুতকরণ, বাংলা ও ইংরেজি দ্রুত নির্ভুল কম্পিউটার কম্পোজ।',
                'description' => '<p>যেকোনো চুক্তিপত্র, এফিডেভিট, দলিল, দরখাস্ত, জীবনবৃত্তান্ত (CV / Resume), প্রাতিষ্ঠানিক প্রশ্নপত্র ও সার্ভিস বুক যত্নসহকারে টাইপ ও ফরম্যাটিং করা হয়।</p>',
                'service_fee' => 100.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১ কর্মদিবস',
                'required_documents' => "১. হাতে লেখা ড্রাফট বা প্রয়োজনীয় তথ্যাবলী\n২. পূর্ববর্তী ডকুমেন্টের কপি (প্রযোজ্য ক্ষেত্রে)",
                'is_online_orderable' => true,
                'is_featured' => false,
                'icon' => 'bi-journal-bookmark-fill',
                'sort_order' => 6,
            ],
            [
                'title' => 'ডিজিটাল ছবি তোলা, ল্যাব প্রিন্ট ও এডিটিং',
                'slug' => 'digital-photography-lab-print',
                'short_description' => 'তাৎক্ষণিক পাসপোর্ট সাইজ ছবি তোলা, ব্যাকগ্রাউন্ড চেঞ্জ, স্টুডিও ল্যাব কোয়ালিটি কালার প্রিন্ট ও লেমিনেশন।',
                'description' => '<p>ভিসা, পাসপোর্ট, চাকরি ও বিভিন্ন পরীক্ষার নির্ধারিত সাইজ ও ব্যাকগ্রাউন্ড কালার (সাদা, নীল, ধূসর) বজায় রেখে প্রফেশনাল ফটো প্রিন্ট সেবা।</p>',
                'service_fee' => 80.00,
                'govt_fee' => 0.00,
                'delivery_time' => 'তাৎক্ষণিক / ১ ঘণ্টা',
                'required_documents' => "১. গ্রাহকের ছবি (অনলাইনে হলে স্পষ্ট মুখের ছবি আপলোড করবেন)",
                'is_online_orderable' => true,
                'is_featured' => false,
                'icon' => 'bi-camera-fill',
                'sort_order' => 7,
            ],
            [
                'title' => 'ফটোকপি, কালার প্রিন্টিং ও ডকুমেন্ট স্ক্যানিং',
                'slug' => 'photocopy-color-printing-scanning',
                'short_description' => 'হাই-স্পিড ডিজিটাল ফটোকপি, যেকোনো সাইজের কালার প্রিন্ট, বুক বাইন্ডিং ও হাই-রেজুলেশন স্ক্যানিং।',
                'description' => '<p>স্পষ্ট ও পরিষ্কার কালার ও ব্ল্যাক অ্যান্ড হোয়াইট প্রিন্ট। যেকোনো জরুরি ডকুমেন্ট উচ্চ রেজুলেশনে স্ক্যান করে ইমেইল বা হোয়াটসঅ্যাপে পাঠানোর ব্যবস্থা।</p>',
                'service_fee' => 50.00,
                'govt_fee' => 0.00,
                'delivery_time' => 'তাৎক্ষণিক',
                'required_documents' => "১. মূল ডকুমেন্ট বা সফট কপি (PDF/Doc/JPG)",
                'is_online_orderable' => false,
                'is_featured' => false,
                'icon' => 'bi-printer-fill',
                'sort_order' => 8,
            ],
            [
                'title' => 'ড্রাইভিং লাইসেন্স ও বিআরটিএ সেবা',
                'slug' => 'brta-driving-license-services',
                'short_description' => 'বিআরটিএ সার্ভিস পোর্টালে (BSP) লার্নার ড্রাইভিং লাইসেন্স আবেদন, ফি পেমেন্ট ও স্মার্ট কার্ড ট্র্যাকিং।',
                'description' => '<p>মোটরসাইকেল ও হালকা যানবাহনের লার্নার লাইসেন্স ঘরে বসেই আবেদন করুন। বিআরটিএ সরকারি ফি প্রদানসহ সকল কাজ সম্পন্ন করা হয়।</p>',
                'service_fee' => 250.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১ কর্মদিবস',
                'required_documents' => "১. জাতীয় পরিচয়পত্র (NID)\n২. রেজিস্টার্ড ডাক্তারের মেডিকেল সার্টিফিকেট\n৩. শিক্ষাগত যোগ্যতার সনদ (কমপক্ষে ৮ম শ্রেণি)\n৪. ইউটিলিটি বিলের কপি",
                'is_online_orderable' => true,
                'is_featured' => false,
                'icon' => 'bi-car-front-fill',
                'sort_order' => 9,
            ],
            [
                'title' => 'বাসা-বাড়িতে সিসিটিভি ক্যামেরা স্থাপন, মেরামত ও পাইকারি-খুচরা বিক্রয়',
                'slug' => 'cctv-camera-home-service-and-sales',
                'short_description' => 'বাসা-বাড়ি, দোকান ও প্রতিষ্ঠানে গিয়ে সিসিটিভি ক্যামেরা স্থাপন ও মেরামতের কাজ করা হয় এবং সকল সিসিটিভি মালামাল পাইকারি ও খুচরা বিক্রয় করা হয়।',
                'description' => '<p>আমরা অত্যন্ত দক্ষতার সাথে বাসা-বাড়ি, দোকান, ব্যবসা প্রতিষ্ঠান, অফিস ও প্রতিষ্ঠানে সরাসরি গিয়ে নতুন সিসিটিভি ক্যামেরা ইনস্টলেশন (Wiring & Setup), পুরাতন ক্যামেরা মেরামত ও সার্ভিসিং করে থাকি।</p><p>এছাড়াও নামিদামি ব্র্যান্ডের এইচডি ও আইপি সিসিটিভি ক্যামেরা, ডিভিআর, এনভিআর, হার্ডডিস্ক, পাওয়ার সাপ্লাই ও ক্যাবলসহ সকল ধরনের সিসিটিভি মালামাল পাইকারি ও খুচরা মূল্যে বিক্রয় করা হয়।</p>',
                'service_fee' => 500.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১-২ কর্মদিবস (হোম সার্ভিস)',
                'required_documents' => "১. বাসা/দোকানের পূর্ণ ঠিকানা ও লোকেশন\n২. ক্যামেরার সংখ্যা ও কাজের বিবরণ (নতুন ক্যামেরা ইনস্টলেশন / মেরামত / মালামাল ক্রয়)",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-camera-video',
                'sort_order' => 10,
            ],
        ];

        foreach ($services as $srvData) {
            Service::updateOrCreate(
                ['slug' => $srvData['slug']],
                $srvData
            );
        }

        // 4. Populate Useful Links
        $jobService = Service::where('slug', 'job-application-online')->first();
        $passportService = Service::where('slug', 'e-passport-application')->first();
        $birthService = Service::where('slug', 'birth-registration-correction')->first();
        $nidService = Service::where('slug', 'nid-card-services')->first();
        $admissionService = Service::where('slug', 'college-university-admission')->first();
        $brtaService = Service::where('slug', 'brta-driving-license-services')->first();

        $usefulLinks = [
            [
                'title' => 'টেলিটক সরকারি চাকরির আবেদন পোর্টাল (All Jobs)',
                'slug' => 'teletalk-all-jobs-portal',
                'category' => 'চাকরির আবেদন',
                'short_description' => 'বাংলাদেশের সকল মন্ত্রণালয়, অধিদপ্তর, স্বায়ত্তশাসিত প্রতিষ্ঠান ও শিক্ষা প্রতিষ্ঠানের টেলিটক অনলাইন চাকরির আবেদন লিংক।',
                'long_description' => "টেলিটকের মাধ্যমে পরিচালিত সকল সরকারি চাকরির আবেদন এই পোর্টাল থেকে করা যায়। আবেদন করতে প্রার্থীর ৩০০x৩০০ পিক্সেল রঙিন ছবি ও ৩০০x৮০ পিক্সেল স্ক্যান করা স্বাক্ষর প্রয়োজন হয়। আবেদন সাবমিটের পর প্রাপ্ত User ID দিয়ে ৭২ ঘণ্টার মধ্যে টেলিটক প্রিপেইড সিমের মাধ্যমে এসএমএস করে নির্ধারিত সরকারি ফি জমা দিতে হয়।\n\nআপনি চাইলে নিজে আবেদন করতে পারেন অথবা সকল তথ্য ও ছবি দিয়ে আমাদের মাধ্যমে শতভাগ নির্ভুলভাবে আবেদন ও পেমেন্ট সম্পন্ন করতে পারেন।",
                'required_documents' => "• পাসপোর্ট সাইজ ছবি (৩০০x৩০০ px)\n• স্বাক্ষর (৩০০x৮০ px)\n• শিক্ষাগত যোগ্যতা (JSC/SSC/HSC/Degree/Masters রোল, রেজিস্ট্রেশন ও জিপিএ)\n• NID অথবা অনলাইন জন্মনিবন্ধন",
                'external_url' => 'https://alljobs.teletalk.com.bd',
                'service_id' => $jobService?->id,
                'icon' => 'bi-briefcase',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'অনলাইন জন্ম ও মৃত্যু নিবন্ধন পোর্টাল (BDPRIS)',
                'slug' => 'online-birth-death-registration-bdpris',
                'category' => 'এনআইডি ও জন্মনিবন্ধন',
                'short_description' => 'নতুন জন্ম নিবন্ধনের জন্য অনলাইনে আবেদন, আবেদনপত্রের বর্তমান অবস্থা যাচাই এবং সংশোধন আবেদন।',
                'long_description' => "স্থানীয় সরকার বিভাগের নতুন ডিজিটাল জন্ম নিবন্ধন পোর্টালে শিশু বা বয়স্কদের জন্ম নিবন্ধন আবেদন সরাসরি দাখিল করা যায়। আবেদনের পর সংশ্লিষ্ট ইউনিয়ন পরিষদ বা পৌরসভা কার্যালয়ে নির্দিষ্ট কাগজপত্র ও চিকিৎসকের প্রত্যয়ন বা টিকার কার্ড জমা দিতে হয়।\n\nতথ্য সংশোধনের ক্ষেত্রে পূর্বের সনদের ভুল এবং সংশোধিত তথ্যের সপক্ষে প্রয়োজনীয় প্রামাণিক নথি সংযুক্ত করতে হয়।",
                'required_documents' => "• পিতামাতার অনলাইন জন্মনিবন্ধন বা NID\n• শিশুর ক্ষেত্রে ইপিআই (টিকা) কার্ড\n• বয়স প্রমাণের জন্য মেডিকেল বা শিক্ষাগত সনদ\n• বর্তমান ঠিকানার বিদ্যুৎ বিল বা হোল্ডিং ট্যাক্স রসিদ",
                'external_url' => 'https://bdpris.gov.bd',
                'service_id' => $birthService?->id,
                'icon' => 'bi-file-earmark-person',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'ই-পাসপোর্ট অনলাইন রেজিস্ট্রেশন পোর্টাল',
                'slug' => 'bangladesh-epassport-portal',
                'category' => 'পাসপোর্ট ও ভিসা',
                'short_description' => 'বাংলাদেশ ই-পাসপোর্টের জন্য অনলাইন ফরম পূরণ, ফি হিসাব এবং আঞ্চলিক পাসপোর্ট অফিসে বায়োমেট্রিক স্লট বুকিং।',
                'long_description' => "ই-পাসপোর্ট আবেদনের জন্য প্রথমে এই পোর্টালে ব্যক্তিগত অ্যাকাউন্ট খুলে ধাপ অনুযায়ী সকল তথ্য প্রদান করতে হয়। ৪৮ পাতা বা ৬৪ পাতা এবং ৫ বছর বা ১০ বছর মেয়াদের ভিত্তিতে সরকারি ফি ব্যাংকে বা অনলাইনে প্রদান করা যায়। আবেদন সাবমিট করার পর অ্যাপ্লিকেশন সামারি ও পেমেন্ট স্লিপ প্রিন্ট করে পাসপোর্ট অফিসে যেতে হয়।",
                'required_documents' => "• জাতীয় পরিচয়পত্র (NID) অথবা অনলাইন জন্মনিবন্ধন\n• পুরাতন পাসপোর্ট (রি-ইস্যু হলে)\n• পেশাগত সনদ (প্রযোজ্য ক্ষেত্রে সরকারি জিও/এনওসি)\n• পিতা-মাতার NID নম্বর",
                'external_url' => 'https://www.epassport.gov.bd',
                'service_id' => $passportService?->id,
                'icon' => 'bi-passport',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'বাংলাদেশ নির্বাচন কমিশন NID সিটিজেন সার্ভিসেস',
                'slug' => 'bangladesh-nid-service-portal',
                'category' => 'এনআইডি ও জন্মনিবন্ধন',
                'short_description' => 'ভোটার আইডি কার্ড ডাউনলোড, তথ্য ও ছবি সংশোধন, হারানো কার্ড রিপ্রিন্ট এবং নতুন ভোটার হওয়ার আবেদন।',
                'long_description' => "নির্বাচন কমিশনের সেবা পোর্টাল থেকে সরাসরি NID কার্ডের অনলাইন কালার কপি ডাউনলোড করা যায়। আপনার নামের বানান, পিতার নাম, জন্ম তারিখ সংশোধন বা বৈবাহিক অবস্থা পরিবর্তনের জন্য অনলাইন ফি দিয়ে প্রয়োজনীয় কাগজপত্র আপলোড করে আবেদন করতে পারেন।",
                'required_documents' => "• NID নম্বর ও জন্ম তারিখ\n• রেজিস্টার্ড মোবাইল নম্বর (ওটিপির জন্য)\n• ফেস ভেরিফিকেশন (NID Wallet App)\n• সংশোধনের সপক্ষে এসএসসি সনদ/জন্মনিবন্ধন",
                'external_url' => 'https://services.nidw.gov.bd',
                'service_id' => $nidService?->id,
                'icon' => 'bi-person-badge',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'একাদশ শ্রেণিতে অনলাইন ভর্তি পোর্টাল (XI Class Admission)',
                'slug' => 'xi-class-admission-system',
                'category' => 'ভর্তি ও শিক্ষা',
                'short_description' => 'এসএসসি উত্তীর্ণ শিক্ষার্থীদের জন্য দেশের সকল সরকারি ও বেসরকারি কলেজে একাদশ শ্রেণিতে ভর্তির কেন্দ্রীয় আবেদন।',
                'long_description' => "মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ডের অধীনে সারাদেশে একাদশ শ্রেণিতে ভর্তির আবেদন এই পোর্টালে সম্পন্ন হয়। একজন শিক্ষার্থী সর্বনিম্ন ৫টি এবং সর্বোচ্চ ১০টি কলেজ পছন্দক্রম অনুযায়ী নির্বাচন করতে পারে। সরকারি আবেদন ফি বিকাশ বা নগদের মাধ্যমে জমা দিয়ে আবেদন সম্পন্ন করা যায়।",
                'required_documents' => "• SSC রোল, রেজিস্ট্রেশন নম্বর ও বোর্ড\n• পাসের সাল\n• যোগাযোগের জন্য সক্রিয় মোবাইল নম্বর\n• পছন্দকৃত কলেজের তালিকা ও শিফট/ভার্সন",
                'external_url' => 'http://xiclassadmission.gov.bd',
                'service_id' => $admissionService?->id,
                'icon' => 'bi-mortarboard',
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'জাতীয় বিশ্ববিদ্যালয় ভর্তি পোর্টাল (NU Admission)',
                'slug' => 'national-university-admission-portal',
                'category' => 'ভর্তি ও শিক্ষা',
                'short_description' => 'জাতীয় বিশ্ববিদ্যালয়ের অধীনে অনার্স, ডিগ্রি পাস, প্রফেশনাল ও মাস্টার্স কোর্সে অনলাইনে প্রাথমিক আবেদন।',
                'long_description' => "অনার্স প্রথম বর্ষ ও ডিগ্রি প্রথম বর্ষে ভর্তির জন্য অনলাইনে আবেদন ফরম পূরণ করে প্রিন্ট কপি ও আবেদন ফি সংশ্লিষ্ট কলেজে জমা দিতে হয়। পরবর্তীতে মেধাতালিকা অনুযায়ী বিষয় বরাদ্দ দেওয়া হয়।",
                'required_documents' => "• SSC ও HSC রোল এবং রেজিস্ট্রেশন নম্বর\n• রঙিন ছবি (১৫০x১২০ px)\n• মোবাইল নম্বর ও ইমেইল",
                'external_url' => 'http://app1.nu.edu.bd',
                'service_id' => $admissionService?->id,
                'icon' => 'bi-building-fill-check',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'title' => 'বিআরটিএ সার্ভিস পোর্টাল (BRTA Service Portal - BSP)',
                'slug' => 'brta-service-portal-bsp',
                'category' => 'সরকারি ও অন্যান্য',
                'short_description' => 'শিক্ষানবিস (লার্নার) ড্রাইভিং লাইসেন্স, স্মার্ট কার্ড লাইসেন্স আবেদন ও গাড়ির ফিটনেস/ট্যাক্স টোকেন নবায়ন।',
                'long_description' => "বিআরটিএ সার্ভিস পোর্টালে অ্যাকাউন্ট খুলে ঘরে বসেই অনলাইনে লার্নার ড্রাইভিং লাইসেন্স প্রিন্ট করা যায়। নির্দিষ্ট তারিখে ফিল্ড টেস্ট পরীক্ষায় উত্তীর্ণ হলে মূল স্মার্ট ড্রাইভিং লাইসেন্সের আবেদন করা সম্ভব।",
                'required_documents' => "• জাতীয় পরিচয়পত্র ও ছবি\n• রেজিস্টার্ড ডাক্তারের স্বাক্ষরিত মেডিকেল সার্টিফিকেট\n• শিক্ষাগত যোগ্যতার সনদ\n• ইউটিলিটি বিলের কপি",
                'external_url' => 'https://bsp.brta.gov.bd',
                'service_id' => $brtaService?->id,
                'icon' => 'bi-car-front',
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'title' => 'শিক্ষা বোর্ড পরীক্ষার ফলাফল ও মার্কশীট (EBoardResults)',
                'slug' => 'eboard-results-marksheet-portal',
                'category' => 'ভর্তি ও শিক্ষা',
                'short_description' => 'জেএসসি, এসএসসি, এইচএসসি ও সমমান পরীক্ষার পূর্ণাঙ্গ নম্বরপত্র ও বিস্তারিত ফলাফল ডাউনলোডের ওয়েবসাইট।',
                'long_description' => "সকল সাধারণ শিক্ষা বোর্ড, মাদ্রাসা বোর্ড ও কারিগরি বোর্ডের পরীক্ষার বিস্তারিত ফলাফল ও নম্বরপত্র (ইনডিভিজুয়াল ও ইনস্টিটিউশনাল) এই সার্ভার থেকে সবচেয়ে দ্রুত পাওয়া যায়।",
                'required_documents' => "• পরীক্ষার নাম ও সাল\n• শিক্ষা বোর্ড\n• রোল নম্বর ও রেজিস্ট্রেশন নম্বর",
                'external_url' => 'https://eboardresults.com',
                'service_id' => null,
                'icon' => 'bi-award',
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'title' => 'বাংলাদেশ জাতীয় ফরম পোর্টাল (Forms.gov.bd)',
                'slug' => 'bangladesh-forms-portal',
                'category' => 'সরকারি ও অন্যান্য',
                'short_description' => 'বাংলাদেশ সরকারের সকল মন্ত্রণালয়, বিভাগ ও অধিদপ্তরের প্রয়োজনীয় সকল প্রকার আবেদন ফরম ও পিডিএফ ডাউনলোড।',
                'long_description' => "পেনশন ফরম, পাসপোর্ট ফরম, ট্রেড লাইসেন্স, জমির নামজারি, ওয়ারিশান সনদ, পুলিশ ভেরিফিকেশনসহ সরকারের সব রকমের অফিশিয়াল ফর্মের নির্ভরযোগ্য সংগ্রহশালা।",
                'required_documents' => "• নির্দিষ্ট ফরম অনুযায়ী প্রযোজ্য তথ্যাদি",
                'external_url' => 'https://forms.gov.bd',
                'service_id' => null,
                'icon' => 'bi-file-text',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'title' => 'বাসা-বাড়িতে সিসিটিভি ক্যামেরা স্থাপন, মেরামত ও পাইকারি-খুচরা বিক্রয়',
                'slug' => 'cctv-camera-home-service-and-sales',
                'short_description' => 'বাসা-বাড়ি, দোকান ও প্রতিষ্ঠানে গিয়ে সিসিটিভি ক্যামেরা স্থাপন ও মেরামতের কাজ করা হয় এবং সকল সিসিটিভি মালামাল পাইকারি ও খুচরা বিক্রয় করা হয়।',
                'description' => '<p>আমরা অত্যন্ত দক্ষতার সাথে বাসা-বাড়ি, দোকান, ব্যবসা প্রতিষ্ঠান, অফিস ও প্রতিষ্ঠানে সরাসরি গিয়ে নতুন সিসিটিভি ক্যামেরা ইনস্টলেশন (Wiring & Setup), পুরাতন ক্যামেরা মেরামত ও সার্ভিসিং করে থাকি।</p><p>এছাড়াও নামিদামি ব্র্যান্ডের এইচডি ও আইপি সিসিটিভি ক্যামেরা, ডিভিআর, এনভিআর, হার্ডডিস্ক, পাওয়ার সাপ্লাই ও ক্যাবলসহ সকল ধরনের সিসিটিভি মালামাল পাইকারি ও খুচরা মূল্যে বিক্রয় করা হয়।</p>',
                'service_fee' => 500.00,
                'govt_fee' => 0.00,
                'delivery_time' => '১-২ কর্মদিবস (হোম সার্ভিস)',
                'required_documents' => "১. বাসা/দোকানের পূর্ণ ঠিকানা ও লোকেশন\n২. ক্যামেরার সংখ্যা ও কাজের বিবরণ (নতুন ক্যামেরা ইনস্টলেশন / মেরামত / মালামাল ক্রয়)",
                'is_online_orderable' => true,
                'is_featured' => true,
                'icon' => 'bi-camera-video',
                'sort_order' => 10,
            ],
        ];

        foreach ($usefulLinks as $linkData) {
            UsefulLink::updateOrCreate(
                ['slug' => $linkData['slug']],
                $linkData
            );
        }
    }
}

