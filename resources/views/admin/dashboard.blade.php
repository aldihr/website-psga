
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin PSGA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f8;
            color: #333;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            background: #74115e;
            color: white;
            padding: 25px 16px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .subtitle {
            font-size: 12px;
            color: #f1d9eb;
            margin-bottom: 30px;
        }

        .menu-label {
            font-size: 11px;
            color: #e3b8d8;
            margin: 22px 10px 10px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 12px;
            margin: 5px 0;
            border-radius: 8px;
            font-size: 14px;
        }

        .menu a:hover,
        .menu a.active {
            background: #a83a8a;
        }

        .main {
            margin-left: 250px;
            padding: 35px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        h1 {
            color: #74115e;
            margin: 0 0 8px;
            font-size: 30px;
        }

        .description {
            color: #777;
            margin: 0;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0000000d;
        }

        .card h2 {
            font-size: 19px;
            margin: 0 0 10px;
            color: #74115e;
        }

        .card p {
            color: #666;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            background: #a10f79;
            color: white;
            padding: 11px 16px;
            text-decoration: none;
            border-radius: 7px;
            font-size: 14px;
        }

        .button:hover {
            background: #74115e;
        }

        .top-button {
            color: #a10f79;
            text-decoration: none;
            font-size: 14px;
        }

        
.sidebar-logout {
    margin-top: 35px;
    padding: 0 20px;
}

.btn-logout {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 8px;
    background: transparent;
    color: white;
    font-size: 15px;
    text-align: left;
    cursor: pointer;
    transition: 0.2s;
}

.btn-logout:hover {
    background: #a20d79;
    border-color: white;
}


        @media (max-width: 700px) {
            .sidebar {
                position: relative;
                width: 100%;
                padding: 20px 15px;
            }

            .main {
                margin-left: 0;
                padding: 22px 15px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand">PSGA Admin</div>
        <div class="subtitle">Pusat Studi Gender dan Anak</div>

        <nav class="menu">
            <div class="menu-label">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="active">
                ▦ Dashboard
            </a>

            <a href="{{ route('pengaduan.admin') }}">
                ▤ Kelola Pengaduan
            </a>

            <a href="{{ route('berita.index') }}">
                ▣ Kelola Berita
            </a>

            <div class="menu-label">Website</div>

                <a href="{{ url('/') }}" target="_blank">
                ↗ Lihat Website
            </a>

<form action="{{ route('logout') }}" method="POST" style="margin: 25px 20px 0;">
    @csrf
    <button type="submit" style="
        width: 100%;
        padding: 12px 15px;
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 8px;
        background: transparent;
        color: white;
        text-align: left;
        font-size: 15px;
        cursor: pointer;
    ">
        ↪ Logout
    </button>
</form>

        </nav>
    </aside>

    <main class="main">
        <header class="header">
            <div>
                <h1>Dashboard Admin</h1>
                <p class="description">
                    Selamat datang di panel pengelolaan PSGA UIN Jakarta.
                </p>
            </div>

            <a class="top-button" href="{{ url('/') }}" target="_blank">
                Buka Website Publik ↗
            </a>
        </header>

        <section class="cards">
            <div class="card">
                <h2>Kelola Pengaduan</h2>
                <p>
                    Lihat data pengaduan, periksa laporan, dan
                    perbarui status penanganan pengaduan.
                </p>
                <a class="button" href="{{ route('pengaduan.admin') }}">
                    Buka Pengaduan
                </a>
            </div>

            <div class="card">
                <h2>Kelola Berita</h2>
                <p>
                    Buat berita, unggah gambar, dan terbitkan informasi
                    agar tampil di beranda website PSGA.
                </p>
                <a class="button" href="{{ route('berita.index') }}">
                    Buka Kelola Berita
                </a>
                <a class="button"
                   href="{{ route('berita.create') }}"
                   style="margin-top: 10px;">
                    + Tambah Berita
                </a>
            </div>
        </section>
    </main>

</body>
</html>
