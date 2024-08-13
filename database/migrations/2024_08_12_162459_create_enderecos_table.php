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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('pais',64);
            $table->string('estado',64);
            $table->string('cidade',80);
            $table->string('bairro',70);
            $table->string('rua',80);
            $table->string('numero_predial',10);
            $table->string('completemento',16)->nullable();
            $table->string('CEP',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
