@extends('layouts.app')

@section('title', 'Data Dosen')
@section('page_title', 'Manajemen Data Dosen')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Dosen Terdaftar</h5>
        <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addDosenModal" {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>
            <i class="bi bi-person-plus-fill me-1"></i>Tambah Dosen
        </button>
    </div>

    @if($availableUsers->isEmpty() && $dosen->isEmpty())
        <div class="alert alert-warning border-0" style="border-radius: 12px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            Sebelum menambahkan data dosen, Anda harus membuat user dengan role <strong>Dosen</strong> terlebih dahulu di menu <strong>Manajemen User</strong>.
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NIDN</th>
                    <th>Nama Dosen</th>
                    <th>Akun User</th>
                    <th>Spesialisasi</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dosen as $d)
                    <tr>
                        <td><code>{{ $d->nidn }}</code></td>
                        <td><div class="fw-bold">{{ $d->nama }}</div></td>
                        <td>{{ $d->user ? $d->user->email : 'Tidak terhubung' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $d->spesialisasi ?? '-' }}</span></td>
                        <td>{{ $d->telepon ?? '-' }}</td>
                        <td><div class="text-truncate" style="max-width: 180px;">{{ $d->alamat ?? '-' }}</div></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editDosenModal{{ $d->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDosenModal{{ $d->id }}" style="border-radius: 8px;">
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

<!-- Add Dosen Modal -->
@if(!$availableUsers->isEmpty())
    <div class="modal fade" id="addDosenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Data Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.dosen.store') }}" method="POST">
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
                            <label class="form-label">NIDN</label>
                            <input type="text" name="nidn" class="form-control" placeholder="Masukkan 10 digit NIDN" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Dosen</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama dosen beserta gelar" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bidang Keahlian (Spesialisasi)</label>
                            <select name="spesialisasi" class="form-select" style="border-radius: 10px;">
                                <option value="" selected>Pilih Bidang Keahlian (Opsional)...</option>
                                <option value="Cyber Security">Cyber Security (Keamanan Siber)</option>
                                <option value="Software Engineering">Software Engineering (Rekayasa Perangkat Lunak)</option>
                                <option value="Artificial Intelligence">Artificial Intelligence (Kecerdasan Buatan)</option>
                                <option value="Data Science">Data Science (Sains Data)</option>
                                <option value="Database Systems">Database Systems (Sistem Basis Data)</option>
                                <option value="Computer Networks">Computer Networks (Jaringan Komputer)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="e.g. 0812xxxxxxxx" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat dosen" style="border-radius: 10px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Tambah Dosen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@foreach($dosen as $d)
    <!-- Edit Dosen Modal -->
    <div class="modal fade" id="editDosenModal{{ $d->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Ubah Data Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.dosen.update', $d->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">NIDN</label>
                            <input type="text" name="nidn" class="form-control" value="{{ $d->nidn }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Dosen</label>
                            <input type="text" name="nama" class="form-control" value="{{ $d->nama }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bidang Keahlian (Spesialisasi)</label>
                            <select name="spesialisasi" class="form-select" style="border-radius: 10px;">
                                <option value="" {{ is_null($d->spesialisasi) ? 'selected' : '' }}>Belum ditentukan</option>
                                <option value="Cyber Security" {{ $d->spesialisasi == 'Cyber Security' ? 'selected' : '' }}>Cyber Security (Keamanan Siber)</option>
                                <option value="Software Engineering" {{ $d->spesialisasi == 'Software Engineering' ? 'selected' : '' }}>Software Engineering (Rekayasa Perangkat Lunak)</option>
                                <option value="Artificial Intelligence" {{ $d->spesialisasi == 'Artificial Intelligence' ? 'selected' : '' }}>Artificial Intelligence (Kecerdasan Buatan)</option>
                                <option value="Data Science" {{ $d->spesialisasi == 'Data Science' ? 'selected' : '' }}>Data Science (Sains Data)</option>
                                <option value="Database Systems" {{ $d->spesialisasi == 'Database Systems' ? 'selected' : '' }}>Database Systems (Sistem Basis Data)</option>
                                <option value="Computer Networks" {{ $d->spesialisasi == 'Computer Networks' ? 'selected' : '' }}>Computer Networks (Jaringan Komputer)</option>
                                @if($d->spesialisasi && !in_array($d->spesialisasi, ['Cyber Security', 'Software Engineering', 'Artificial Intelligence', 'Data Science', 'Database Systems', 'Computer Networks']))
                                    <option value="{{ $d->spesialisasi }}" selected>{{ $d->spesialisasi }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $d->telepon }}" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" style="border-radius: 10px;">{{ $d->alamat }}</textarea>
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

    <!-- Delete Dosen Modal -->
    <div class="modal fade" id="deleteDosenModal{{ $d->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    Apakah Anda yakin ingin menghapus data dosen <strong>{{ $d->nama }}</strong>? Tindakan ini hanya menghapus data akademiknya, user terkait tetap ada.
                </div>
                <div class="modal-footer border-0 pt-0">
                    <form action="{{ route('admin.dosen.delete', $d->id) }}" method="POST">
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
