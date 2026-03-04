<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();

            // Relacion con la factura y el cajero
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Relacion con la caja
            $table->foreignId('cash_register_movement_id')->nullable()->constrained('cash_register_movements')->nullOnDelete();

            // Detalles del pago
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // cash, credit_card, etc.

            // Datos adicionales si paga con tarjeta
            $table->foreignId('card_plan_id')->nullable()->constrained('card_plans')->nullOnDelete();

            // Descripción general
            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
