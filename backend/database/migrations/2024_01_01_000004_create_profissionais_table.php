<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nivel_id')->constrained('niveis');
            $table->string('nome');
            $table->enum('sexo', ['M', 'F', 'O']);
            $table->date('data_nascimento');
            $table->string('hobby')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};
