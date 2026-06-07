@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Administrasi & Keamanan')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="glass-card text-center py-4">
            <div class="display-6 text-primary mb-2"><i class="bi bi-people-fill"></i></div>
            <h5 class="text-muted mb-1">Total User</h5>
            <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="glass-card text-center py-4">
            <div class="display-6 text-success mb-2"><i class="bi bi-mortarboard-fill"></i></div>
            <h5 class="text-muted mb-1">Mahasiswa</h5>
            <h3 class="fw-bold mb-0">{{ $totalMahasiswa }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="glass-card text-center py-4">
            <div class="display-6 text-warning mb-2"><i class="bi bi-person-workspace"></i></div>
            <h5 class="text-muted mb-1">Dosen</h5>
            <h3 class="fw-bold mb-0">{{ $totalDosen }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="glass-card text-center py-4">
            <div class="display-6 text-info mb-2"><i class="bi bi-journal-bookmark-fill"></i></div>
            <h5 class="text-muted mb-1">Mata Kuliah</h5>
            <h3 class="fw-bold mb-0">{{ $totalMK }}</h3>
        </div>
    </div>
</div>

<div class="row">
    <!-- Security Events Log -->
    <div class="col-lg-6 mb-4">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-shield-fill-exclamation me-2"></i>Log Keamanan Sistem</h5>
                <a href="{{ route('admin.logs', ['action' => 'BRUTE_FORCE_ATTEMPT']) }}" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;">Lihat Semua</a>
            </div>
            
            @if($securityLogs->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                    <p class="mb-0">Tidak ada peringatan keamanan dalam 30 hari terakhir.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($securityLogs as $sLog)
                        <div class="list-group-item px-0 border-0 border-bottom py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge {{ $sLog->action == 'BRUTE_FORCE_ATTEMPT' ? 'bg-danger' : 'bg-warning' }} text-white">
                                    {{ $sLog->action }}
                                </span>
                                <small class="text-muted">{{ $sLog->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-dark" style="font-size: 0.9rem;">{{ $sLog->description }}</p>
                            <div class="d-flex gap-3 text-muted" style="font-size: 0.75rem;">
                                <span><i class="bi bi-laptop me-1"></i> IP: {{ $sLog->ip_address }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- General Activities Log -->
    <div class="col-lg-6 mb-4">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-list-task me-2"></i>Log Aktivitas Pengguna</h5>
                <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Lihat Semua</a>
            </div>

            @if($logs->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-clock-history display-4 mb-3"></i>
                    <p class="mb-0">Belum ada aktivitas tercatat.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($logs as $log)
                        <div class="list-group-item px-0 border-0 border-bottom py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    {{ $log->user ? $log->user->name : 'Sistem/Guest' }}
                                </span>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-muted" style="font-size: 0.85rem;">
                                <span class="badge bg-secondary me-1 text-uppercase">{{ $log->action }}</span>
                                {{ $log->description }}
                            </p>
                            <div class="d-flex gap-3 text-muted" style="font-size: 0.75rem;">
                                <span><i class="bi bi-laptop me-1"></i> IP: {{ $log->ip_address }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
