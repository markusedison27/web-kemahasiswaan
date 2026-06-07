@extends('layouts.app')

@section('title', 'Dosen Dashboard')
@section('page_title', 'Dashboard Dosen')

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="glass-card text-center py-4 h-100">
            <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                {{ strtoupper(substr($dosen->nama, 0, 2)) }}
            </div>
            <h4 class="fw-bold mb-1">{{ $dosen->nama }}</h4>
            <p class="text-muted mb-2">NIDN: {{ $dosen->nidn }}</p>
            <span class="badge bg-primary px-3 py-2" style="border-radius: 20px;">{{ $dosen->spesialisasi }}</span>
            
            <hr class="my-4">
            
            <div class="text-start px-2">
                <div class="mb-2"><strong><i class="bi bi-telephone-fill text-muted me-2"></i>Telepon:</strong> <span class="text-muted">{{ $dosen->telepon ?? '-' }}</span></div>
                <div><strong><i class="bi bi-geo-alt-fill text-muted me-2"></i>Alamat:</strong> <span class="text-muted">{{ $dosen->alamat ?? '-' }}</span></div>
            </div>
        </div>
    </div>

    <!-- Schedule Card -->
    <div class="col-md-8 mb-4">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-calendar3 me-2 text-primary"></i>Jadwal Mengajar</h5>
                <span class="badge bg-info text-white">{{ $totalMengajar }} Kelas Aktif</span>
            </div>

            @if($jadwal->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-calendar-x display-3 text-muted mb-3"></i>
                    <p class="mb-0">Anda tidak memiliki jadwal mengajar semester ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Kuliah</th>
                                <th>Jam</th>
                                <th>Ruangan</th>
                                <th>Aksi</th>
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
                                    <td><i class="bi bi-door-closed me-1 text-muted"></i>{{ $j->ruangan }}</td>
                                    <td>
                                        <a href="{{ route('dosen.grades', $j->mata_kuliah_id) }}" class="btn btn-sm btn-custom-primary">
                                            <i class="bi bi-pencil-square me-1"></i>Input Nilai
                                        </a>
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
