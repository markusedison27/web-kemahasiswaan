@extends('layouts.app')

@section('title', 'Mata Kuliah')
@section('page_title', 'Manajemen Mata Kuliah')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Mata Kuliah</h5>
        <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="bi bi-journal-plus me-1"></i>Tambah Mata Kuliah
        </button>
    </div>

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
                @forelse($courses as $mk)
                    <tr>
                        <td><code>{{ $mk->kode_mk }}</code></td>
                        <td><div class="fw-bold text-dark">{{ $mk->nama_mk }}</div></td>
                        <td><span class="badge bg-light text-dark border">{{ $mk->sks }} SKS</span></td>
                        <td>Semester {{ $mk->semester }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCourseModal{{ $mk->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal{{ $mk->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data mata kuliah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label">Kode Mata Kuliah</label>
                        <input type="text" name="kode_mk" class="form-control" placeholder="e.g. IF-301" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" class="form-control" placeholder="e.g. Keamanan Siber" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah SKS</label>
                        <input type="number" name="sks" class="form-control" placeholder="e.g. 3" required min="1" max="6" style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="number" name="semester" class="form-control" placeholder="e.g. 4" required min="1" max="8" style="border-radius: 10px;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Tambah Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($courses as $mk)
    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal{{ $mk->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Ubah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.courses.update', $mk->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">Kode Mata Kuliah</label>
                            <input type="text" name="kode_mk" class="form-control" value="{{ $mk->kode_mk }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Mata Kuliah</label>
                            <input type="text" name="nama_mk" class="form-control" value="{{ $mk->nama_mk }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SKS</label>
                            <input type="number" name="sks" class="form-control" value="{{ $mk->sks }}" required min="1" max="6" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ $mk->semester }}" required min="1" max="8" style="border-radius: 10px;">
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

    <!-- Delete Course Modal -->
    <div class="modal fade" id="deleteCourseModal{{ $mk->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    Apakah Anda yakin ingin menghapus mata kuliah <strong>{{ $mk->nama_mk }} ({{ $mk->kode_mk }})</strong>? Menghapus mata kuliah ini akan menghapus jadwal dan KRS yang terkait.
                </div>
                <div class="modal-footer border-0 pt-0">
                    <form action="{{ route('admin.courses.delete', $mk->id) }}" method="POST">
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
