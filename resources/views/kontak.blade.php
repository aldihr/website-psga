
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak PSGA UIN Jakarta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f7f7f7;
            color: #333;
        }
        .kontak-header {
            background: #A10F79;
            color: white;
            text-align: center;
            padding: 45px 20px;
        }
        .kontak-container {
            max-width: 1000px;
            margin: 35px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }
        .kontak-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #00000012;
        }
        .kontak-card h3 {
            color: #A10F79;
        }
        .kontak-card a {
            color: #A10F79;
        }
        .kembali {
            display: block;
            text-align: center;
            margin: 10px auto 35px;
            color: #A10F79;
        }
        @media (max-width: 600px) {
            .kontak-container {
                grid-template-columns: 1fr;
                margin: 15px auto;
            }
        }
    </style>
</head>
<body>

    <header class="kontak-header">
        <h1>Hubungi PSGA</h1>
        <p>Pusat Studi Gender dan Anak UIN Syarif Hidayatullah Jakarta</p>
    </header>

    <main class="kontak-container">
        <div class="kontak-card">
            <h3>📍 Alamat</h3>
            <p>Gedung Rektorat Lantai 1, Jl. Ir. H. Djuanda No. 95,
            Ciputat, Tangerang Selatan 15412.</p>
        </div>

        <div class="kontak-card">
            <h3>📞 Telepon</h3>
            <p><a href="tel:085773966700">0857-7396-6700</a></p>
            <p><a href="tel:0217401925">021-7401925 ext. 1850</a></p>
        </div>

        <div class="kontak-card">
            <h3>✉️ Email</h3>
            <p>
                <a href="mailto:psga@apps.uinjkt.ac.id">
                    psga@apps.uinjkt.ac.id
                </a>
            </p>
        </div>

        <div class="kontak-card">
            <h3>📷 Instagram</h3>
            <p>
                <a href="https://www.instagram.com/psgauinjkt/"
                   target="_blank" rel="noopener noreferrer">
                    Kunjungi Instagram PSGA
                </a>
            </p>
        </div>
    </main>

    <a class="kembali" href="{{ url('/') }}">← Kembali ke Beranda</a>

</body>
</html>
