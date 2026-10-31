<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('useful_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('সরকারি সেবা'); // চাকরির আবেদন, ভর্তি ও শিক্ষা, এনআইডি ও জন্মনিবন্ধন, পাসপোর্ট ও ভিসা, সরকারি ও অন্যান্য
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->text('required_documents')->nullable();
            $table->string('external_url');
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('icon')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('useful_links');
    }
};

