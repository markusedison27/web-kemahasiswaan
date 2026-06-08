<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(37,99,235,0.05) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(56,189,248,0.06) 0%, transparent 50%),
                        #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            color: #334155;
        }

        .register-container { width: 100%; max-width: 480px; }

        .register-card {
            background: #ffffff;
            border: 1px solid rgba(226,232,240,0.8);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.03);
        }

        .brand-logo {
            font-size: 1.9rem;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .brand-logo i { color: #2563eb; }
        .brand-logo span { color: #2563eb; }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            font-size: 0.875rem;
        }

        .form-control {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 12px;
            padding: 0.72rem 1rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
        }

        .form-control::placeholder { color: #94a3b8; }

        .input-group-icon {
            position: relative;
        }
        .input-group-icon .form-control { padding-right: 2.8rem; }
        .input-group-icon .icon-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .input-group-icon .icon-btn:hover { color: #2563eb; }

        /* Password Strength */
        .password-strength {
            margin-top: 6px;
        }
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
            margin-bottom: 4px;
        }
        .strength-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease, background 0.3s ease;
            width: 0%;
        }
        .strength-text {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .btn-register {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37,99,235,0.2);
            margin-top: 0.5rem;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: 0.82rem;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            border-radius: 20px;
            padding: 0.3rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .invalid-feedback { font-size: 0.8rem; }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 0.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: #334155;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 0;
        }
        .btn-google:hover {
            border-color: #4285F4;
            background: #f8faff;
            color: #1a1a2e;
            box-shadow: 0 4px 12px rgba(66,133,244,0.15);
            transform: translateY(-1px);
        }
        .btn-google img { width: 20px; height: 20px; }
    </style>
</head>
<body>
<div class="register-container">
    <div class="register-card">

        {{-- Logo --}}
        <div class="brand-logo">
            <img src="{{ asset('logo_polbeng.jpg') }}" alt="Logo" style="height:42px;width:auto;object-fit:contain;">
            MY<span>POLBENG</span>
        </div>
        <div class="subtitle">Buat akun baru untuk mengakses sistem</div>

        {{-- Alert --}}
        @if($errors->any())
            <div class="alert border-0 mb-3" style="border-radius:12px;font-size:0.85rem;background:#fee2e2;color:#991b1b;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert border-0 mb-3" style="border-radius:12px;font-size:0.85rem;background:#dcfce7;color:#166534;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Role Info --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span style="font-size:0.85rem;color:#64748b;">Akun akan terdaftar sebagai:</span>
            <span class="role-badge"><i class="bi bi-person-badge"></i> Mahasiswa</span>
        </div>

        {{-- Form --}}
        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Masukkan nama lengkap"
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@polbeng.ac.id"
                       value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group-icon">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter" required>
                    <button type="button" class="icon-btn" id="togglePass">
                        <i class="bi bi-eye-slash" id="togglePassIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Strength indicator --}}
                <div class="password-strength">
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <span class="strength-text" id="strengthText" style="color:#94a3b8;">Masukkan password</span>
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <div class="input-group-icon">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"
                           placeholder="Ulangi kata sandi" required>
                    <button type="button" class="icon-btn" id="togglePassConfirm">
                        <i class="bi bi-eye-slash" id="togglePassConfirmIcon"></i>
                    </button>
                </div>
                <div id="matchMsg" style="font-size:0.78rem;margin-top:4px;display:none;"></div>
            </div>

            <button type="submit" class="btn btn-register" id="btnRegister">
                <i class="bi bi-person-plus me-2"></i>Buat Akun &amp; Verifikasi via Email
            </button>
        </form>

        <div class="divider">atau daftar dengan</div>

        <a href="{{ route('auth.google') }}" class="btn-google" id="btnGoogleRegister">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
            Daftar dengan Google
        </a>

        <div class="divider">sudah punya akun?</div>

        <a href="{{ route('login') }}" class="btn w-100" style="border-radius:12px;padding:0.75rem;font-weight:600;border:1px solid #cbd5e1;color:#334155;font-size:0.9rem;background:#fff;transition:all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
        </a>

    </div>
</div>

<script>
    // Toggle password visibility
    function toggleVisibility(btnId, iconId, inputId) {
        document.getElementById(btnId).addEventListener('click', () => {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        });
    }
    toggleVisibility('togglePass', 'togglePassIcon', 'password');
    toggleVisibility('togglePassConfirm', 'togglePassConfirmIcon', 'password_confirmation');

    // Password strength
    document.getElementById('password').addEventListener('input', function () {
        const val  = this.value;
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');

        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '#e2e8f0', label: 'Masukkan password',   textColor: '#94a3b8' },
            { pct: '25%',  color: '#ef4444', label: 'Sangat lemah',        textColor: '#ef4444' },
            { pct: '50%',  color: '#f97316', label: 'Lemah',               textColor: '#f97316' },
            { pct: '75%',  color: '#eab308', label: 'Cukup',               textColor: '#ca8a04' },
            { pct: '100%', color: '#22c55e', label: 'Kuat 💪',             textColor: '#16a34a' },
        ];

        const lvl = val.length === 0 ? levels[0] : levels[score];
        fill.style.width      = lvl.pct;
        fill.style.background = lvl.color;
        text.textContent      = lvl.label;
        text.style.color      = lvl.textColor;
    });

    // Password match check
    const passConfirm = document.getElementById('password_confirmation');
    const matchMsg    = document.getElementById('matchMsg');

    passConfirm.addEventListener('input', () => {
        const pass = document.getElementById('password').value;
        if (passConfirm.value === '') {
            matchMsg.style.display = 'none';
            return;
        }
        matchMsg.style.display = 'block';
        if (pass === passConfirm.value) {
            matchMsg.textContent  = '✓ Password cocok';
            matchMsg.style.color  = '#16a34a';
            passConfirm.style.borderColor = '#22c55e';
        } else {
            matchMsg.textContent  = '✗ Password tidak cocok';
            matchMsg.style.color  = '#ef4444';
            passConfirm.style.borderColor = '#ef4444';
        }
    });
</script>

</body>
</html>
