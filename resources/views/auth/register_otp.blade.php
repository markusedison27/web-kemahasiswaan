<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Registrasi - {{ config('app.name') }}</title>
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
            padding: 1.5rem;
        }

        .otp-card {
            background: #ffffff;
            border: 1px solid rgba(226,232,240,0.8);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
        }

        .otp-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin-bottom: 0.4rem;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .email-highlight {
            font-weight: 600;
            color: #2563eb;
        }

        /* OTP Input Group */
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .otp-box {
            width: 52px;
            height: 60px;
            border: 2px solid #cbd5e1;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .otp-box:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        .otp-box.filled {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .otp-box.is-invalid {
            border-color: #ef4444 !important;
            background: #fef2f2;
        }

        .btn-verify {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 12px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37,99,235,0.2);
        }

        .btn-verify:hover {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.3);
        }

        .btn-verify:disabled {
            opacity: 0.6;
            transform: none;
        }

        .timer-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .timer-badge #countdown {
            font-weight: 700;
            color: #2563eb;
            font-variant-numeric: tabular-nums;
        }

        .timer-badge.expired #countdown {
            color: #ef4444;
        }

        .resend-link {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
            display: none;
        }

        .resend-link:hover { text-decoration: underline; }
        .resend-link.visible { display: inline; }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #64748b;
            font-size: 0.85rem;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: #2563eb; }
    </style>
</head>
<body>

<div class="otp-card">

    <div class="otp-icon">📝</div>

    <div class="title">Verifikasi Registrasi</div>
    <div class="subtitle">
        Kode OTP 6 digit telah dikirim ke email:<br>
        <span class="email-highlight">{{ session('register_otp_pending_email') }}</span>
    </div>

    {{-- Alert errors --}}
    @if($errors->any())
        <div class="alert border-0 mb-3" style="border-radius:12px;font-size:0.85rem;background:#fee2e2;color:#991b1b;">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert border-0 mb-3" style="border-radius:12px;font-size:0.85rem;background:#dcfce7;color:#166534;">
            {{ session('success') }}
        </div>
    @endif

    {{-- OTP Form --}}
    <form action="{{ route('register.otp.verify') }}" method="POST" id="otpForm">
        @csrf

        {{-- Hidden input yang dikirim --}}
        <input type="hidden" name="otp" id="otpHidden">

        {{-- 6 kotak OTP --}}
        <div class="otp-inputs">
            @for($i = 1; $i <= 6; $i++)
                <input type="text" class="otp-box {{ $errors->any() ? 'is-invalid' : '' }}"
                       maxlength="1" pattern="\d" inputmode="numeric"
                       id="otp{{ $i }}" autocomplete="off">
            @endfor
        </div>

        {{-- Timer --}}
        <div class="timer-badge" id="timerBadge">
            <i class="bi bi-clock"></i>
            OTP berlaku selama <span id="countdown">15:00</span>
        </div>

        <button type="submit" class="btn btn-verify" id="btnVerify">
            <i class="bi bi-shield-check me-2"></i>Verifikasi & Aktifkan Akun
        </button>
    </form>

    {{-- Resend --}}
    <div class="text-center mt-3">
        <span style="font-size:0.85rem;color:#64748b;">Tidak menerima kode?</span>
        <form action="{{ route('register.otp.resend') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="resend-link visible" id="resendBtn" style="background:none;border:none;padding:0;cursor:pointer;">
                Kirim ulang OTP
            </button>
        </form>
    </div>

    <a href="{{ route('register') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Registrasi
    </a>
</div>

<script>
    // ── Auto-focus & auto-move OTP boxes ──
    const boxes = document.querySelectorAll('.otp-box');
    const hidden = document.getElementById('otpHidden');
    const form   = document.getElementById('otpForm');

    boxes.forEach((box, idx) => {
        box.addEventListener('input', (e) => {
            const val = e.target.value.replace(/\D/g, '');
            box.value = val;
            box.classList.toggle('filled', val !== '');

            if (val && idx < 5) boxes[idx + 1].focus();

            // Update hidden input
            hidden.value = Array.from(boxes).map(b => b.value).join('');

            // Auto-submit jika semua terisi
            if (hidden.value.length === 6) {
                form.submit();
            }
        });

        box.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !box.value && idx > 0) {
                boxes[idx - 1].focus();
                boxes[idx - 1].value = '';
                boxes[idx - 1].classList.remove('filled');
            }
            // Allow paste
            if (e.key === 'v' && (e.ctrlKey || e.metaKey)) return;
        });

        box.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            pasted.split('').forEach((char, i) => {
                if (boxes[i]) {
                    boxes[i].value = char;
                    boxes[i].classList.add('filled');
                }
            });
            hidden.value = pasted;
            if (pasted.length === 6) form.submit();
        });
    });

    // Focus kotak pertama
    boxes[0].focus();

    // ── Countdown Timer (15 menit) ──
    let seconds = 900;
    const countdownEl = document.getElementById('countdown');
    const timerBadge  = document.getElementById('timerBadge');
    const btnVerify   = document.getElementById('btnVerify');

    const timer = setInterval(() => {
        seconds--;
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        countdownEl.textContent = `${m}:${s}`;

        if (seconds <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'Kedaluwarsa!';
            timerBadge.classList.add('expired');
            btnVerify.disabled = true;
            btnVerify.textContent = 'OTP Kedaluwarsa';
        }
    }, 1000);
</script>

</body>
</html>
