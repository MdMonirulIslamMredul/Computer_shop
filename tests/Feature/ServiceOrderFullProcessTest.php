<?php

namespace Tests\Feature;

use App\Models\OrderAttachment;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceOrderFullProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Setting::firstOrCreate([], [
            'site_name' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার',
            'phone' => '01711665098',
            'email' => 'info@example.com',
            'address' => 'আলেকজান্ডার, রামগতি, লক্ষ্মীপুর',
        ]);
    }

    public function test_complete_order_lifecycle()
    {
        Storage::fake('public');

        // 1. Ensure an online service exists
        $service = Service::where('status', true)->where('is_online_orderable', true)->first();
        if (!$service) {
            $service = Service::create([
                'title' => 'জাতীয় পরিচয়পত্র (NID) সংশোধন ও রি-প্রিন্ট',
                'slug' => 'nid-correction-reprint-test',
                'service_fee' => 150.00,
                'govt_fee' => 100.00,
                'status' => true,
                'is_online_orderable' => true,
                'sort_order' => 1,
            ]);
        }

        // 2. Test GET create order page
        $createPageResponse = $this->get(route('orders.create', ['service' => $service->slug]));
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSee($service->title);

        // 3. Test POST create order with minimum 2 documents and applicant photo
        $photo = UploadedFile::fake()->image('applicant_passport.jpg', 300, 300);
        $doc1 = UploadedFile::fake()->create('nid_copy.pdf', 300, 'application/pdf');
        $doc2 = UploadedFile::fake()->image('ssc_certificate.jpg', 800, 600);
        $doc3 = UploadedFile::fake()->create('signature.png', 100, 'image/png');

        $orderPayload = [
            'service_id' => $service->id,
            'customer_name' => 'মোঃ রফিকুল ইসলাম',
            'customer_phone' => '01711665098',
            'customer_email' => 'rofiq@example.com',
            'customer_address' => 'আলেকজান্ডার, রামগতি, লক্ষ্মীপুর',
            'customer_notes' => 'জরুরি প্রয়োজন, দ্রুত সমাধান চাই।',
            'app_applicant_name_bn' => 'মোঃ রফিকুল ইসলাম',
            'app_applicant_name_en' => 'Md Rofiqul Islam',
            'app_father_name_bn' => 'মোঃ আব্দুর রহমান',
            'app_mother_name_bn' => 'ফাতেমা বেগম',
            'app_dob' => '1995-05-15',
            'app_nid_birth_reg_no' => '19951234567890123',
            'payment_method' => 'bkash',
            'payment_number' => '01711665098',
            'transaction_id' => 'TRX882910AK',
            'applicant_photo' => $photo,
            'attachments' => [$doc1, $doc2, $doc3],
        ];

        $storeResponse = $this->post(route('orders.store'), $orderPayload);

        // Should redirect to confirmation page
        $storeResponse->assertStatus(302);
        
        $order = ServiceOrder::with('attachments')->where('customer_phone', '01711665098')->latest()->first();
        $this->assertNotNull($order, 'Order was not created in database.');
        $this->assertEquals('মোঃ রফিকুল ইসলাম', $order->customer_name);
        $this->assertEquals($service->title, $order->service_title);
        $this->assertEquals(250.00, $order->total_fee);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals('pending', $order->payment_status);

        // Check attachments in database (1 applicant photo + 3 attachments = 4 records)
        $this->assertEquals(4, $order->attachments->count(), 'All attachments and applicant photo should be recorded.');
        
        // Check files exist in fake storage
        foreach ($order->attachments as $att) {
            Storage::disk('public')->assertExists($att->file_path);
        }

        // 4. Test Confirmation page
        $confirmResponse = $this->get(route('orders.confirmation', $order->tracking_id));
        $confirmResponse->assertStatus(200);
        $confirmResponse->assertSee($order->tracking_id);

        // 5. Test Public Tracking page
        $trackResponse = $this->get(route('orders.track', ['search' => $order->tracking_id]));
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->tracking_id);
        $trackResponse->assertSee('অপেক্ষমাণ');

        // 6. Test Admin Functions
        $admin = User::firstOrCreate(
            ['email' => 'admin_tester@test.com'],
            [
                'name' => 'Admin Tester',
                'password' => bcrypt('password123'),
                'is_admin' => true,
            ]
        );

        // Admin Orders List
        $adminListResponse = $this->actingAs($admin)->get(route('admin.orders.index'));
        $adminListResponse->assertStatus(200);
        $adminListResponse->assertSee($order->tracking_id);
        $adminListResponse->assertSee('মোঃ রফিকুল ইসলাম');

        // Admin Order Details Show
        $adminShowResponse = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));
        $adminShowResponse->assertStatus(200);
        $adminShowResponse->assertSee($order->tracking_id);
        $adminShowResponse->assertSee('TRX882910AK');
        $adminShowResponse->assertSee('nid_copy.pdf');
        $adminShowResponse->assertSee('ssc_certificate.jpg');
        $adminShowResponse->assertSee('signature.png');
        $adminShowResponse->assertSee('আবেদনকারীর ছবি (User Photo)');

        // Admin Verify Payment
        $verifyResponse = $this->actingAs($admin)->post(route('admin.orders.verify-payment', $order->id));
        $verifyResponse->assertStatus(302);
        $order->refresh();
        $this->assertEquals('verified', $order->payment_status);
        $this->assertEquals('processing', $order->order_status);

        // Admin Status Update with notes
        $updateStatusResponse = $this->actingAs($admin)->put(route('admin.orders.update-status', $order->id), [
            'order_status' => 'processing',
            'payment_status' => 'verified',
            'admin_notes' => 'আপনার আবেদনটি নির্বাচন কমিশন সার্ভারে দাখিল করা হয়েছে।',
        ]);
        $updateStatusResponse->assertStatus(302);
        $order->refresh();
        $this->assertEquals('আপনার আবেদনটি নির্বাচন কমিশন সার্ভারে দাখিল করা হয়েছে।', $order->admin_notes);

        // Admin Upload Completed File (e.g. final PDF slip)
        $completedFile = UploadedFile::fake()->create('nid_final_slip.pdf', 500, 'application/pdf');
        $uploadFileResponse = $this->actingAs($admin)->post(route('admin.orders.upload-file', $order->id), [
            'completed_file' => $completedFile,
        ]);
        $uploadFileResponse->assertStatus(302);
        $order->refresh();
        $this->assertEquals('completed', $order->order_status);
        $this->assertNotNull($order->completed_file_path);
        Storage::disk('public')->assertExists($order->completed_file_path);

        // Customer download of completed file
        $downloadResponse = $this->get(route('orders.download', $order->tracking_id));
        $downloadResponse->assertStatus(200);

        // Admin Print Slip
        $printResponse = $this->actingAs($admin)->get(route('admin.orders.print', $order->id));
        $printResponse->assertStatus(200);
        $printResponse->assertSee($order->tracking_id);
        $printResponse->assertSee('স্লিপ');

        // 7. Test Manual Service Order placement
        $manualDoc1 = UploadedFile::fake()->create('cctv_plan.pdf', 100);
        $manualDoc2 = UploadedFile::fake()->image('house_photo.jpg', 400, 300);

        $manualOrderPayload = [
            'service_id' => 'other',
            'custom_service_title' => 'সিসিটিভি ক্যামেরা মেরামত ও ক্যাবল রিপ্লেসমেন্ট',
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01812345678',
            'customer_email' => 'tanvir@example.com',
            'customer_address' => 'চর হাসান হোসেন, রামগতি',
            'payment_method' => 'shop_cash',
            'attachments' => [$manualDoc1, $manualDoc2],
        ];

        $manualStoreResponse = $this->post(route('orders.store'), $manualOrderPayload);
        $manualStoreResponse->assertStatus(302);

        $manualOrder = ServiceOrder::where('customer_phone', '01812345678')->latest()->first();
        $this->assertNotNull($manualOrder);
        $this->assertEquals('সিসিটিভি ক্যামেরা মেরামত ও ক্যাবল রিপ্লেসমেন্ট', $manualOrder->service_title);
        $this->assertEquals(0.00, $manualOrder->total_fee);
        $this->assertEquals('shop_cash', $manualOrder->payment_method);
        $this->assertEquals(2, $manualOrder->attachments->count());

        // Admin can view manual order cleanly
        $adminManualShow = $this->actingAs($admin)->get(route('admin.orders.show', $manualOrder->id));
        $adminManualShow->assertStatus(200);
        $adminManualShow->assertSee('সিসিটিভি ক্যামেরা মেরামত ও ক্যাবল রিপ্লেসমেন্ট');
        $adminManualShow->assertSee('দোকানে নগদ প্রদান (Cash at Shop)');
    }
}
