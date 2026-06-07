@extends('layouts.app')

@section('title', 'Backup Database')
@section('page_title', 'Cadangan & Pemulihan Database')

@section('content')
<div class="row">
    <!-- Action Panel -->
    <div class="col-md-4 mb-4">
        <div class="glass-card text-center py-4 h-100">
            <div class="display-5 text-primary mb-3"><i class="bi bi-database-fill-gear"></i></div>
            <h5 class="fw-bold">Buat Cadangan Baru</h5>
            <p class="text-muted" style="font-size: 0.85rem;">
                Mengekspor skema dan seluruh baris data dari database <code>web_kemahasiswaan</code> menjadi file skrip SQL (.sql) untuk keperluan backup dan pemulihan data.
            </p>
            <form action="{{ route('admin.backups.create') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="btn btn-custom-primary w-100 py-2">
                    <i class="bi bi-cloud-arrow-down-fill me-1"></i>Ekspor Database (.sql)
                </button>
            </form>
            <div class="alert alert-info border-0 mt-4 text-start" style="border-radius: 10px; font-size: 0.8rem;">
                <i class="bi bi-shield-check-fill me-1 text-primary"></i>
                <strong>Keamanan Cadangan:</strong> File cadangan disimpan secara aman di direktori sistem lokal terproteksi dan tidak dapat diakses secara langsung dari luar (webroot).
            </div>
        </div>
    </div>

    <!-- Backups List -->
    <div class="col-md-8 mb-4">
        <div class="glass-card h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Daftar File Cadangan (SQL)</h5>

            @if(empty($backups))
                <div class="text-center text-muted py-5">
                    <i class="bi bi-database-dash display-3 text-muted mb-3"></i>
                    <p class="mb-0">Belum ada file cadangan database yang dibuat.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><i class="bi bi-filetype-sql text-primary me-2"></i>{{ $backup['name'] }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $backup['size'] }}</span></td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.backups.download', $backup['name']) }}" class="btn btn-sm btn-outline-success" style="border-radius: 8px;" title="Unduh File">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBackupModal{{ Str::slug(basename($backup['name'])) }}" style="border-radius: 8px;" title="Hapus File">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Backup Modal -->
                                <div class="modal fade" id="deleteBackupModal{{ Str::slug(basename($backup['name'])) }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0" style="border-radius: 18px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                Apakah Anda yakin ingin menghapus file cadangan <strong>{{ $backup['name'] }}</strong> secara permanen?
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <form action="{{ route('admin.backups.delete', $backup['name']) }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                                                    <button type="submit" class="btn btn-danger" style="border-radius: 10px;">Ya, Hapus Permanen</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
