<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Google Accounts</title>
    <!-- Google Fonts (Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            color: #202124;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 1rem;
        }

        .chooser-card {
            width: 100%;
            max-width: 450px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            padding: 40px;
            margin-top: auto;
            margin-bottom: auto;
        }

        .google-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 16px;
        }

        .google-logo span {
            font-size: 24px;
            font-weight: 500;
            letter-spacing: -0.5px;
        }

        .title {
            font-size: 24px;
            font-weight: 400;
            text-align: center;
            color: #202124;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 16px;
            text-align: center;
            color: #5f6368;
            margin-bottom: 24px;
        }

        .account-list {
            border-top: 1px solid #dadce0;
            margin-bottom: 24px;
        }

        .account-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #dadce0;
            text-decoration: none;
            color: inherit;
            transition: background-color 0.15s ease;
            cursor: pointer;
        }

        .account-item:hover {
            background-color: #f7f8f8;
            margin-left: -40px;
            margin-right: -40px;
            padding-left: 40px;
            padding-right: 40px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
            margin-right: 12px;
            text-transform: uppercase;
        }

        /* Google Brand Colors for Avatars */
        .avatar-admin { background-color: #4285f4; }
        .avatar-dosen { background-color: #ea4335; }
        .avatar-mahasiswa { background-color: #fbbc05; }

        .account-details {
            flex-grow: 1;
            overflow: hidden;
        }

        .account-name {
            font-size: 14px;
            font-weight: 500;
            color: #3c4043;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .account-email {
            font-size: 12px;
            color: #5f6368;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .disclaimer {
            font-size: 12px;
            color: #5f6368;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .disclaimer a {
            color: #1a73e8;
            text-decoration: none;
        }

        .disclaimer a:hover {
            text-decoration: underline;
        }

        .footer {
            width: 100%;
            max-width: 450px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #5f6368;
            padding: 0 10px;
        }

        .footer-links a {
            color: #5f6368;
            text-decoration: none;
            margin-left: 16px;
        }

        .footer-links a:hover {
            color: #202124;
        }
    </style>
</head>
<body>

    <div class="chooser-card">
        
        <!-- Mock Google Logo -->
        <div class="google-logo">
            <svg width="74" height="24" viewBox="0 0 74 24">
                <g fill="none" fill-rule="evenodd">
                    <path fill="#EA4335" d="M37.76 19.34c-2.8 0-5.04-2.12-5.04-5.1s2.24-5.1 5.04-5.1c2.8 0 5.04 2.12 5.04 5.1s-2.24 5.1-5.04 5.1zm0-2.02c1.55 0 2.93-1.26 2.93-3.08s-1.38-3.08-2.93-3.08c-1.55 0-2.93 1.26-2.93 3.08s1.38 3.08 2.93 3.08z"/>
                    <path fill="#FBBC05" d="M26.74 19.34c-2.8 0-5.04-2.12-5.04-5.1s2.24-5.1 5.04-5.1c2.8 0 5.04 2.12 5.04 5.1s-2.24 5.1-5.04 5.1zm0-2.02c1.55 0 2.93-1.26 2.93-3.08s-1.38-3.08-2.93-3.08c-1.55 0-2.93 1.26-2.93 3.08s1.38 3.08 2.93 3.08z"/>
                    <path fill="#4285F4" d="M12.92 21.05c-3.78 0-7.05-3.23-7.05-7.05S9.14 6.95 12.92 6.95c2.07 0 3.54.8 4.66 1.87l-2.6 2.6c-.78-.75-1.8-1.35-3.06-1.35-2.27 0-4.12 1.83-4.12 4.43 0 2.6 1.85 4.43 4.12 4.43 1.6 0 2.5-.64 3.08-1.22.46-.46.77-1.12.89-2.01h-3.97V11.2h6.1c.06.32.1.69.1 1.09 0 1.7-.46 3.63-1.9 5.07-1.4 1.46-3.2 2.22-5.13 2.22z"/>
                    <path fill="#34A853" d="M54.5 5h1.96v13.88H54.5V5z"/>
                    <path fill="#4285F4" d="M49.2 19.34c-2.5 0-4.46-1.85-4.46-4.66 0-2.9 2.05-4.88 4.46-4.88 1.48 0 2.4.67 2.94 1.27l-1.6 1.6c-.36-.36-.88-.73-1.34-.73-.99 0-1.87.89-1.87 2.07s.87 2.07 1.87 2.07c.7 0 1.14-.36 1.48-.73.28-.28.46-.7.54-1.25H49.2V12.1h4.12c.03.18.05.4.05.64 0 1.25-.34 2.84-1.4 3.91-1 .99-2.03 1.55-2.77 1.55z"/>
                    <path fill="#FBBC05" d="M62.68 19.34c-1.45 0-2.65-.72-3.26-1.96l5.24-2.17-.18-.45c-.3-.82-1.2-2.42-3.35-2.42-2.12 0-3.9 1.67-3.9 4.66 0 2.8 1.76 4.66 4.28 4.66 2.03 0 3.2-1.24 3.69-1.96l-1.63-1.09c-.54.8-1.24 1.27-2.03 1.27m-.16-6.42c1.14 0 2.11.58 2.42 1.4l-3.86 1.6c0-1.74 1.26-3 1.44-3"/>
                </g>
            </svg>
        </div>

        <div class="title">Pilih akun</div>
        <div class="subtitle">untuk melanjutkan ke MYPOLBENG</div>

        <!-- Dynamic Account List -->
        <div class="account-list">
            @foreach($users as $u)
                <a href="{{ route('login.google.auth', ['id' => $u->id]) }}" class="account-item">
                    <div class="avatar avatar-{{ $u->role }}">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div class="account-details">
                        <div class="account-name">{{ $u->name }}</div>
                        <div class="account-email">{{ str_replace('siakad.com', 'polbeng.ac.id', $u->email) }}</div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
            @endforeach

            <!-- Mock Add Account -->
            <a href="#" class="account-item" onclick="alert('Silakan hubungi Administrator untuk menambahkan user baru.')">
                <div class="avatar bg-light text-secondary">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="account-details">
                    <div class="account-name" style="color: #1a73e8;">Gunakan akun lain</div>
                </div>
            </a>
        </div>

        <div class="disclaimer">
            Untuk melanjutkan, Google akan membagikan nama, alamat email, preferensi bahasa, dan foto profil Anda dengan MYPOLBENG. Sebelum menggunakan aplikasi ini, Anda dapat meninjau <a href="#">Kebijakan Privasi</a> dan <a href="#">Persyaratan Layanan</a> MYPOLBENG.
        </div>

    </div>

    <!-- Footer links -->
    <div class="footer">
        <div>Indonesia</div>
        <div class="footer-links">
            <a href="#">Bantuan</a>
            <a href="#">Privasi</a>
            <a href="#">Ketentuan</a>
        </div>
    </div>

</body>
</html>
