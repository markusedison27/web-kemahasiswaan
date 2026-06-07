@extends('layouts.app')

@section('title', 'Kelas Mengajar')
@section('page_title', 'Kelas Mengajar Dosen')

@section('content')
<div class="glass-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-mortarboard-fill text-primary me-2"></i>Daftar Kelas Mengajar Aktif</h5>

    @if($classes->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-bookmark-dash display-3 text-muted mb-3"></i>
            <p class="mb-0">Belum ada mata kuliah yang dijadwalkan untuk Anda semester ini.</p>
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
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $c)
                        <tr>
                            <td><code>{{ $c->mataKuliah->kode_mk }}</code></td>
                            <td><div class="fw-bold text-dark">{{ $c->mataKuliah->nama_mk }}</div></td>
                            <td><span class="badge bg-light text-dark border">{{ $c->mataKuliah->sks }} SKS</span></td>
                            <td>Semester {{ $c->mataKuliah->semester }}</td>
                            <td class="text-end">
                                <a href="{{ route('dosen.grades', $c->mata_kuliah_id) }}" class="btn btn-custom-primary">
                                    <i class="bi bi-pencil-square me-1"></i>Input & Kelola Nilai
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
