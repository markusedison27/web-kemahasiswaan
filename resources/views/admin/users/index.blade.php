@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User Pengguna')

@section('content')
<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Daftar Pengguna Sistem</h5>
        <button class="btn btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus-fill me-1"></i>Tambah User
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Hak Akses (Role)</th>
                    <th>Status Akun</th>
                    <th>Terdaftar Pada</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </td>
                        <td><code>{{ $user->email }}</code></td>
                        <td>
                            <span class="badge badge-role role-{{ $user->role }}">{{ $user->role }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $user->status == 'active' ? 'success' : 'danger' }} px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                                {{ $user->status == 'active' ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" style="border-radius: 8px;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}" style="border-radius: 8px;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@siakad.com" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required style="border-radius: 10px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Akses</label>
                        <select name="role" class="form-select" required style="border-radius: 10px;">
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Keaktifan</label>
                        <select name="status" class="form-select" required style="border-radius: 10px;">
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($users as $user)
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Ubah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Baru (kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Akses</label>
                            <select name="role" class="form-select" required style="border-radius: 10px;">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Keaktifan</label>
                            <select name="status" class="form-select" required style="border-radius: 10px;">
                                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
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

    <!-- Delete User Modal -->
    @if($user->id !== Auth::id())
        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0" style="border-radius: 18px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn btn-danger" style="border-radius: 10px;">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
