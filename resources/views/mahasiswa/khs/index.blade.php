@extends('layouts.app')

@section('title', 'KHS / Nilai')
@section('page_title', 'Kartu Hasil Studi (KHS)')

@section('content')
<div class="glass-card mb-4 bg-primary bg-opacity-10 border-0">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="fw-bold text-primary mb-1">Rangkuman Prestasi Akademik</h5>
            <p class="text-muted mb-0">Jurusan: {{ $mahasiswa->jurusan }} | Angkatan: {{ $mahasiswa->angkatan }} | NIM: <code>{{ $mahasiswa->nim }}</code></p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="d-inline-block text-start me-4">
                <small class="text-muted d-block">IP Semester (IPS)</small>
                <h3 class="fw-bold text-primary mb-0">{{ number_format($ips, 2) }}</h3>
            </div>
            <div class="d-inline-block text-start">
                <small class="text-muted d-block">Total SKS Dinilai</small>
                <h3 class="fw-bold text-dark mb-0">{{ $totalSks }} SKS</h3>
            </div>
        </div>
    </div>
</div>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-award-fill text-primary me-2"></i>Nilai Akademik Semester Ini</h5>
        <a href="{{ route('mahasiswa.khs.print') }}" target="_blank" class="btn btn-custom-primary">
            <i class="bi bi-printer-fill me-1"></i>Cetak KHS
        </a>
    </div>

    @if($khs->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-emoji-neutral display-3 text-muted mb-3"></i>
            <p class="mb-0">Belum ada mata kuliah yang disetujui atau nilai yang diinput semester ini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen Pengampu</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($khs as $item)
                        <tr>
                            <td><code>{{ $item->mataKuliah->kode_mk }}</code></td>
                            <td><div class="fw-bold text-dark">{{ $item->mataKuliah->nama_mk }}</div></td>
                            <td><span class="badge bg-light text-dark border">{{ $item->mataKuliah->sks }} SKS</span></td>
                            <td>{{ $item->nilai && $item->nilai->dosen ? $item->nilai->dosen->nama : 'Belum dinilai' }}</td>
                            <td>
                                @if($item->nilai)
                                    <span class="fw-bold">{{ $item->nilai->nilai_angka }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->nilai)
                                    @php
                                        $badgeColor = 'bg-danger';
                                        if ($item->nilai->nilai_huruf == 'A') $badgeColor = 'bg-success';
                                        elseif ($item->nilai->nilai_huruf == 'B') $badgeColor = 'bg-primary';
                                        elseif ($item->nilai->nilai_huruf == 'C') $badgeColor = 'bg-info text-dark';
                                        elseif ($item->nilai->nilai_huruf == 'D') $badgeColor = 'bg-warning text-dark';
                                    @endphp
                                    <span class="badge {{ $badgeColor }} px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                                        {{ $item->nilai->nilai_huruf }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->nilai)
                                    @php
                                        $pt = 0;
                                        switch($item->nilai->nilai_huruf) {
                                            case 'A': $pt = 4; break;
                                            case 'B': $pt = 3; break;
                                            case 'C': $pt = 2; break;
                                            case 'D': $pt = 1; break;
                                            case 'E': $pt = 0; break;
                                        }
                                    @endphp
                                    <span class="fw-bold">{{ $pt * $item->mataKuliah->sks }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
