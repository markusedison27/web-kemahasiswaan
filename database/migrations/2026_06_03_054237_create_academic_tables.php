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
        // 1. Dosen Table
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nidn', 20)->unique();
            $table->string('nama', 100);
            $table->string('spesialisasi', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // 2. Mahasiswa Table
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->string('jurusan', 100);
            $table->integer('angkatan');
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // 3. Mata Kuliah Table
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 20)->unique();
            $table->string('nama_mk', 100);
            $table->integer('sks');
            $table->integer('semester'); // Semester ke- (1, 2, 3...)
            $table->timestamps();
        });

        // 4. Jadwal Table
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->string('hari', 15); // Senin, Selasa, etc.
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 50);
            $table->timestamps();
        });

        // 5. KRS Table
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->string('tahun_akademik', 15); // e.g. 2025/2026
            $table->string('semester', 10); // Ganjil / Genap
            $table->string('status_approval', 20)->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        // 6. Nilai Table
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs')->onDelete('cascade');
            $table->integer('nilai_angka')->nullable();
            $table->string('nilai_huruf', 5)->nullable(); // A, B, C, D, E
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->timestamps();
        });

        // 7. Activity Logs Table (Security Feature)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 50); // LOGIN, LOGOUT, ADD_USER, BACKUP_DB, etc.
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('krs');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('dosen');
    }
};
