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
    Schema::create('pengaduans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke siswa/guru
        $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
        $table->string('lokasi', 100); // Lokasi kerusakan [cite: 331]
        $table->text('keterangan'); // Detail aspirasi [cite: 333]
        $table->string('foto')->nullable(); // Opsional tapi bagus untuk bukti
        $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu'); // Sesuai soal [cite: 335]
        $table->text('feedback')->nullable(); // Tanggapan admin/guru [cite: 339]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
