<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\ActivityLog;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. MANAJEMEN USER
    // ==========================================
    public function usersList()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        ActivityLogger::log('CREATE_USER', "Membuat user baru: {$user->name} ({$user->email}) dengan role: {$user->role}");

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLogger::log('UPDATE_USER', "Mengubah data user: {$user->name} ({$user->email})");

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        ActivityLogger::log('DELETE_USER', "Menghapus user: {$user->name} ({$user->email})");
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // ==========================================
    // 2. MANAJEMEN DOSEN
    // ==========================================
    public function dosenList()
    {
        $dosen = Dosen::with('user')->orderBy('nama')->get();
        // Users with role 'dosen' who don't have associated Dosen record yet
        $availableUsers = User::where('role', 'dosen')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dosen')
                    ->whereColumn('dosen.user_id', 'users.id');
            })->get();

        return view('admin.dosen.index', compact('dosen', 'availableUsers'));
    }

    public function dosenStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:dosen,user_id',
            'nidn' => 'required|string|max:20|unique:dosen,nidn',
            'nama' => 'required|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $dosen = Dosen::create($validated);
        ActivityLogger::log('CREATE_DOSEN', "Menambahkan data dosen: {$dosen->nama} (NIDN: {$dosen->nidn})");

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen berhasil ditambahkan.');
    }

    public function dosenUpdate(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $validated = $request->validate([
            'nidn' => 'required|string|max:20|unique:dosen,nidn,' . $id,
            'nama' => 'required|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $dosen->update($validated);
        ActivityLogger::log('UPDATE_DOSEN', "Memperbarui data dosen: {$dosen->nama} (NIDN: {$dosen->nidn})");

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen berhasil diperbarui.');
    }

    public function dosenDelete($id)
    {
        $dosen = Dosen::findOrFail($id);
        ActivityLogger::log('DELETE_DOSEN', "Menghapus data dosen: {$dosen->nama} (NIDN: {$dosen->nidn})");
        $dosen->delete();

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen berhasil dihapus.');
    }

    // ==========================================
    // 3. MANAJEMEN MAHASISWA
    // ==========================================
    public function mahasiswaList()
    {
        $mahasiswa = Mahasiswa::with('user')->orderBy('nama')->get();
        // Users with role 'mahasiswa' who don't have associated Mahasiswa record yet
        $availableUsers = User::where('role', 'mahasiswa')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('mahasiswa')
                    ->whereColumn('mahasiswa.user_id', 'users.id');
            })->get();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'availableUsers'));
    }

    public function mahasiswaStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:mahasiswa,user_id',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|integer',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $mahasiswa = Mahasiswa::create($validated);
        ActivityLogger::log('CREATE_MAHASISWA', "Menambahkan data mahasiswa: {$mahasiswa->nama} (NIM: {$mahasiswa->nim})");

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa berhasil ditambahkan.');
    }

    public function mahasiswaUpdate(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $id,
            'nama' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|integer',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $mahasiswa->update($validated);
        ActivityLogger::log('UPDATE_MAHASISWA', "Memperbarui data mahasiswa: {$mahasiswa->nama} (NIM: {$mahasiswa->nim})");

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function mahasiswaDelete($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        ActivityLogger::log('DELETE_MAHASISWA', "Menghapus data mahasiswa: {$mahasiswa->nama} (NIM: {$mahasiswa->nim})");
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    // ==========================================
    // 4. MANAJEMEN MATA KULIAH
    // ==========================================
    public function mkList()
    {
        $courses = MataKuliah::orderBy('semester')->orderBy('kode_mk')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function mkStore(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $mk = MataKuliah::create($validated);
        ActivityLogger::log('CREATE_MK', "Menambahkan mata kuliah: {$mk->nama_mk} ({$mk->kode_mk})");

        return redirect()->route('admin.courses')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function mkUpdate(Request $request, $id)
    {
        $mk = MataKuliah::findOrFail($id);
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,' . $id,
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $mk->update($validated);
        ActivityLogger::log('UPDATE_MK', "Memperbarui mata kuliah: {$mk->nama_mk} ({$mk->kode_mk})");

        return redirect()->route('admin.courses')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function mkDelete($id)
    {
        $mk = MataKuliah::findOrFail($id);
        ActivityLogger::log('DELETE_MK', "Menghapus mata kuliah: {$mk->nama_mk} ({$mk->kode_mk})");
        $mk->delete();

        return redirect()->route('admin.courses')->with('success', 'Mata Kuliah berhasil dihapus.');
    }

    // ==========================================
    // 5. MANAJEMEN JADWAL
    // ==========================================
    public function jadwalList()
    {
        $jadwal = Jadwal::with(['mataKuliah', 'dosen'])->get();
        $courses = MataKuliah::orderBy('nama_mk')->get();
        $dosen = Dosen::orderBy('nama')->get();
        return view('admin.jadwal.index', compact('jadwal', 'courses', 'dosen'));
    }

    public function jadwalStore(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'hari' => 'required|string|max:15',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string|max:50',
        ]);

        $j = Jadwal::create($validated);
        $jLoad = Jadwal::with(['mataKuliah', 'dosen'])->find($j->id);
        ActivityLogger::log('CREATE_JADWAL', "Menambahkan jadwal: {$jLoad->mataKuliah->nama_mk} - {$jLoad->dosen->nama} pada {$jLoad->hari} ({$jLoad->jam_mulai} - {$jLoad->jam_selesai})");

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $j = Jadwal::findOrFail($id);
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'hari' => 'required|string|max:15',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string|max:50',
        ]);

        $j->update($validated);
        $jLoad = Jadwal::with(['mataKuliah', 'dosen'])->find($j->id);
        ActivityLogger::log('UPDATE_JADWAL', "Memperbarui jadwal: {$jLoad->mataKuliah->nama_mk} - {$jLoad->dosen->nama} pada {$jLoad->hari} ({$jLoad->jam_mulai} - {$jLoad->jam_selesai})");

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function jadwalDelete($id)
    {
        $j = Jadwal::findOrFail($id);
        $jLoad = Jadwal::with(['mataKuliah'])->find($id);
        ActivityLogger::log('DELETE_JADWAL', "Menghapus jadwal untuk mata kuliah: {$jLoad->mataKuliah->nama_mk}");
        $j->delete();

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    // ==========================================
    // 6. LOG AKTIVITAS (SECURITY LOG VIEWER)
    // ==========================================
    public function logsList(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(30)->withQueryString();
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.logs.index', compact('logs', 'actions'));
    }
}
