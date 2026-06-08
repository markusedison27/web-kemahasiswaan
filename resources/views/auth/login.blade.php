<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAWA Siber</title>
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
                <i class="bi bi-shield-lock-fill"></i>
                SIAKAD<span>SIBER</span>
            </div>
            <div class="subtitle">Sistem Informasi Akademik dengan Keamanan Siber</div>

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
                    <input type="email" name="email" id="email" class="form-control" placeholder="nama@siakad.com" required value="{{ old('email') }}">
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
