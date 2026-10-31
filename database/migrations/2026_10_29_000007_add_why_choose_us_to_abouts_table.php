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
        Schema::table('abouts', function (Blueprint $table) {
            if (!Schema::hasColumn('abouts', 'why_choose_us_title')) {
                $table->string('why_choose_us_title')->nullable()->after('key_values');
            }
            if (!Schema::hasColumn('abouts', 'why_choose_us_subtitle')) {
                $table->text('why_choose_us_subtitle')->nullable()->after('why_choose_us_title');
            }
            if (!Schema::hasColumn('abouts', 'why_choose_us_points')) {
                $table->json('why_choose_us_points')->nullable()->after('why_choose_us_subtitle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn(['why_choose_us_title', 'why_choose_us_subtitle', 'why_choose_us_points']);
        });
    }
};

