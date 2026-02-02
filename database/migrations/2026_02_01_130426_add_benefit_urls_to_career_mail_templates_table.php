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
        Schema::table('career_mail_templates', function (Blueprint $table) {
            $table->string('benefit_1_url')->nullable()->after('benefit_1_image');
            $table->string('benefit_2_url')->nullable()->after('benefit_2_image');
            $table->string('benefit_3_url')->nullable()->after('benefit_3_image');
            $table->string('benefit_4_url')->nullable()->after('benefit_4_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_mail_templates', function (Blueprint $table) {
            $table->dropColumn([
                'benefit_1_url',
                'benefit_2_url',
                'benefit_3_url',
                'benefit_4_url',
            ]);
        });
    }
};
