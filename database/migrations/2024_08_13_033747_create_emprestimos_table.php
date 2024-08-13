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
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->double('valor');
            $table->boolean('esta_pendente');
            $table->boolean('foi_aprovado')->nullable();
            $table->double('taxa_juros_mensal')->nullable();
            $table->double('quantidade_a_pagar')->nullable();
            $table->dateTime('data_limite')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->unsignedBigInteger('conta_id');
             $table->foreign('conta_id')->references('id')->on('contas');
            $table->unsignedBigInteger('transacao_id');
            $table->foreign('transacao_id')->references('id')->on('transacoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};
