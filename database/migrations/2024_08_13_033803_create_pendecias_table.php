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
        Schema::create('pendecias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('titulo');
            $table->boolean('foi_resolvida');
            $table->enum('tipo',['transferencia','emprestimo']);
            $table->unsignedBigInteger('emprestimo_id')->nullable();
            $table->foreign('transacao_id')->references('id')->on('transacoes');
            $table->unsignedBigInteger('transacao_id')->nullable();
            $table->foreign('emprestimo_id')->references('id')->on('emprestimos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendecias');
    }
};
