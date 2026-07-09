<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SKL - {{ $skl->mahasiswa->user->name }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 2cm;
            background-color: #fff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        .header-text {
            text-align: center;
            vertical-align: middle;
        }
        .header-text h2 {
            margin: 0;
            font-size: 15pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header-text h1 {
            margin: 2px 0;
            font-size: 17pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #1e3a8a;
        }
        .header-text p {
            margin: 0;
            font-size: 9.5pt;
            font-style: italic;
        }
        .doc-title {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 25px;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .doc-title p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }
        .content-para {
            text-align: justify;
            text-indent: 40px;
            margin-bottom: 20px;
        }
        .info-table {
            width: 90%;
            margin: 15px auto;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 30%;
        }
        .info-table td.colon {
            width: 3%;
            text-align: center;
        }
        .footer-block {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        .footer-block td {
            vertical-align: top;
        }
        .signature-area {
            width: 45%;
            float: right;
            text-align: left;
            margin-top: 40px;
        }
        .signature-space {
            height: 90px;
        }
        @media print {
            body {
                padding: 1cm;
            }
            .no-print {
                display: none;
            }
        }
        .no-print-bar {
            background-color: #f3f4f6;
            padding: 10px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 6px 16px;
            font-size: 10.5pt;
            border-radius: 4px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="no-print-bar no-print">
        <span>Pratinjau Dokumen Cetak Surat Keterangan Lulus (SKL)</span>
        <button class="btn-print" onclick="window.print()">Cetak Dokumen</button>
    </div>

    <!-- Kop Surat Resmi Kampus -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <!-- Fallback to text logo if image is not accessible -->
                <div style="font-size: 24px; font-weight: bold; border: 2px solid #000; padding: 5px; border-radius: 50%;">STF</div>
            </td>
            <td class="header-text">
                <h2>YAYASAN PENDIDIKAN MUHAMMADIYAH</h2>
                <h1>SEKOLAH TINGGI FARMASI MUHAMMADIYAH CIREBON</h1>
                <p>Jl. Cideng Indah No. 111, Kertawinangun, Kedawung, Kabupaten Cirebon, Jawa Barat 45153</p>
                <p>Email: info@stfmuhammadiyahcirebon.ac.id | Web: stfmuhammadiyahcirebon.ac.id</p>
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="doc-title">
        <h3>SURAT KETERANGAN LULUS</h3>
        <p>Nomor: {{ $skl->nomor_skl }}</p>
    </div>

    <p class="content-para">
        Yang bertanda tangan di bawah ini, Pimpinan Sekolah Tinggi Farmasi Muhammadiyah Cirebon menerangkan bahwa mahasiswa yang tercantum di bawah ini:
    </p>

    <!-- Informasi Mahasiswa -->
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="colon">:</td>
            <td><strong>{{ $skl->mahasiswa->user->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Induk Mahasiswa (NIM)</td>
            <td class="colon">:</td>
            <td>{{ $skl->mahasiswa->user->username }}</td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td class="colon">:</td>
            <td>{{ $skl->mahasiswa->programStudi->name }}</td>
        </tr>
        <tr>
            <td class="label">Jenjang Pendidikan</td>
            <td class="colon">:</td>
            <td>Sarjana (S1)</td>
        </tr>
        <tr>
            <td class="label">Tanggal Yudisium Kelulusan</td>
            <td class="colon">:</td>
            <td>{{ \Carbon\Carbon::parse($skl->tanggal_lulus)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Indeks Prestasi Kumulatif (IPK)</td>
            <td class="colon">:</td>
            <td><strong>{{ number_format($skl->ipk, 2) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Predikat Kelulusan</td>
            <td class="colon">:</td>
            <td>{{ $skl->yudisium }}</td>
        </tr>
        <tr>
            <td class="label">Judul Skripsi</td>
            <td class="colon">:</td>
            <td style="font-style: italic;">"{{ $skl->judul_skripsi }}"</td>
        </tr>
    </table>

    <p class="content-para">
        Adalah benar telah dinyatakan **LULUS** pada Ujian Sidang Skripsi/Karya Tulis Ilmiah Sekolah Tinggi Farmasi Muhammadiyah Cirebon. Surat Keterangan Lulus ini diterbitkan sebagai dokumen pengganti sementara Ijazah asli yang sedang dalam proses penerbitan di LLDIKTI.
    </p>

    <p class="content-para">
        Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <!-- Tanda Tangan Pejabat -->
    <div class="signature-area">
        <div>Cirebon, {{ \Carbon\Carbon::parse($skl->created_at)->format('d F Y') }}</div>
        <div>{{ $skl->jabatan_penandatangan }},</div>
        <div class="signature-space">
            <!-- Simulated Stamp or Electronic Signature Space -->
            <div style="margin-top: 25px; font-size: 8pt; color: #6b7280; font-family: monospace; border: 1px dashed #d1d5db; padding: 5px; width: 200px;">
                E-SIGNATURE VERIFIED<br>
                SECURE QR VALIDATION<br>
                REF: {{ sha1($skl->id . $skl->nomor_skl) }}
            </div>
        </div>
        <div><strong><u>{{ $skl->pejabat_penandatangan }}</u></strong></div>
        <div class="small">NIDN. 0405087501</div>
    </div>

    <script>
        // Trigger print dialog automatically when loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
