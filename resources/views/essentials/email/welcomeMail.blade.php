<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #435ebe;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 15px;
        }
        h1 {
            color: #435ebe;
            margin: 0;
            font-size: 28px;
        }
        .welcome-content {
            margin-bottom: 30px;
        }
        .user-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .user-info h3 {
            margin-top: 0;
            color: #435ebe;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
        }
        .info-value {
            color: #333;
        }
        .roles-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .role-badge {
            background: #435ebe;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .cta-button {
            display: inline-block;
            background: #435ebe;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .cta-button:hover {
            background: #364a98;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .credentials-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box h4 {
            color: #856404;
            margin-top: 0;
        }
        .credential-item {
            background: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #435ebe;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            @if(isset($academy) && $academy->logo_horizontal)
                <img src="{{ $academy->logo_horizontal }}" alt="{{ $academy->name ?? config('app.name') }}" class="logo">
            @endif
            <h1>Selamat Datang!</h1>
            <p>Akun Anda telah berhasil dibuat</p>
        </div>

        <div class="welcome-content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <p>Selamat datang di <strong>{{ $academy->name ?? config('app.name') }}</strong>! Akun Anda telah berhasil dibuat dan siap digunakan.</p>

            <div class="user-info">
                <h3>Informasi Akun Anda</h3>
                <div class="info-row">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon:</span>
                    <span class="info-value">{{ $user->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Username:</span>
                    <span class="info-value">{{ $user->username }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Role:</span>
                    <div class="roles-list">
                        @foreach($user->roles as $role)
                            <span class="role-badge">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="credentials-box">
                <h4>Informasi Login</h4>
                <div class="credential-item">
                    <strong>Username:</strong> {{ $user->username }}
                </div>
                <div class="credential-item">
                    <strong>Password:</strong> {{ $temporaryPassword ?? 'Sesuai yang telah Anda atur' }}
                </div>
                <p style="margin-top: 15px; color: #856404; font-size: 14px;">
                    <strong>Catatan:</strong> Untuk keamanan, segera ubah password Anda setelah login pertama kali.
                </p>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="cta-button">Login Sekarang</a>
            </div>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim dukungan kami.</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem {{ $academy->name ?? config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ $academy->name ?? config('app.name') }}. All rights reserved.</p>
            @if(isset($academy) && $academy->phone)
                <p>Hubungi kami: {{ $academy->phone }} | {{ $academy->email ?? '' }}</p>
            @endif
        </div>
    </div>
</body>
</html>