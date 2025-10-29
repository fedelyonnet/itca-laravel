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
        Schema::table('noticias', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('titulo');
        });
        
        // Generar slugs para noticias existentes
        $noticias = \App\Models\Noticia::all();
        foreach ($noticias as $noticia) {
            $slug = \Str::slug($noticia->titulo);
            $slug = $noticia->id . '-' . $slug;
            $noticia->update(['slug' => $slug]);
        }
        
        // Ahora hacer el campo único
        Schema::table('noticias', function (Blueprint $table) {
            $table->string('slug', 255)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};