<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $broadcastSubject }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f8; color: #333333; }
        .wrapper { max-width: 620px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1a56db 0%, #0e3fa5 100%); padding: 36px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .header p { color: rgba(255,255,255,0.80); font-size: 13px; margin-top: 6px; }
        .badge-pengumuman { display: inline-block; background: rgba(255,255,255,0.2); color: #ffffff; border-radius: 20px; font-size: 11px; font-weight: 600; padding: 4px 14px; margin-bottom: 12px; letter-spacing: 1px; text-transform: uppercase; }
        .body { padding: 36px 32px; }
        .subject-title { font-size: 19px; font-weight: 700; color: #1a56db; margin-bottom: 20px; border-left: 4px solid #1a56db; padding-left: 12px; }
        .content-box { font-size: 14.5px; line-height: 1.75; color: #444; white-space: pre-line; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 28px 0; }
        .note { background: #f0f4ff; border-radius: 8px; padding: 14px 18px; font-size: 12.5px; color: #6b7280; }
        .footer { background: #f9fafb; padding: 20px 32px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { font-size: 12px; color: #9ca3af; }
        .footer .academy-name { font-weight: 600; color: #374151; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="badge-pengumuman">Pengumuman Resmi</div>
            <h1>{{ $academyName }}</h1>
            <p>Sistem Informasi Akademik Terpadu</p>
        </div>

        <!-- Body -->
        <div class="body">
            <div class="subject-title">{{ $broadcastSubject }}</div>

            <div class="content-box">{{ $broadcastContent }}</div>

            <hr class="divider">

            <div class="note">
                <strong>Catatan:</strong> Email ini dikirimkan secara otomatis oleh sistem SIAKAD
                <strong>{{ $academyName }}</strong>. Harap tidak membalas email ini langsung.
                Untuk pertanyaan lebih lanjut, silakan hubungi bagian akademik kampus Anda.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dikirim oleh <span class="academy-name">{{ $academyName }}</span></p>
            <p style="margin-top:4px;">{{ now()->format('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</body>
</html>
