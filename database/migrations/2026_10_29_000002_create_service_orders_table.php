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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id')->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_title');
            $table->string('customer_name');
            $table->string('customer_phone')->index();
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->json('application_data')->nullable(); // applicant details: post, name, parent names, dob, reg, roll, etc.
            $table->text('customer_notes')->nullable();
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('govt_fee', 10, 2)->default(0);
            $table->decimal('total_fee', 10, 2)->default(0);
            $table->string('payment_method')->default('bkash'); // bkash, nagad, rocket, shop_cash
            $table->string('payment_number')->nullable();
            $table->string('transaction_id')->nullable()->index();
            $table->string('payment_status')->default('pending'); // pending, verified, rejected
            $table->string('order_status')->default('pending'); // pending, processing, completed, delivered, cancelled
            $table->text('admin_notes')->nullable();
            $table->string('completed_file_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};

