<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\ActivityLog;
use App\Models\Jadwal;
use App\Models\Krs;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalMahasiswa = Mahasiswa::count();
            $totalDosen = Dosen::count();
            $totalMK = MataKuliah::count();
            $totalUsers = User::count();
            
            // Latest logs
            $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();
            // Security warnings (like failed login attempts or backups)
            $securityLogs = ActivityLog::whereIn('action', ['BRUTE_FORCE_ATTEMPT', 'LOGIN_FAILED', 'BACKUP_DB'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('dashboard.admin', compact('totalMahasiswa', 'totalDosen', 'totalMK', 'totalUsers', 'logs', 'securityLogs'));
        }

        if ($user->isDosen()) {
            $dosen = $user->dosen;
            
            // Jika user dosen belum punya data profil dosen
            if (!$dosen) {
                // Ambil daftar spesialisasi unik dari database sebagai pilihan
                $spesialisasies = Dosen::select('spesialisasi')->whereNotNull('spesialisasi')->distinct()->pluck('spesialisasi');
                
                // Jika database kosong, berikan beberapa default
                if ($spesialisasies->isEmpty()) {
                    $spesialisasies = collect(['Rekayasa Perangkat Lunak', 'Kecerdasan Buatan', 'Jaringan Komputer', 'Keamanan Siber', 'Sistem Informasi', 'Ilmu Data', 'Lainnya']);
                }

                return view('dashboard.dosen', [
                    'dosen' => null,
                    'jadwal' => collect(),
                    'totalMengajar' => 0,
                    'needsProfile' => true,
                    'spesialisasies' => $spesialisasies,
                ]);
            }

            $jadwal = Jadwal::with('mataKuliah')
                ->where('dosen_id', $dosen->id)
                ->orderBy('hari')
                ->get();

            $totalMengajar = $jadwal->count();

            return view('dashboard.dosen', compact('dosen', 'jadwal', 'totalMengajar'));
        }

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;

            // Jika user mahasiswa belum punya data profil mahasiswa
            if (!$mahasiswa) {
                // Ambil daftar jurusan unik dari database sebagai pilihan
                $jurusans = Mahasiswa::select('jurusan')->whereNotNull('jurusan')->distinct()->pluck('jurusan');
                
                // Jika database masih kosong, berikan beberapa default Polbeng
                if ($jurusans->isEmpty()) {
                    $jurusans = collect(['Teknik Informatika', 'Sistem Informasi', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Administrasi Niaga', 'Bahasa Inggris', 'Kemaritiman']);
                }

                return view('dashboard.mahasiswa', [
                    'mahasiswa'  => null,
                    'totalSks'   => 0,
                    'currentKrs' => collect(),
                    'jadwal'     => collect(),
                    'needsProfile' => true,
                    'jurusans'   => $jurusans,
                ]);
            }

            // Current KRS
            $currentKrs = Krs::with('mataKuliah')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status_approval', 'approved')
                ->get();

            $totalSks = $currentKrs->sum(function ($item) {
                return $item->mataKuliah->sks;
            });

            // Jadwal based on KRS
            $mkIds = $currentKrs->pluck('mata_kuliah_id');
            $jadwal = Jadwal::with(['mataKuliah', 'dosen'])
                ->whereIn('mata_kuliah_id', $mkIds)
                ->orderBy('hari')
                ->get();

            return view('dashboard.mahasiswa', compact('mahasiswa', 'totalSks', 'currentKrs', 'jadwal'));
        }

        abort(403);
    }
}
