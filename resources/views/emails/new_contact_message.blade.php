<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak Baru - SMAN 2 Situbondo</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #1B3C73 0%, #0e2347 100%);
            color: #ffffff;
            padding: 30px 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 6px 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 0;
            font-size: 13px;
            color: #e2e8f0;
        }
        .badge-category {
            display: inline-block;
            background-color: #F19E38;
            color: #0f172a;
            font-weight: 800;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .content {
            padding: 30px 25px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 10px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td.label {
            width: 32%;
            font-weight: 700;
            color: #475569;
            background-color: #f8fafc;
            border-radius: 6px;
        }
        .info-table td.value {
            color: #0f172a;
            font-weight: 500;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #1B3C73;
            border-radius: 0 12px 12px 0;
            padding: 18px 20px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #1e293b;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .btn-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-admin {
            display: inline-block;
            background-color: #F19E38;
            color: #ffffff !important;
            font-weight: 800;
            font-size: 14px;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(241, 158, 56, 0.35);
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SMAN 2 SITUBONDO</h1>
            <p>Notifikasi Pesan Masuk dari Formulir Hubungi Kami</p>
            <span class="badge-category">{{ $data['subject'] ?? 'Pesan Masuk' }}</span>
        </div>

        <!-- Body Content -->
        <div class="content">
            <p style="margin-top: 0; font-size: 14px; color: #334155;">
                Halo <strong>Administrator</strong>, Anda baru saja menerima pesan baru dari halaman kontak publik website SMAN 2 Situbondo:
            </p>

            <!-- Table of Sender Details -->
            <table class="info-table">
                <tr>
                    <td class="label">Nama Pengirim</td>
                    <td class="value"><strong>{{ $data['name'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Alamat Email</td>
                    <td class="value"><a href="mailto:{{ $data['email'] ?? '' }}" style="color: #1B3C73; text-decoration: none;">{{ $data['email'] ?? '-' }}</a></td>
                </tr>
                <tr>
                    <td class="label">Nomor Telepon</td>
                    <td class="value">{{ !empty($data['phone']) ? $data['phone'] : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Subjek Pesan</td>
                    <td class="value"><strong>{{ $data['subject'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Waktu Kirim</td>
                    <td class="value">{{ \Carbon\Carbon::parse($data['created_at'] ?? now())->translatedFormat('l, d F Y - H:i') }} WIB</td>
                </tr>
            </table>

            <!-- Message Body -->
            <div style="font-weight: 700; font-size: 13px; color: #475569; margin-bottom: 8px;">
                Isi Pesan:
            </div>
            <div class="message-box">{{ $data['message'] ?? '-' }}</div>

            <!-- CTA Button to Admin Dashboard -->
            <div class="btn-wrapper">
                <a href="{{ $data['admin_url'] ?? url('/admin/contact') }}" class="btn-admin" target="_blank" rel="noopener">
                    Buka di Admin Dashboard &rarr;
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 5px 0;">Email ini dikirim otomatis oleh sistem notifikasi website <strong>SMA Negeri 2 Situbondo</strong>.</p>
            <p style="margin: 0;">Jl. Anggrek No. 1 Patokan, Kec. Situbondo, Kabupaten Situbondo, Jawa Timur 68312</p>
        </div>
    </div>
</body>
</html>
