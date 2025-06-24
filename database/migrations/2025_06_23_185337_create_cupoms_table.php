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
       Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código do cupom
            $table->decimal('desconto_valor', 10, 2)->nullable(); // Desconto fixo em reais
            $table->decimal('desconto_percentual', 5, 2)->nullable(); // Desconto percentual (ex: 10.00 = 10%)
            $table->decimal('valor_minimo', 10, 2)->default(0); // Valor mínimo do subtotal para usar o cupom
            $table->date('validade'); // Data de validade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
