@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('page_title', 'Audit Log Keamanan & Aktivitas')

@section('content')
<!-- Search & Filter Card -->
<div class="glass-card mb-4">
    <form action="{{ route('admin.logs') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-4">
            <label class="form-label text-muted mb-1" style="font-size: 0.85rem;">Cari Aktivitas / IP / Nama</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari..." value="{{ request('search') }}" style="border-radius: 0 10px 10px 0;">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted mb-1" style="font-size: 0.85rem;">Filter Aksi (Action)</label>
            <select name="action" class="form-select" style="border-radius: 10px;">
                <option value="">Semua Aksi...</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mt-4 pt-2">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-custom-primary w-100">
                    <i class="bi bi-funnel-fill me-1"></i>Filter
                </button>
                @if(request()->has('search') || request()->has('action'))
                    <a href="{{ route('admin.logs') }}" class="btn btn-light border w-100" style="border-radius: 10px;">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Waktu (Timestamp)</th>
                    <th>Aksi (Action)</th>
                    <th>Deskripsi Aktivitas</th>
                    <th>Oleh Pengguna</th>
                    <th>Alamat IP</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $log->created_at->format('d M Y H:i:s') }}</div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @php
                                $badgeClass = 'bg-secondary';
                                if (in_array($log->action, ['BRUTE_FORCE_ATTEMPT', 'LOGIN_FAILED', 'BACKUP_FAILED'])) {
                                    $badgeClass = 'bg-danger';
                                } elseif (in_array($log->action, ['LOGIN_SUCCESS', 'BACKUP_DB'])) {
                                    $badgeClass = 'bg-success';
                                } elseif (str_contains($log->action, 'CREATE')) {
                                    $badgeClass = 'bg-primary';
                                } elseif (str_contains($log->action, 'DELETE')) {
                                    $badgeClass = 'bg-warning text-dark';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase" style="letter-spacing: 0.5px;">{{ $log->action }}</span>
                        </td>
                        <td><div style="max-width: 320px; word-break: break-word;">{{ $log->description }}</div></td>
                        <td>
                            @if($log->user)
                                <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                                <span class="badge badge-role role-{{ $log->user->role }}">{{ $log->user->role }}</span>
                            @else
                                <span class="text-muted italic">Guest / Sistem</span>
                            @endif
                        </td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>
                            <div class="text-truncate text-muted" style="max-width: 140px;" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-clock-history display-4 mb-3"></i>
                            <p class="mb-0">Tidak ada log aktivitas ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    @if($logs->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {!! $logs->links('pagination::bootstrap-5') !!}
        </div>
    @endif
</div>
@endsection
