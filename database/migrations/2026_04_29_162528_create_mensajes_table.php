<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('contenido');
            $table->enum('destino_tipo', ['todos', 'departamento', 'municipio', 'localidad']);
            $table->unsignedBigInteger('destino_id')->nullable();
            $table->string('destino_nombre', 200)->nullable();
            $table->unsignedInteger('total_destinatarios')->default(0);
            $table->unsignedInteger('enviados')->default(0);
            $table->unsignedInteger('fallidos')->default(0);
            $table->enum('estado', ['pendiente', 'enviando', 'completado', 'fallido'])->default('pendiente');
            $table->timestamps();
        });

        Schema::create('mensaje_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mensaje_id')->constrained('mensajes')->onDelete('cascade');
            $table->foreignId('miembro_id')->constrained('miembros')->onDelete('cascade');
            $table->string('telefono', 30);
            $table->enum('estado', ['enviado', 'fallido'])->default('enviado');
            $table->string('error', 255)->nullable();
            $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensaje_envios');
        Schema::dropIfExists('mensajes');
    }
};
