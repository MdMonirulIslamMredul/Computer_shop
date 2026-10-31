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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('bkash_number')->nullable()->after('social_links');
            $table->string('nagad_number')->nullable()->after('bkash_number');
            $table->string('rocket_number')->nullable()->after('nagad_number');
            $table->text('payment_instructions')->nullable()->after('rocket_number');
            $table->text('emergency_notice')->nullable()->after('payment_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'bkash_number',
                'nagad_number',
                'rocket_number',
                'payment_instructions',
                'emergency_notice',
            ]);
        });
    }
};

