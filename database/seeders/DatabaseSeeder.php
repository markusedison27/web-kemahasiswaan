<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siakad.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Seed Dosen
        $userDosen1 = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi@siakad.com',
            'password' => bcrypt('dosen123'),
            'role' => 'dosen',
            'status' => 'active',
        ]);
        $dosen1 = \App\Models\Dosen::create([
            'user_id' => $userDosen1->id,
            'nidn' => '1111111111',
            'nama' => $userDosen1->name,
            'spesialisasi' => 'Cyber Security',
            'telepon' => '081234567890',
            'alamat' => 'Sleman, Yogyakarta',
        ]);

        $userDosen2 = User::create([
            'name' => 'Dr. Siti Aminah',
            'email' => 'siti@siakad.com',
            'password' => bcrypt('dosen123'),
            'role' => 'dosen',
            'status' => 'active',
        ]);
        $dosen2 = \App\Models\Dosen::create([
            'user_id' => $userDosen2->id,
            'nidn' => '2222222222',
            'nama' => $userDosen2->name,
            'spesialisasi' => 'Software Engineering',
            'telepon' => '081298765432',
            'alamat' => 'Bantul, Yogyakarta',
        ]);

        // 3. Seed Mahasiswa
        $userMhs1 = User::create([
            'name' => 'Adi Wijaya',
            'email' => 'adi@siakad.com',
            'password' => bcrypt('mhs123'),
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);
        $mhs1 = \App\Models\Mahasiswa::create([
            'user_id' => $userMhs1->id,
            'nim' => '240001',
            'nama' => $userMhs1->name,
            'jurusan' => 'Teknik Informatika',
            'angkatan' => 2024,
            'telepon' => '082134567801',
            'alamat' => 'Depok, Sleman',
        ]);

        $userMhs2 = User::create([
            'name' => 'Citra Lestari',
            'email' => 'citra@siakad.com',
            'password' => bcrypt('mhs123'),
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);
        $mhs2 = \App\Models\Mahasiswa::create([
            'user_id' => $userMhs2->id,
            'nim' => '240002',
            'nama' => $userMhs2->name,
            'jurusan' => 'Sistem Informasi',
            'angkatan' => 2024,
            'telepon' => '082134567802',
            'alamat' => 'Gondokusuman, Kota Yogyakarta',
        ]);

        $userMhs3 = User::create([
            'name' => 'Dewi Pratama',
            'email' => 'dewi@siakad.com',
            'password' => bcrypt('mhs123'),
            'role' => 'mahasiswa',
            'status' => 'active',
        ]);
        $mhs3 = \App\Models\Mahasiswa::create([
            'user_id' => $userMhs3->id,
            'nim' => '240003',
            'nama' => $userMhs3->name,
            'jurusan' => 'Teknik Informatika',
            'angkatan' => 2024,
            'telepon' => '082134567803',
            'alamat' => 'Kasihan, Bantul',
        ]);

        // 4. Seed Mata Kuliah
        $mk1 = \App\Models\MataKuliah::create([
            'kode_mk' => 'IF-301',
            'nama_mk' => 'Keamanan Siber',
            'sks' => 3,
            'semester' => 4,
        ]);

        $mk2 = \App\Models\MataKuliah::create([
            'kode_mk' => 'IF-302',
            'nama_mk' => 'Pemrograman Web Laravel',
            'sks' => 3,
            'semester' => 4,
        ]);

        $mk3 = \App\Models\MataKuliah::create([
            'kode_mk' => 'IF-303',
            'nama_mk' => 'Basis Data Lanjut',
            'sks' => 3,
            'semester' => 4,
        ]);

        $mk4 = \App\Models\MataKuliah::create([
            'kode_mk' => 'IF-304',
            'nama_mk' => 'Jaringan Komputer',
            'sks' => 3,
            'semester' => 4,
        ]);

        // 5. Seed Jadwal
        \App\Models\Jadwal::create([
            'mata_kuliah_id' => $mk1->id,
            'dosen_id' => $dosen1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'ruangan' => 'Lab Cyber Security',
        ]);

        \App\Models\Jadwal::create([
            'mata_kuliah_id' => $mk2->id,
            'dosen_id' => $dosen2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:30:00',
            'ruangan' => 'Lab Web & Software',
        ]);

        \App\Models\Jadwal::create([
            'mata_kuliah_id' => $mk3->id,
            'dosen_id' => $dosen2->id,
            'hari' => 'Rabu',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:30:00',
            'ruangan' => 'Ruang Teori R-202',
        ]);

        \App\Models\Jadwal::create([
            'mata_kuliah_id' => $mk4->id,
            'dosen_id' => $dosen1->id,
            'hari' => 'Kamis',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'ruangan' => 'Lab Jaringan',
        ]);
    }
}
