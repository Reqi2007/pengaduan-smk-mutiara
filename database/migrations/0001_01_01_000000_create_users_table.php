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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Sekarang Nama harus unik karena dipakai untuk login
            $table->string('email')->nullable(); // Email kita buat boleh kosong
            $table->string('password');
            
            // Data Akun Multi-Role
            $table->enum('role', ['superadmin', 'guru', 'murid'])->default('murid'); 
            $table->boolean('is_active')->default(true); 
            
            // Profil Tambahan (Khusus Guru / Murid)
            $table->string('nis_nip')->unique()->nullable();
            $table->string('foto_profile')->nullable(); // Foto Profil
            $table->string('kelas', 20)->nullable(); // Contoh: XII, XI
            $table->string('jurusan', 50)->nullable(); // Contoh: Rekayasa Perangkat Lunak
            $table->string('no_telp', 20)->nullable(); // Bonus: Kontak darurat/WA
            
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // ... (Biarkan tabel password_reset_tokens dan sessions di bawahnya apa adanya)

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
