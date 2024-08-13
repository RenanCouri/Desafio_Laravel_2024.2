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
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->nullable();
            $table->string('numero_telefone',20);
            $table->date('data_nascimento');
            $table->string('CPF',20);
            $table->enum('cargo',['usuario_comum','gerente','administrador']);
            $table->unsignedBigInteger('endereco_id');
            $table->foreign('endereco_id')->references('id')->on('enderecos');
            $table->unsignedBigInteger('usuario_responsavel_id');
            $table->foreign('usuario_responsavel_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto');
            $table->dropColumn('numero_telefone');
            $table->dropColumn('data_nascimento');
            $table->dropColumn('CPF');
            $table->dropColumn('cargo');
            $table->dropForeign('endereco_id');
            $table->dropForeign('usuario_id_responsavel');
            $table->dropForeColumn('endereco_id');
            $table->dropColumn('usuario_id_responsavel');
        });
    }
};
