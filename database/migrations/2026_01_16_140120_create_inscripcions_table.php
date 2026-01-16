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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            
            // Usamos ID_Curso (string) porque así es la PK lógica en la tabla cursadas actual
            // Idealmente 'cursadas' debería tener ID numérico como FK, pero mantenemos consistencia con lead_cursadas
            $table->string('cursada_id')->index(); 
            
            // Datos del Pago
            $table->string('estado')->default('pendiente'); // pendiente, pagado, rechazado
            $table->decimal('monto_matricula', 10, 2);
            $table->string('preference_id')->nullable(); // ID de preferencia de MP
            $table->string('collection_id')->nullable(); // ID de pago de MP
            $table->string('payment_type')->nullable(); // credit_card, ticket, etc
            $table->string('merchant_order_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
