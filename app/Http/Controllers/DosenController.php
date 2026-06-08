<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Nilai;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    private function getDosen()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return false; // Return false instead of abort(404)
        }
        return $dosen;
    }

    public function setupProfile(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|unique:dosen,nidn',
            'spesialisasi' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $user = Auth::user();

        Dosen::create([
            'user_id' => $user->id,
            'nidn' => $validated['nidn'],
            'nama' => $user->name, // Copy name from users table
            'spesialisasi' => $validated['spesialisasi'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'],
        ]);

        ActivityLogger::log('PROFILE_SETUP', "Dosen {$user->name} melengkapi profil dengan NIDN: {$validated['nidn']}");

        return redirect()->route('dashboard')->with('success', 'Profil Dosen Anda berhasil disimpan! Semua fitur sekarang tersedia.');
    }

    public function classes()
    {
        $dosen = $this->getDosen();
        if (!$dosen) {
            return redirect()->route('dashboard')->with('error', 'Profil Dosen Anda belum lengkap. Silakan hubungi Admin untuk melengkapi data dosen Anda.');
        }

        // Get unique courses from teaching schedule
        $classes = \App\Models\Jadwal::with('mataKuliah')
            ->where('dosen_id', $dosen->id)
            ->get()
            ->unique('mata_kuliah_id');

        return view('dosen.classes.index', compact('classes', 'dosen'));
    }

    public function studentGrades($courseId)
    {
        $dosen = $this->getDosen();
        if (!$dosen) {
            return redirect()->route('dashboard')->with('error', 'Profil Dosen Anda belum lengkap.');
        }
        $course = \App\Models\MataKuliah::findOrFail($courseId);

        // Verify lecturer teaches this course
        $isTeaching = \App\Models\Jadwal::where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $courseId)
            ->exists();

        if (!$isTeaching) {
            abort(403, 'Anda tidak mengajar mata kuliah ini.');
        }

        // Get student KRS enrollments for this course
        $studentsKrs = Krs::with(['mahasiswa', 'nilai'])
            ->where('mata_kuliah_id', $courseId)
            ->where('status_approval', 'approved')
            ->get();

        return view('dosen.classes.grades', compact('studentsKrs', 'course', 'dosen'));
    }

    public function storeGrade(Request $request, $krsId)
    {
        $dosen = $this->getDosen();
        if (!$dosen) {
            return redirect()->route('dashboard')->with('error', 'Profil Dosen Anda belum lengkap.');
        }
        $krs = Krs::with('mahasiswa', 'mataKuliah')->findOrFail($krsId);

        // Verify lecturer teaches this course
        $isTeaching = \App\Models\Jadwal::where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $krs->mata_kuliah_id)
            ->exists();

        if (!$isTeaching) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengisi nilai kelas ini.');
        }

        $validated = $request->validate([
            'nilai_angka' => 'required|integer|min:0|max:100',
        ]);

        $score = $validated['nilai_angka'];
        $gradeLetter = 'E';

        if ($score >= 85) {
            $gradeLetter = 'A';
        } elseif ($score >= 75) {
            $gradeLetter = 'B';
        } elseif ($score >= 60) {
            $gradeLetter = 'C';
        } elseif ($score >= 45) {
            $gradeLetter = 'D';
        }

        // Update or create Nilai record
        $nilai = Nilai::updateOrCreate(
            ['krs_id' => $krsId],
            [
                'nilai_angka' => $score,
                'nilai_huruf' => $gradeLetter,
                'dosen_id' => $dosen->id,
            ]
        );

        $mhs = $krs->mahasiswa;
        ActivityLogger::log('INPUT_GRADE', "Dosen {$dosen->nama} menginput nilai untuk mahasiswa: {$mhs->nama} (NIM: {$mhs->nim}), MK: {$krs->mataKuliah->nama_mk}, Nilai: {$score} ({$gradeLetter})");

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
