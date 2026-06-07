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
            if (!$dosen) {
                abort(404, 'Data Dosen tidak ditemukan.');
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
            if (!$mahasiswa) {
                abort(404, 'Data Mahasiswa tidak ditemukan.');
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
