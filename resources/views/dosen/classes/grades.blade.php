@extends('layouts.app')

@section('title', 'Kelola Nilai')
@section('page_title', 'Kelola Nilai Mahasiswa')

@section('content')
<div class="mb-3">
    <a href="{{ route('dosen.classes') }}" class="btn btn-sm btn-light border" style="border-radius: 8px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kelas
    </a>
</div>

<div class="glass-card mb-4 bg-primary bg-opacity-10 border-0">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold text-primary mb-1">{{ $course->nama_mk }}</h5>
            <p class="text-muted mb-0">Kode MK: <code>{{ $course->kode_mk }}</code> | SKS: {{ $course->sks }} | Semester: {{ $course->semester }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-primary px-3 py-2" style="border-radius: 20px;">Dosen: {{ $dosen->nama }}</span>
        </div>
    </div>
</div>

<div class="glass-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Mahasiswa Kelas</h5>

    @if($studentsKrs->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-person-slash display-3 mb-3"></i>
            <p class="mb-0">Belum ada mahasiswa yang mengambil mata kuliah ini di KRS mereka.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jurusan</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentsKrs as $sk)
                        <tr>
                            <td><code>{{ $sk->mahasiswa->nim }}</code></td>
                            <td><div class="fw-bold text-dark">{{ $sk->mahasiswa->nama }}</div></td>
                            <td>{{ $sk->mahasiswa->jurusan }}</td>
                            <td>
                                @if($sk->nilai)
                                    <h5 class="fw-bold mb-0 text-dark">{{ $sk->nilai->nilai_angka }}</h5>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($sk->nilai)
                                    @php
                                        $badgeColor = 'bg-danger';
                                        if ($sk->nilai->nilai_huruf == 'A') $badgeColor = 'bg-success';
                                        elseif ($sk->nilai->nilai_huruf == 'B') $badgeColor = 'bg-primary';
                                        elseif ($sk->nilai->nilai_huruf == 'C') $badgeColor = 'bg-info text-dark';
                                        elseif ($sk->nilai->nilai_huruf == 'D') $badgeColor = 'bg-warning text-dark';
                                    @endphp
                                    <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 0.9rem; border-radius: 8px;">
                                        {{ $sk->nilai->nilai_huruf }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-custom-primary" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $sk->id }}">
                                    <i class="bi bi-pencil-square me-1"></i>Input/Ubah Nilai
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if(!$studentsKrs->isEmpty())
    @foreach($studentsKrs as $sk)
        <!-- Grade Modal -->
        <div class="modal fade" id="gradeModal{{ $sk->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0" style="border-radius: 18px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Input Nilai Akademik</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dosen.grades.store', $sk->id) }}" method="POST">
                        @csrf
                        <div class="modal-body py-4">
                            <div class="mb-3 text-center bg-light p-3" style="border-radius: 12px;">
                                <h6 class="fw-bold mb-1">{{ $sk->mahasiswa->nama }}</h6>
                                <small class="text-muted">NIM: {{ $sk->mahasiswa->nim }} | Jurusan: {{ $sk->mahasiswa->jurusan }}</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nilai Angka (0 - 100)</label>
                                <input type="number" name="nilai_angka" class="form-control text-center fs-3 fw-bold" min="0" max="100" value="{{ $sk->nilai ? $sk->nilai->nilai_angka : '' }}" required style="border-radius: 12px; height: 60px;">
                            </div>
                            <div class="alert alert-info border-0 mt-3 mb-0" style="border-radius: 10px; font-size: 0.8rem;">
                                <strong>Aturan Konversi Nilai:</strong><br>
                                A (>= 85) | B (75-84) | C (60-74) | D (45-59) | E (< 45)
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Nilai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection
