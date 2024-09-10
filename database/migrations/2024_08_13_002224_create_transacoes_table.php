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
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('esta_pendente');
            $table->double('valor');
            $table->enum('tipo',['saque','deposito','transferencia','emprestimo','pagamento_emprestimo']);
            $table->unsignedBigInteger('conta_remetente_id')->nullable();
            $table->foreign('conta_remetente_id')->references('id')->on('contas')->nullOnDelete();
            $table->unsignedBigInteger('conta_destinatario_id')->nullable();
            $table->foreign('conta_destinatario_id')->references('id')->on('contas')->nullOnDelete();
            $table->unsignedBigInteger('autoridade_id')->nullable();
            $table->foreign('autoridade_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes');
    }
};
