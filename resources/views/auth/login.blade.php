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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #f8fafc;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand-logo i {
            color: #38bdf8;
            filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.4));
        }

        .brand-logo span {
            color: #38bdf8;
        }

        .subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #38bdf8;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
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
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
        }

        .security-badge {
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 1.5rem;
        }


    </style>
</head>
<body>

    <div class="login-container">
        
        <div class="login-card">
            <div class="brand-logo">
                <i class="bi bi-shield-fill-check"></i>
                SIAKAD<span>SIBER</span>
            </div>
            <div class="subtitle">Sistem Informasi Akademik dengan Keamanan Siber</div>

            <!-- Validation & Session Alerts -->
            @if($errors->any())
                <div class="alert alert-danger border-0 text-white bg-danger bg-opacity-75" style="border-radius: 10px; font-size: 0.85rem;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 text-white bg-danger bg-opacity-75" style="border-radius: 10px; font-size: 0.85rem;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success border-0 text-white bg-success bg-opacity-75" style="border-radius: 10px; font-size: 0.85rem;">
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
