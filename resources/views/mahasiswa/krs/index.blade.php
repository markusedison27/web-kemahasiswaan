@extends('layouts.app')

@section('title', 'Isi KRS')
@section('page_title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="row">
    <!-- Form Tambah Mata Kuliah -->
    <div class="col-md-4 mb-4">
        <div class="glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-journal-plus text-primary me-2"></i>Pilih Mata Kuliah</h5>
            <p class="text-muted" style="font-size: 0.85rem;">
                Pilih mata kuliah dari daftar yang tersedia untuk ditambahkan ke rencana studi Anda pada semester ini.
            </p>

            @if($availableCourses->isEmpty())
                <div class="alert alert-info border-0" style="border-radius: 10px; font-size: 0.85rem;">
                    Semua mata kuliah yang tersedia telah ditambahkan ke KRS Anda.
                </div>
            @else
                <form action="{{ route('mahasiswa.krs.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem;">Mata Kuliah Tersedia</label>
                        <select name="mata_kuliah_id" class="form-select" required style="border-radius: 10px;">
                            <option value="" disabled selected>Pilih Mata Kuliah...</option>
                            @foreach($availableCourses as $ac)
                                <option value="{{ $ac->id }}">
                                    {{ $ac->kode_mk }} - {{ $ac->nama_mk }} ({{ $ac->sks }} SKS)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-custom-primary w-100 py-2">
                        <i class="bi bi-plus-lg me-1"></i>Tambahkan Ke KRS
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Daftar KRS Terpilih -->
    <div class="col-md-8 mb-4">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>KRS Anda Semester Ini</h5>
                <span class="badge bg-success px-3 py-2" style="border-radius: 20px; font-size: 0.9rem;">
                    {{ $totalSks }} / 24 Maks SKS
                </span>
            </div>

            @if($krs->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-journal-x display-3 mb-3"></i>
                    <p class="mb-0">KRS Anda masih kosong. Silakan pilih mata kuliah di form sebelah kiri.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Status Approval</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($krs as $k)
                                <tr>
                                    <td><code>{{ $k->mataKuliah->kode_mk }}</code></td>
                                    <td><div class="fw-bold text-dark">{{ $k->mataKuliah->nama_mk }}</div></td>
                                    <td><span class="badge bg-light text-dark border">{{ $k->mataKuliah->sks }} SKS</span></td>
                                    <td>Semester {{ $k->mataKuliah->semester }}</td>
                                    <td>
                                        @if($k->status_approval === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2" style="border-radius: 20px;">Disetujui</span>
                                        @elseif($k->status_approval === 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2" style="border-radius: 20px;">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2" style="border-radius: 20px;">Awaiting</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('mahasiswa.krs.delete', $k->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">
                                                <i class="bi bi-trash3 me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
