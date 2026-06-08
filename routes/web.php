<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\BackupController;

// Welcome / Redirect route
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registrasi
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // OTP Verification
    Route::get('/login/otp', [AuthController::class, 'showOtp'])->name('login.otp');
    Route::post('/login/otp/verify', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
    Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // ADMIN ROLE ONLY ROUTES
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Users Management
        Route::get('/users', [AdminController::class, 'usersList'])->name('users');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::post('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::post('/users/{id}/delete', [AdminController::class, 'userDelete'])->name('users.delete');

        // Dosen Management
        Route::get('/dosen', [AdminController::class, 'dosenList'])->name('dosen');
        Route::post('/dosen', [AdminController::class, 'dosenStore'])->name('dosen.store');
        Route::post('/dosen/{id}', [AdminController::class, 'dosenUpdate'])->name('dosen.update');
        Route::post('/dosen/{id}/delete', [AdminController::class, 'dosenDelete'])->name('dosen.delete');

        // Mahasiswa Management
        Route::get('/mahasiswa', [AdminController::class, 'mahasiswaList'])->name('mahasiswa');
        Route::post('/mahasiswa', [AdminController::class, 'mahasiswaStore'])->name('mahasiswa.store');
        Route::post('/mahasiswa/{id}', [AdminController::class, 'mahasiswaUpdate'])->name('mahasiswa.update');
        Route::post('/mahasiswa/{id}/delete', [AdminController::class, 'mahasiswaDelete'])->name('mahasiswa.delete');

        // Courses Management
        Route::get('/courses', [AdminController::class, 'mkList'])->name('courses');
        Route::post('/courses', [AdminController::class, 'mkStore'])->name('courses.store');
        Route::post('/courses/{id}', [AdminController::class, 'mkUpdate'])->name('courses.update');
        Route::post('/courses/{id}/delete', [AdminController::class, 'mkDelete'])->name('courses.delete');

        // Schedule Management
        Route::get('/jadwal', [AdminController::class, 'jadwalList'])->name('jadwal');
        Route::post('/jadwal', [AdminController::class, 'jadwalStore'])->name('jadwal.store');
        Route::post('/jadwal/{id}', [AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
        Route::post('/jadwal/{id}/delete', [AdminController::class, 'jadwalDelete'])->name('jadwal.delete');

        // Security Activity Logs
        Route::get('/logs', [AdminController::class, 'logsList'])->name('logs');

        // Database Backup Management
        Route::get('/backups', [BackupController::class, 'index'])->name('backups');
        Route::post('/backups/create', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/{filename}/delete', [BackupController::class, 'delete'])->name('backups.delete');
    });

    // ==========================================
    // DOSEN ROLE ONLY ROUTES
    // ==========================================
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/classes', [DosenController::class, 'classes'])->name('classes');
        Route::get('/classes/{courseId}/grades', [DosenController::class, 'studentGrades'])->name('grades');
        Route::post('/grades/{krsId}', [DosenController::class, 'storeGrade'])->name('grades.store');
    });

    // ==========================================
    // MAHASISWA ROLE ONLY ROUTES
    // ==========================================
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // KRS
        Route::get('/krs', [MahasiswaController::class, 'krsList'])->name('krs');
        Route::post('/krs', [MahasiswaController::class, 'krsStore'])->name('krs.store');
        Route::post('/krs/{id}/delete', [MahasiswaController::class, 'krsDelete'])->name('krs.delete');

        // KHS
        Route::get('/khs', [MahasiswaController::class, 'khsList'])->name('khs');
        Route::get('/khs/print', [MahasiswaController::class, 'khsPrint'])->name('khs.print');
    });
});
