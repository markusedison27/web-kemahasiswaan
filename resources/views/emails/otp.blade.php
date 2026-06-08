<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Login</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#1d4ed8,#2563eb);padding:36px 40px;text-align:center;">
                            <p style="margin:0;font-size:28px;font-weight:800;color:#ffffff;letter-spacing:-0.5px;">
                                🎓 {{ config('app.name') }}
                            </p>
                            <p style="margin:8px 0 0;font-size:14px;color:rgba(255,255,255,0.75);">
                                Sistem Informasi Akademik
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px;">
                            <p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#0f172a;">
                                Halo, {{ $userName }}! 👋
                            </p>
                            <p style="margin:0 0 28px;font-size:15px;color:#64748b;line-height:1.6;">
                                Kami menerima permintaan login ke akun Anda. Gunakan kode OTP berikut untuk menyelesaikan proses login:
                            </p>

                            <!-- OTP Box -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:24px 0;">
                                        <div style="display:inline-block;background:#eff6ff;border:2px dashed #2563eb;border-radius:16px;padding:24px 48px;">
                                            <p style="margin:0;font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#2563eb;font-weight:600;">Kode OTP Anda</p>
                                            <p style="margin:8px 0 0;font-size:42px;font-weight:800;color:#1d4ed8;letter-spacing:10px;font-family:'Courier New',monospace;">{{ $otp }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:8px;margin:8px 0 28px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="margin:0;font-size:13px;color:#92400e;">
                                            ⚠️ <strong>Kode ini berlaku selama 5 menit</strong> dan hanya bisa digunakan 1 kali. Jangan bagikan kode ini kepada siapapun.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:14px;color:#94a3b8;line-height:1.6;">
                                Jika Anda tidak mencoba login, abaikan email ini. Keamanan akun Anda tetap terjaga.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:24px 40px;text-align:center;">
                            <p style="margin:0;font-size:12px;color:#94a3b8;">
                                © {{ date('Y') }} {{ config('app.name') }} · Sistem Informasi Akademik<br>
                                Email ini dikirim otomatis, jangan membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
