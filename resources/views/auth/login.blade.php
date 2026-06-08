<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MYPOLBENG</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(56, 189, 248, 0.06) 0%, transparent 50%),
                        #f5f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #334155;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand-logo i {
            color: #2563eb;
            filter: drop-shadow(0 4px 6px rgba(37, 99, 235, 0.15));
        }

        .brand-logo span {
            color: #2563eb;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #475569;
            font-size: 0.9rem;
        }

        .form-control {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: #2563eb;
            color: #0f172a;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
        }

        .security-badge {
            background: rgba(37, 99, 235, 0.04);
            border: 1px solid rgba(37, 99, 235, 0.12);
            color: #1e40af;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 1.5rem;
        }

        .security-badge i {
            color: #2563eb;
        }
    </style>
</head>
<body>

    <div class="login-container">
        
        <div class="login-card">
            <div class="brand-logo">
                <img src="{{ asset('logo_polbeng.jpg') }}" alt="Logo POLBENG" style="height: 48px; width: auto; object-fit: contain; margin-bottom: 5px;">
                MY<span>POLBENG</span>
            </div>
            <div class="subtitle">Sistem Informasi Akademik Politeknik Negeri Bengkalis</div>

            <!-- Validation & Session Alerts -->
            @if($errors->any())
                <div class="alert alert-danger border-0 mb-3" style="border-radius: 12px; font-size: 0.85rem; background-color: #fee2e2; color: #991b1b;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 mb-3" style="border-radius: 12px; font-size: 0.85rem; background-color: #fee2e2; color: #991b1b;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success border-0 mb-3" style="border-radius: 12px; font-size: 0.85rem; background-color: #dcfce7; color: #166534;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="nama@polbeng.ac.id" required value="{{ old('email') }}">
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label mb-0">Kata Sandi</label>
                    </div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">Ingat Sesi Saya</label>
                </div>

                <button type="submit" class="btn btn-login">Masuk ke Sistem</button>
            </form>

            <div class="text-center my-3 text-muted position-relative">
                <span class="bg-white px-3" style="position: relative; z-index: 2; font-size: 0.85rem;">atau</span>
                <hr style="position: absolute; top: 50%; left: 0; right: 0; margin: 0; z-index: 1; border-color: #e2e8f0;">
            </div>

            <a href="{{ route('login.google') }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; padding: 0.75rem; font-weight: 500; border: 1px solid #cbd5e1; background-color: #ffffff; color: #334155; transition: all 0.2s ease; font-size: 0.9rem;" onmouseover="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#cbd5e1';">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22c-.22-.67-.35-1.37-.35-2.09z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="security-badge">
                <i class="bi bi-shield-fill-exclamation fs-5"></i>
                <div>
                    <strong>Proteksi Keamanan:</strong> Laju percobaan login dibatasi (Rate Limited) untuk mencegah serangan brute force.
                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
