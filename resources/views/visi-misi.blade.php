<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visi & Misi - PSGA UIN Jakarta</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        h1 {
            color: #8e0f72;
            margin-bottom: 25px;
        }

        h2 {
            color: #8e0f72;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        p {
            line-height: 1.8;
            text-align: justify;
        }

        .visi {
            background: #f7eafa;
            padding: 20px;
            border-left: 5px solid #8e0f72;
            border-radius: 8px;
            line-height: 1.8;
        }

        .misi {
            padding-left: 25px;
            line-height: 1.9;
        }

        .misi li {
            margin-bottom: 10px;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 18px;
            background: #8e0f72;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back:hover {
            background: #6f0b59;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px auto;
                padding: 15px;
            }

            .card {
                padding: 25px 20px;
            }

            h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Visi & Misi PSGA</h1>

        <h2>Visi</h2>

        <div class="visi">
            Mewujudkan kampus UIN Syarif Hidayatullah Jakarta yang responsif,
            berkeadilan, dan setara gender, serta menjadi pusat rujukan studi
            gender dan anak yang unggul berbasis nilai-nilai keislaman.
        </div>

        <h2>Misi</h2>

        <ol class="misi">
            <li>
                Tridarma Kesetaraan: Mempercepat kesetaraan dan keadilan
                gender dalam bidang pengajaran, penelitian, dan pengabdian kepada masyarakat.
            </li>

            <li>
                Kajian & Riset: Mengembangkan kajian, konsultasi, dan penelitian ilmiah
                terkait isu gender, hak anak, serta keluarga.
            </li>

            <li>
                Pencegahan Kekerasan: Menjadi lembaga pendampingan serta wadah pelaporan
                dan pencegahan tindakan kekerasan seksual serta diskriminasi di lingkungan kampus.
            </li>

            <li>
                Sinergi Program: Mensinergikan berbagai program unggulan universitas untuk memperkuat
                iklim kampus yang aman, inklusif, humanis, dan ramah anak/perempuan.
            </li>
        </ol>

        <a href="{{ url('/') }}" class="back">
            ← Kembali ke Beranda
        </a>

    </div>

</div>

</body>
</html>