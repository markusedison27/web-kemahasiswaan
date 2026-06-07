@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('page_title', 'Manajemen Data Mahasiswa')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Mahasiswa Terdaftar</h5>
        <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal" {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>
            <i class="bi bi-person-plus-fill me-1"></i>Tambah Mahasiswa
        </button>
    </div>

    @if($availableUsers->isEmpty() && $mahasiswa->isEmpty())
        <div class="alert alert-warning border-0" style="border-radius: 12px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Sebelum menambahkan data mahasiswa, Anda harus membuat user dengan role <strong>Mahasiswa</strong> terlebih dahulu di menu <strong>Manajemen User</strong>.
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Akun User</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa as $m)
                    <tr>
                        <td><code>{{ $m->nim }}</code></td>
                        <td><div class="fw-bold">{{ $m->nama }}</div></td>
                        <td>{{ $m->user ? $m->user->email : 'Tidak terhubung' }}</td>
                        <td>{{ $m->jurusan }}</td>
                        <td>{{ $m->angkatan }}</td>
                        <td>{{ $m->telepon ?? '-' }}</td>
                        <td><div class="text-truncate" style="max-width: 150px;">{{ $m->alamat ?? '-' }}</div></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMahasiswaModal{{ $m->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMahasiswaModal{{ $m->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Mahasiswa Modal -->
@if(!$availableUsers->isEmpty())
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">Hubungkan Akun User</label>
                            <select name="user_id" class="form-select" required style="border-radius: 10px;">
                                <option value="" disabled selected>Pilih user yang belum terhubung...</option>
                                @foreach($availableUsers as $au)
                                    <option value="{{ $au->id }}">{{ $au->name }} ({{ $au->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Mahasiswa" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama mahasiswa" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select" required style="border-radius: 10px;">
                                <option value="" disabled selected>Pilih Jurusan...</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                <option value="Teknologi Informasi">Teknologi Informasi</option>
                                <option value="Sains Data">Sains Data</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Angkatan</label>
                            <input type="number" name="angkatan" class="form-control" placeholder="e.g. 2024" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="e.g. 0821xxxxxxxx" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap mahasiswa" style="border-radius: 10px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Tambah Mahasiswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@foreach($mahasiswa as $m)
    <!-- Edit Mahasiswa Modal -->
    <div class="modal fade" id="editMahasiswaModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Ubah Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.mahasiswa.update', $m->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim" class="form-control" value="{{ $m->nim }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Mahasiswa</label>
                            <input type="text" name="nama" class="form-control" value="{{ $m->nama }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select" required style="border-radius: 10px;">
                                <option value="Teknik Informatika" {{ $m->jurusan == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                <option value="Sistem Informasi" {{ $m->jurusan == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                <option value="Rekayasa Perangkat Lunak" {{ $m->jurusan == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Rekayasa Perangkat Lunak</option>
                                <option value="Teknologi Informasi" {{ $m->jurusan == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Sains Data" {{ $m->jurusan == 'Sains Data' ? 'selected' : '' }}>Sains Data</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Angkatan</label>
                            <input type="number" name="angkatan" class="form-control" value="{{ $m->angkatan }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $m->telepon }}" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" style="border-radius: 10px;">{{ $m->alamat }}</textarea>
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

    <!-- Delete Mahasiswa Modal -->
    <div class="modal fade" id="deleteMahasiswaModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    Apakah Anda yakin ingin menghapus data mahasiswa <strong>{{ $m->nama }}</strong>? Tindakan ini hanya menghapus data akademiknya, user terkait tetap ada.
                </div>
                <div class="modal-footer border-0 pt-0">
                    <form action="{{ route('admin.mahasiswa.delete', $m->id) }}" method="POST">
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
