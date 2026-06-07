<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\MataKuliah;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    private function getMahasiswa()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            abort(404, 'Data Mahasiswa Anda tidak ditemukan.');
        }
        return $mahasiswa;
    }

    // ==========================================
    // KRS (KARTU RENCANA STUDI)
    // ==========================================
    public function krsList()
    {
        $mahasiswa = $this->getMahasiswa();

        $krs = Krs::with(['mataKuliah', 'nilai'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        $totalSks = $krs->where('status_approval', 'approved')->sum(function ($item) {
            return $item->mataKuliah->sks;
        });

        // Available courses that are not yet selected
        $selectedMkIds = $krs->pluck('mata_kuliah_id');
        $availableCourses = MataKuliah::whereNotIn('id', $selectedMkIds)->get();

        return view('mahasiswa.krs.index', compact('krs', 'totalSks', 'availableCourses', 'mahasiswa'));
    }

    public function krsStore(Request $request)
    {
        $mahasiswa = $this->getMahasiswa();

        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
        ]);

        $mk = MataKuliah::findOrFail($validated['mata_kuliah_id']);

        // Check if already selected
        $exists = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('mata_kuliah_id', $mk->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Mata kuliah ini sudah ada di KRS Anda.');
        }

        // Add to KRS
        Krs::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mata_kuliah_id' => $mk->id,
            'tahun_akademik' => '2025/2026', // Current academic year
            'semester' => 'Ganjil', // Current semester
            'status_approval' => 'approved', // Auto-approved for this simple SIAKAD demo!
        ]);

        ActivityLogger::log('ADD_KRS', "Mahasiswa {$mahasiswa->nama} mengajukan KRS mata kuliah: {$mk->nama_mk} ({$mk->kode_mk})");

        return back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
    }

    public function krsDelete($id)
    {
        $mahasiswa = $this->getMahasiswa();
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->findOrFail($id);

        $mk = $krs->mataKuliah;
        ActivityLogger::log('DELETE_KRS', "Mahasiswa {$mahasiswa->nama} membatalkan KRS mata kuliah: {$mk->nama_mk}");
        $krs->delete();

        return back()->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
    }

    // ==========================================
    // KHS (KARTU HASIL STUDI) / NILAI
    // ==========================================
    public function khsList()
    {
        $mahasiswa = $this->getMahasiswa();

        // Get KRS that are approved and have scores
        $khs = Krs::with(['mataKuliah', 'nilai.dosen'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status_approval', 'approved')
            ->get();

        // Calculate GPA (IPK/IPS)
        $totalSks = 0;
        $totalPoints = 0;

        foreach ($khs as $item) {
            if ($item->nilai) {
                $sks = $item->mataKuliah->sks;
                $totalSks += $sks;

                $points = 0;
                switch ($item->nilai->nilai_huruf) {
                    case 'A': $points = 4; break;
                    case 'B': $points = 3; break;
                    case 'C': $points = 2; break;
                    case 'D': $points = 1; break;
                    case 'E': $points = 0; break;
                }
                $totalPoints += ($points * $sks);
            }
        }

        $ips = $totalSks > 0 ? round($totalPoints / $totalSks, 2) : 0.00;

        return view('mahasiswa.khs.index', compact('khs', 'totalSks', 'ips', 'mahasiswa'));
    }

    public function khsPrint()
    {
        $mahasiswa = $this->getMahasiswa();

        $khs = Krs::with(['mataKuliah', 'nilai.dosen'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status_approval', 'approved')
            ->get();

        $totalSks = 0;
        $totalPoints = 0;

        foreach ($khs as $item) {
            if ($item->nilai) {
                $sks = $item->mataKuliah->sks;
                $totalSks += $sks;

                $points = 0;
                switch ($item->nilai->nilai_huruf) {
                    case 'A': $points = 4; break;
                    case 'B': $points = 3; break;
                    case 'C': $points = 2; break;
                    case 'D': $points = 1; break;
                    case 'E': $points = 0; break;
                }
                $totalPoints += ($points * $sks);
            }
        }

        $ips = $totalSks > 0 ? round($totalPoints / $totalSks, 2) : 0.00;

        ActivityLogger::log('PRINT_KHS', "Mahasiswa {$mahasiswa->nama} (NIM: {$mahasiswa->nim}) mencetak lembar KHS.");

        return view('mahasiswa.khs.print', compact('khs', 'totalSks', 'ips', 'mahasiswa'));
    }
}
