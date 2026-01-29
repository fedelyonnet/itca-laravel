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
        Schema::create('career_mail_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            
            // Imágenes
            $table->string('header_image')->nullable();
            $table->string('main_illustration')->nullable();
            $table->string('certificate_image')->nullable();
            $table->string('benefit_1_image')->nullable();
            $table->string('benefit_2_image')->nullable();
            $table->string('benefit_3_image')->nullable();
            $table->string('benefit_4_image')->nullable();
            $table->string('utn_banner_image')->nullable();
            $table->string('partners_image')->nullable();
            $table->string('illustration_2')->nullable();
            $table->string('illustration_3')->nullable();
            $table->string('illustration_4')->nullable();
            $table->string('illustration_5')->nullable();
            $table->string('bottom_image')->nullable();

            // Documentos (PDFs)
            $table->string('syllabus_year_1')->nullable();
            $table->string('syllabus_year_2')->nullable();
            $table->string('syllabus_year_3')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_mail_templates');
    }
};
