@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('page_title', 'Manajemen Jadwal Kuliah')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Jadwal Kuliah</h5>
        <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addJadwalModal" {{ $courses->isEmpty() || $dosen->isEmpty() ? 'disabled' : '' }}>
            <i class="bi bi-calendar-plus me-1"></i>Tambah Jadwal
        </button>
    </div>

    @if($courses->isEmpty() || $dosen->isEmpty())
        <div class="alert alert-warning border-0" style="border-radius: 12px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Pastikan Anda sudah memiliki data <strong>Mata Kuliah</strong> dan <strong>Dosen</strong> terdaftar sebelum menambahkan jadwal kuliah.
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th>Jam Kuliah</th>
                    <th>Ruangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $j)
                    <tr>
                        <td class="fw-bold text-primary">{{ $j->hari }}</td>
                        <td>
                            <div class="fw-bold">{{ $j->mataKuliah->nama_mk }}</div>
                            <small class="text-muted">{{ $j->mataKuliah->kode_mk }} ({{ $j->mataKuliah->sks }} SKS)</small>
                        </td>
                        <td>{{ $j->dosen->nama }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </td>
                        <td><i class="bi bi-door-closed me-1 text-muted"></i>{{ $j->ruangan }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editJadwalModal{{ $j->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteJadwalModal{{ $j->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data jadwal kuliah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Jadwal Modal -->
@if(!$courses->isEmpty() && !$dosen->isEmpty())
    <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Jadwal Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">Mata Kuliah</label>
                            <select name="mata_kuliah_id" class="form-select" required style="border-radius: 10px;">
                                <option value="" disabled selected>Pilih Mata Kuliah...</option>
                                @foreach($courses as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosen Pengampu</label>
                            <select name="dosen_id" class="form-select" required style="border-radius: 10px;">
                                <option value="" disabled selected>Pilih Dosen...</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required style="border-radius: 10px;">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" required style="border-radius: 10px;">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" required style="border-radius: 10px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control" placeholder="e.g. Ruang Teori R-202" required style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Tambah Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@foreach($jadwal as $j)
    <!-- Edit Jadwal Modal -->
    <div class="modal fade" id="editJadwalModal{{ $j->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Ubah Jadwal Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.jadwal.update', $j->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">Mata Kuliah</label>
                            <select name="mata_kuliah_id" class="form-select" required style="border-radius: 10px;">
                                @foreach($courses as $mk)
                                    <option value="{{ $mk->id }}" {{ $j->mata_kuliah_id == $mk->id ? 'selected' : '' }}>
                                        {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosen Pengampu</label>
                            <select name="dosen_id" class="form-select" required style="border-radius: 10px;">
                                @foreach($dosen as $d)
                                    <option value="{{ $d->id }}" {{ $j->dosen_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required style="border-radius: 10px;">
                                <option value="Senin" {{ $j->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ $j->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ $j->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ $j->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ $j->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ $j->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" value="{{ substr($j->jam_mulai, 0, 5) }}" required style="border-radius: 10px;">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control" value="{{ substr($j->jam_selesai, 0, 5) }}" required style="border-radius: 10px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control" value="{{ $j->ruangan }}" required style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Jadwal Modal -->
    <div class="modal fade" id="deleteJadwalModal{{ $j->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    Apakah Anda yakin ingin menghapus jadwal untuk mata kuliah <strong>{{ $j->mataKuliah->nama_mk }}</strong>?
                </div>
                <div class="modal-footer border-0 pt-0">
                    <form action="{{ route('admin.jadwal.delete', $j->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-danger" style="border-radius: 10px;">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
