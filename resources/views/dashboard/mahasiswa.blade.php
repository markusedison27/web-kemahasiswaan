@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard')
@section('page_title', 'Dashboard Mahasiswa')

@section('content')
@if(!$mahasiswa)
{{-- User mahasiswa baru yang belum punya data profil --}}
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="glass-card text-center py-5">
            <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <h4 class="fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 🎉</h4>
            <p class="text-muted mb-4">Akun Anda telah berhasil diverifikasi dan aktif.</p>

            <form action="{{ route('mahasiswa.profile.setup') }}" method="POST" class="text-start mt-4 bg-light p-4 rounded-4 shadow-sm mx-auto" style="max-width: 500px;">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">NIM</label>
                    <input type="text" name="nim" class="form-control" required placeholder="Contoh: 12345678" value="{{ old('nim') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jurusan</label>
                    <select name="jurusan" class="form-select" required>
                        <option value="" disabled selected>Pilih Jurusan Anda</option>
                        @foreach($jurusans as $j)
                            <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tahun Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" required placeholder="Contoh: {{ date('Y') }}" value="{{ old('angkatan') ?? date('Y') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control" placeholder="Opsional" value="{{ old('telepon') }}">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Opsional">{{ old('alamat') }}</textarea>
                </div>
                <button type="submit" class="btn btn-custom-primary w-100 py-2 fw-bold">
                    <i class="bi bi-save2 me-2"></i>Simpan Profil & Buka Fitur
                </button>
            </form>

            <div class="mt-4">
                <span class="badge bg-success px-3 py-2" style="border-radius: 20px;">
                    <i class="bi bi-check-circle me-1"></i>Akun Aktif
                </span>
                <span class="badge bg-primary px-3 py-2 ms-2" style="border-radius: 20px;">
                    <i class="bi bi-person me-1"></i>Role: Mahasiswa
                </span>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="glass-card text-center py-4 h-100">
            <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($mahasiswa->nama, 0, 2)) }}
            </div>
            <h4 class="fw-bold mb-1">{{ $mahasiswa->nama }}</h4>
            <p class="text-muted mb-2">NIM: {{ $mahasiswa->nim }}</p>
            <span class="badge bg-primary px-3 py-2 mb-2" style="border-radius: 20px;">{{ $mahasiswa->jurusan }}</span>
            <div class="text-muted" style="font-size: 0.85rem;">Angkatan {{ $mahasiswa->angkatan }}</div>
            
            <hr class="my-4">
            
            <div class="text-start px-2">
                <div class="mb-2"><strong><i class="bi bi-telephone-fill text-muted me-2"></i>Telepon:</strong> <span class="text-muted">{{ $mahasiswa->telepon ?? '-' }}</span></div>
                <div><strong><i class="bi bi-geo-alt-fill text-muted me-2"></i>Alamat:</strong> <span class="text-muted">{{ $mahasiswa->alamat ?? '-' }}</span></div>
            </div>
        </div>
    </div>

    <!-- KRS Overview & Schedule Card -->
    <div class="col-md-8 mb-4">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-calendar3 me-2 text-primary"></i>Jadwal Kuliah Semester Ini</h5>
                <span class="badge bg-success text-white">{{ $totalSks }} SKS Disetujui</span>
            </div>

            @if($jadwal->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x display-3 text-muted mb-3"></i>
                    <p class="mb-2">Jadwal kuliah belum tersedia.</p>
                    <a href="{{ route('mahasiswa.krs') }}" class="btn btn-custom-primary mt-2">
                        <i class="bi bi-file-earmark-text-fill me-1"></i>Isi KRS Sekarang
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Kuliah</th>
                                <th>Jam</th>
                                <th>Dosen Pengampu</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwal as $j)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $j->hari }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $j->mataKuliah->nama_mk }}</div>
                                        <small class="text-muted">{{ $j->mataKuliah->kode_mk }} ({{ $j->mataKuliah->sks }} SKS)</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                        </span>
                                    </td>
                                    <td>{{ $j->dosen->nama }}</td>
                                    <td><i class="bi bi-door-closed me-1 text-muted"></i>{{ $j->ruangan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
@endsection
