
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} - PSGA UIN Jakarta</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f5f8;
            color: #333;
            line-height: 1.7;
        }

        .header {
            background: white;
            padding: 18px 6%;
            box-shadow: 0 2px 8px #00000012;
        }

        .header a {
            color: #A10F79;
            text-decoration: none;
            font-weight: bold;
            font-size: 22px;
        }

        .container {
            max-width: 900px;
            margin: 35px auto;
            padding: 30px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 3px 15px #00000010;
        }

        .kategori {
            display: inline-block;
            color: #A10F79;
            background: #f8e7f3;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        h1 {
            line-height: 1.3;
            color: #333;
            margin: 18px 0 10px;
        }

        .tanggal {
            color: #777;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .gambar-berita {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 22px;
        }

.isi-berita {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    text-align: justify;
    font-weight: 400;
    overflow-wrap: anywhere;
}

.isi-berita p {
    font-weight: 400;
    margin: 0 0 16px;
}

.isi-berita img {
    max-width: 100%;
    height: auto;
}

.isi-berita h1,
.isi-berita h2,
.isi-berita h3 {
    margin: 24px 0 12px;
}
        .kembali {
            display: inline-block;
            margin-top: 25px;
            color: #A10F79;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .container {
                margin: 18px 12px;
                padding: 20px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="{{ url('/') }}">PSGA UIN Jakarta</a>
    </header>

    <main class="container">

        @if ($berita->kategori)
            <span class="kategori">
                {{ $berita->kategori }}
            </span>
        @endif

        <h1>{{ $berita->judul }}</h1>

        <div class="tanggal">
            {{ optional($berita->tanggal_terbit ?? $berita->created_at)->translatedFormat('d F Y') }}
        </div>

        @if ($berita->gambar)
            <img
                src="{{ asset('storage/' . $berita->gambar) }}"
                alt="{{ $berita->judul }}"
                class="gambar-berita"
            >
        @endif

        @if ($berita->ringkasan)
            <p>{!! $berita->ringkasan !!}</p>
        @endif

        <div class="isi-berita">
            {!! $berita->isi !!}
        </div>

        <a href="{{ url('/') }}" class="kembali">
            ← Kembali ke Beranda
        </a>

    </main>

</body>
</html>
