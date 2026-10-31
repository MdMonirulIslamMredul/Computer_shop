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
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('service_fee', 10, 2)->default(0)->after('description');
            $table->decimal('govt_fee', 10, 2)->default(0)->after('service_fee');
            $table->string('delivery_time')->nullable()->after('govt_fee');
            $table->text('required_documents')->nullable()->after('delivery_time');
            $table->boolean('is_online_orderable')->default(true)->after('required_documents');
            $table->string('icon')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'service_fee',
                'govt_fee',
                'delivery_time',
                'required_documents',
                'is_online_orderable',
                'icon',
            ]);
        });
    }
};

