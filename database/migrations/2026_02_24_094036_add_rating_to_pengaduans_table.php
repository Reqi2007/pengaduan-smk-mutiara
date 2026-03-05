<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::table('pengaduans', function (Blueprint $table) {
        $table->integer('rating')->nullable(); // Bintang 1-5
        $table->text('ulasan_murid')->nullable(); // Komentar kebahagiaan
    });
}

public function down(): void
{
    Schema::table('pengaduans', function (Blueprint $table) {
        $table->dropColumn(['rating', 'ulasan_murid']);
    });
}
};
