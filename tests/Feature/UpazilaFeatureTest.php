<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Upazila;
use App\Models\User;
use Database\Seeders\UpazilaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpazilaFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::create([
            'site_name' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার',
        ]);
        $this->seed(UpazilaSeeder::class);
    }

    protected function getAdminUser(): User
    {
        return User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => bcrypt('12345678'),
            'is_admin' => true,
        ]);
    }

    public function test_frontend_upazila_pages_are_accessible_and_render_content(): void
    {
        $response = $this->get(route('upazila.show', 'upazila-informations'));
        $response->assertStatus(200);
        $response->assertSee('উপজেলা সম্পর্কিত তথ্য');
        $response->assertSee('রামগতি উপজেলা');

        // Check dropdown renders in navbar
        $response->assertSee('উপজেলা');
        $response->assertSee('upazila-contacts');
        $response->assertSee('upazila-famous-places');

        // Check footer renders Upazila information links
        $response->assertSee('উপজেলা তথ্য');
        $response->assertSee(route('upazila.show', 'upazila-informations'));
        $response->assertSee(route('upazila.show', 'upazila-contacts'));
        $response->assertSee(route('upazila.show', 'upazila-famous-places'));
    }

    public function test_admin_can_view_upazilas_index(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.upazilas.index'));
        $response->assertStatus(200);
        $response->assertSee('উপজেলা সম্পর্কিত পেইজ সমূহ');
        $response->assertSee('upazila-informations');
    }

    public function test_admin_can_view_create_page(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get(route('admin.upazilas.create'));
        $response->assertStatus(200);
        $response->assertSee('নতুন উপজেলা পেইজ যোগ করুন');
    }

    public function test_admin_can_create_a_new_upazila_page(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $banner = UploadedFile::fake()->image('banner.jpg', 1200, 400);
        $img1 = UploadedFile::fake()->image('img1.jpg', 600, 400);

        $response = $this->actingAs($admin)->post(route('admin.upazilas.store'), [
            'title' => 'টেস্ট উপজেলা পেইজ',
            'slug' => 'test-upazila-page',
            'sort_order' => 10,
            'is_active' => '1',
            'banner_image' => $banner,
            'image_1' => $img1,
            'detail_1' => '<p>টেস্ট ডিটেইল ১ এর বিবরণ</p>',
            'detail_2' => '<p>টেস্ট ডিটেইল ২ এর বিবরণ</p>',
            'detail_3' => '<p>টেস্ট ডিটেইল ৩ এর বিবরণ</p>',
        ]);

        $response->assertRedirect(route('admin.upazilas.index'));
        $this->assertDatabaseHas('upazilas', [
            'slug' => 'test-upazila-page',
            'title' => 'টেস্ট উপজেলা পেইজ',
        ]);
    }

    public function test_admin_can_update_an_upazila_page(): void
    {
        $admin = $this->getAdminUser();
        $upazila = Upazila::first();

        $response = $this->actingAs($admin)->put(route('admin.upazilas.update', $upazila->id), [
            'title' => 'আপডেটেড উপজেলা টাইটেল',
            'slug' => $upazila->slug,
            'sort_order' => 5,
            'is_active' => '1',
            'detail_1' => '<p>আপডেটেড ডিটেইল ১</p>',
            'detail_2' => '<p>আপডেটেড ডিটেইল ২</p>',
            'detail_3' => '<p>আপডেটেড ডিটেইল ৩</p>',
        ]);

        $response->assertRedirect(route('admin.upazilas.index'));
        $this->assertDatabaseHas('upazilas', [
            'id' => $upazila->id,
            'title' => 'আপডেটেড উপজেলা টাইটেল',
        ]);
    }

    public function test_admin_can_delete_an_upazila_page(): void
    {
        $admin = $this->getAdminUser();
        $upazila = Upazila::create([
            'title' => 'Temporary Upazila to Delete',
            'slug' => 'temporary-upazila-delete',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.upazilas.destroy', $upazila->id));
        $response->assertRedirect(route('admin.upazilas.index'));
        $this->assertDatabaseMissing('upazilas', [
            'id' => $upazila->id,
        ]);
    }
}
