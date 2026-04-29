<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['operador', 'supervisor'])->default('operador')->after('email');
            $table->foreignId('municipio_id')->nullable()->after('rol')
                  ->constrained('municipios')->onDelete('set null');
            $table->string('google_id')->nullable()->unique()->after('municipio_id');
            $table->string('avatar')->nullable()->after('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['municipio_id']);
            $table->dropColumn(['rol', 'municipio_id', 'google_id', 'avatar']);
        });
    }
};
