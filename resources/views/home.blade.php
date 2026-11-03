<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title> PSGA UIN Syarif Hidayatullah Jakarta</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #333;
        }

        /* =========================
           NAVBAR
        ========================= */

        header {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
             }
            .logo h1 {
             color: #8e0f72;
            }

        .navbar {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-box {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.logo-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

        .logo-text {
            color: #A10F79;
            font-size: 18px;
            font-weight: bold;
        }

        .logo-text small {
            display: block;
            color: #666;
            font-size: 11px;
            font-weight: normal;
        }

        .menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 5px;
        }

        .menu li {
            position: relative;
        }

        .menu li a {
            display: block;
            padding: 10px 14px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
        }

        .menu li a:hover {
            background: #f0e5f7;
            color: #A10F79;
        }

        /* SUB MENU */

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 190px;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 6px;
            list-style: none;
            padding: 8px 0;
        }

        .menu li:hover .submenu {
            display: block;
        }

        .submenu li a {
            padding: 10px 15px;
            white-space: nowrap;
        }

        .btn-pengaduan {
            background: #A10F79 !important;
            color: white !important;
        }

        .btn-pengaduan:hover {
            background: #A10F79 !important;
        }

        /* =========================
           SLIDER
        ========================= */

        .slider {
            max-width: 1200px;
            margin: 25px auto;
            padding: 0 20px;
        }

        .slider-container {
            position: relative;
            height: 600px;
            overflow: hidden;
            border-radius: 15px;
        }

        .slide {
            display: none;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .slide.active {
            display: block;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to right,
                rgba(0,0,0,0.65),
                rgba(0,0,0,0.15)
            );
        }

        .slide-content {
            position: absolute;
            left: 50px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            max-width: 600px;
        }

        .slide-content h1 {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .slide-content p {
            font-size: 17px;
            line-height: 1.6;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: rgba(0,0,0,0.4);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .dots {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }

        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: white;
            opacity: 0.5;
            border-radius: 50%;
            margin: 0 4px;
            cursor: pointer;
        }

        .dot.active {
            opacity: 1;
        }

        /* =========================
           SAMBUTAN
        ========================= */
        .text-justify {
        text-align: justify;
        line-height: 1.8;
        }
        .section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title h2 {
            color: #A10F79;
            margin-bottom: 8px;
        }

        .section-title p {
            color: #777;
        }

        .welcome {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            line-height: 1.8;
            text-align: center;
        }

        
/* Pimpinan PSGA */
#pimpinan-psga {
    padding-top: 35px;
    padding-bottom: 45px;
}

.pimpinan-tabs {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin: 25px 0;
}

.pimpinan-tab {
    padding: 10px 24px;
    border: 1px solid #A10F79;
    border-radius: 5px;
    background: white;
    color: #A10F79;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
}

.pimpinan-tab:hover,
.pimpinan-tab.active {
    background: #A10F79;
    color: white;
}

.pimpinan-content {
    max-width: 950px;
    margin: 0 auto;
    padding: 30px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
}

.pimpinan-item {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 30px;
}

.pimpinan-item:last-child {
    margin-bottom: 0;
}

.pimpinan-item[hidden] {
    display: none;
}

.pimpinan-foto {
    width: 150px;
    height: 180px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8E8F3;
    border-radius: 8px;
    color: #A10F79;
    font-weight: bold;
}

.pimpinan-item h3 {
    color: #A10F79;
    margin: 0 0 10px;
}

.pimpinan-item p {
    color: #555;
    margin: 0;
    line-height: 1.7;
}

@media (max-width: 600px) {
    .pimpinan-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .pimpinan-foto {
        width: 130px;
        height: 155px;
    }

    .pimpinan-content {
        padding: 20px;
    }
}

        /* =========================
           BERITA
        ========================= */

        .news-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .news-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .news-content {
            padding: 20px;
        }

        .news-date {
            color: #A10F79;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .news-content h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 19px;
        }

        .news-content p {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        .read-more {
            display: inline-block;
            margin-top: 15px;
            color: #A10F79;
            text-decoration: none;
            font-weight: bold;
        }

        .berita-ringkasan {
    font-size: 14px;
    line-height: 1.7;
    color: #555;
    font-weight: 400;
    text-align: justify;
    overflow-wrap: anywhere;
}

.berita-ringkasan p {
    margin: 0 0 8px;
    font-weight: 400;
}

.berita-ringkasan strong,
.berita-ringkasan b {
    font-weight: 400;
}

/* ================================
   FOOTER PSGA
================================ */

.footer-psga {
    background: #A10F79;
    color: white;
    margin-top: 50px;
    padding: 45px 7% 20px;
}

/* MEDIA SOSIAL */

.footer-social {
    text-align: center;
    margin-bottom: 35px;
}

.footer-social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    width: 42px;
    height: 42px;

    margin: 0 8px;

    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 50%;

    color: white;
    text-decoration: none;

    font-size: 20px;
    font-weight: bold;

    transition: 0.3s;
}

.footer-social a i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 20px;
}


.footer-social a:hover {
    background: white;
    color: #A10F79;
    transform: translateY(-3px);
}

/* BAGIAN UTAMA FOOTER */

.footer-main {
    max-width: 1100px;
    margin: auto;

    display: grid;
    grid-template-columns: 1.5fr 1fr 1.3fr;

    gap: 50px;
}

.footer-main h2 {
    margin: 0 0 12px;

    color: white;

    font-size: 25px;
}

.footer-main h3 {
    margin-bottom: 15px;

    color: white;

    font-size: 18px;
}

.footer-main p {
    color: rgba(255,255,255,0.85);
    line-height: 1.7;
}


/* MENU FOOTER */

.footer-menu {
    display: flex;
    flex-direction: column;
}

.footer-menu a {
    color: rgba(255,255,255,0.85);

    text-decoration: none;

    margin-bottom: 10px;

    transition: 0.2s;
}

.footer-menu a:hover {
    color: white;
    padding-left: 5px;
}


/* ALAMAT */

.footer-address p {
    line-height: 1.8;
    margin-bottom: 15px;
}

.footer-map {
    width: 100%;
    max-width: 360px;
    overflow: hidden;
    border-radius: 10px;
    margin-top: 10px;
}

.footer-map iframe {
    display: block;
    width: 100%;
    border-radius: 10px;
}


/* GARIS */

.footer-line {
    max-width: 1100px;

    height: 1px;

    background: rgba(255,255,255,0.25);

    margin: 35px auto 18px;
}


/* COPYRIGHT */

.footer-bottom {
    max-width: 1100px;

    margin: auto;

    display: flex;

    justify-content: space-between;

    align-items: center;

    color: rgba(255,255,255,0.8);

    font-size: 13px;
}


/* RESPONSIVE */

@media (max-width: 768px) {

    .footer-main {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 8px;

        text-align: center;
    }

    .footer-social a {
        margin: 0 4px;
    }

}

        /* =========================
           RESPONSIVE
        ========================= */

        .mobile-menu {
            display: none;
            background: #A10F79;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 20px;
            cursor: pointer;
        }

        @media (max-width: 900px) {

            .mobile-menu {
                display: block;
            }

            .menu {
                display: none;
                position: absolute;
                top: 75px;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                align-items: stretch;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                padding: 10px 20px;
            }

            .menu.show {
                display: flex;
            }

            .menu li {
                width: 100%;
            }

            .menu li a {
                width: 100%;
            }

            .submenu {
                position: static;
                box-shadow: none;
                display: none;
                padding-left: 20px;
            }

            .menu li:hover .submenu {
                display: block;
            }

            .slider-container {
                height: 350px;
            }

            .slide-content h1 {
                font-size: 30px;
            }

            .footer-main {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {

            .navbar {
                padding: 12px 15px;
            }

            .logo-text {
                font-size: 15px;
            }

            .slider {
                padding: 0 10px;
            }

            .slider-container {
                height: 300px;
                border-radius: 10px;
            }

            .slide-content {
                left: 25px;
                right: 25px;
            }

            .slide-content h1 {
                font-size: 24px;
            }

            .slide-content p {
                font-size: 14px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 0 15px;
            }

            .welcome {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

<!-- =========================
     NAVBAR
========================= -->

<header>

    <nav class="navbar">

        <div class="logo">
    <div class="logo-box">
        <img src="{{ asset('images/logo-psga.jpg') }}"
             alt="Logo PSGA UIN Jakarta">
    </div>

    <div>
        <h1>PSGA UIN Jakarta</h1>
        <p>Pusat Studi Gender dan Anak</p>
    </div>
</div>

        <button class="mobile-menu" onclick="toggleMenu()">
            ☰
        </button>

        <ul class="menu" id="menu">

            <li>
                <a href="/">Beranda</a>
            </li>

            <li>
                <a href="#">Profil ▾</a>

                <ul class="submenu">
                    <li>
                        <a href="{{ route('tentang.psga') }}">Tentang PSGA</a>
                    </li>

                    <li>
                        <a href="{{ url('/visi-misi') }}">Visi & Misi</a>
                    </li>
                </ul>

            </li>

            <li>
                <a href="#">Layanan ▾</a>

                <ul class="submenu">

                    <li>
                        <a href="{{ route('pengaduan.create') }}">
                            Pengaduan
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pengaduan.tracking') }}">
                            Tracking Pengaduan
                        </a>
                    </li>

                </ul>

            </li>

            <li>
                <a href="#">Informasi ▾</a>

                <ul class="submenu">

                    <li>
                        <a href="#">Berita</a>
                    </li>

                    <li>
                        <a href="#">Agenda</a>
                    </li>

                </ul>

            </li>

            <li>            
                <a href="{{ route('kontak') }}">Kontak</a>
            </li>

            <li>
                <a
                    href="{{ route('pengaduan.create') }}"
                    class="btn-pengaduan"
                >
                    Ajukan Pengaduan
                </a>
            </li>

        </ul>

    </nav>

</header>


<!-- =========================
     SLIDER
========================= -->

<section class="slider">

    <div class="slider-container">

        <div class="slide active">

            <img
                src="{{ asset('images/struktur-organisasi.png') }}"
                alt="PSGA UIN Jakarta"
            >

            <div class="slide-overlay"></div>

            <div class="slide-content">

                <h1>
                    Pusat Studi Gender dan Anak
                </h1>

                <p>
                    Mewujudkan lingkungan kampus yang aman,
                    inklusif, setara, dan bebas dari kekerasan.
                </p>

            </div>

        </div>


        <div class="slide">

            <img
                src="{{ asset('images/slider2.jpg') }}"
                alt="Layanan PSGA"
            >

            <div class="slide-overlay"></div>

            <div class="slide-content">

                <h1>
                    Layanan Pengaduan PSGA
                </h1>

                <p>
                    Sampaikan pengaduan Anda dengan aman
                    dan dapatkan informasi perkembangan laporan.
                </p>

            </div>

        </div>


        <div class="slide">

            <img
                src="{{ asset('images/uin-taman.jpg') }}"
                alt="Kegiatan PSGA"
            >

            <div class="slide-overlay"></div>

            <div class="slide-content">

                <h1>
                    Bersama Mewujudkan Kampus Aman
                </h1>

                <p>
                    Bersama membangun lingkungan pendidikan
                    yang ramah, aman dan berkeadilan.
                </p>

            </div>

        </div>


        <button class="slider-btn prev" onclick="changeSlide(-1)">
            ❮
        </button>

        <button class="slider-btn next" onclick="changeSlide(1)">
            ❯
        </button>


        <div class="dots">

            <span class="dot active" onclick="currentSlide(1)"></span>

            <span class="dot" onclick="currentSlide(2)"></span>

            <span class="dot" onclick="currentSlide(3)"></span>

        </div>

    </div>

</section>


<!-- =========================
     SAMBUTAN
========================= -->

<section class="section">

    <div class="section-title">

        <h2>
            Selamat Datang di PSGA
        </h2>

        <p>
            Pusat Studi Gender dan Anak UIN Syarif Hidayatullah Jakarta
        </p>

    </div>


    <div class="welcome">

        <p class="text-justify">
    <strong>Pusat Studi Gender dan Anak (PSGA) UIN Syarif Hidayatullah Jakarta</strong>
    hadir sebagai pusat layanan, edukasi, penelitian, dan pengembangan kajian
    yang berfokus pada isu gender, perlindungan anak, serta terciptanya lingkungan
    kampus yang aman, inklusif, dan berkeadilan. PSGA berperan dalam meningkatkan
    pemahaman dan kesadaran sivitas akademika terhadap kesetaraan gender,
    pencegahan kekerasan, perlindungan hak anak, serta berbagai persoalan sosial
    yang berkaitan dengan gender dan anak.
</p>

<p class="text-justify">
    Dalam menjalankan perannya, PSGA menyediakan layanan pengaduan bagi masyarakat
    kampus yang membutuhkan bantuan dan pendampingan terkait permasalahan gender,
    kekerasan, diskriminasi, pelecehan seksual, maupun bentuk permasalahan lainnya.
    Layanan pengaduan dikembangkan agar dapat diakses dengan mudah, aman, menjaga
    kerahasiaan pelapor, serta memberikan informasi mengenai perkembangan penanganan
    pengaduan secara lebih terstruktur dan dapat dipantau oleh pelapor.
</p>

<p class="text-justify">
    Melalui layanan, edukasi, kajian, dan berbagai kegiatan pengembangan lainnya,
    PSGA UIN Syarif Hidayatullah Jakarta berkomitmen untuk mendukung terwujudnya
    lingkungan akademik yang <strong>aman, ramah, inklusif, responsif gender,
    dan bebas dari kekerasan serta diskriminasi.</strong>
</p>

    </div>

</section>


<!-- =========================
     PIMPINAN PSGA
========================= -->
<section class="section" id="pimpinan-psga">

    <div class="section-title">
        <h2>Pengelola PSGA</h2>
        <p>Struktur organisasi Pusat Studi Gender dan Anak UIN Syarif Hidayatullah Jakarta</p>
    </div>

    <div class="pimpinan-tabs">
        <button type="button" class="pimpinan-tab active"
                onclick="tampilkanPimpinan('pimpinan', this)">
            Pimpinan
        </button>

        <button type="button" class="pimpinan-tab"
                onclick="tampilkanPimpinan('koordinator', this)">
            Koordinator
        </button>

        <button type="button" class="pimpinan-tab"
                onclick="tampilkanPimpinan('staf', this)">
            Staf
        </button>
    </div>

    <div class="pimpinan-item" id="pimpinan">
    <img
    src="{{ asset('images/Dr.wiwisitisajaroh,M.Ag.png') }}"
    alt="Dr. Wiwi Siti Sajaroh, M.Ag."
    class="pimpinan-foto"
>

    <div>
        <h3>Dr. Wiwi Siti Sajaroh, M.Ag.</h3>
        <p>Pimpinan PSGA UIN Syarif Hidayatullah Jakarta</p>
    </div>
</div>

       <!-- KOORDINATOR BIDANG GENDER -->
<div class="pimpinan-item" id="koordinator-gender" hidden>
    <img
    src="{{ asset('images/nadya.png') }}"
    alt="Koordinator Bidang Anak PSGA"
    class="pimpinan-foto"
>

    <div>
        <h3>Nadya Kharima M.Kesos</h3>
        <p>Koordinator Bidang Gender</p>
    </div>
</div>

<!-- KOORDINATOR BIDANG ANAK -->
<div class="pimpinan-item" id="koordinator-anak" hidden>
    <img
    src="{{ asset('images/fifi.png') }}"
    alt="Koordinator Bidang Anak PSGA"
    class="pimpinan-foto"
>

    <div>
        <h3>Musfiah Saidah, S.Sos., M.Si.</h3>
        <p>Koordinator Bidang Anak</p>
    </div>
</div>

        <div class="pimpinan-item" id="staf" hidden>
            <img
    src="{{ asset('images/anis.jpg') }}"
    alt="Koordinator Bidang Anak PSGA"
    class="pimpinan-foto"
>
            <div>
                <h3>Anis Qurli Oktavia, S.Psi</h3>
                <p>Staf PSGA UIN Syarif Hidayatullah Jakarta</p>
            </div>
        </div>
    </div>

</section>

<script>

function tampilkanPimpinan(id, tombol) {
    // Sembunyikan semua bagian pimpinan, koordinator, dan staf
    document.querySelectorAll('.pimpinan-item').forEach(function(item) {
        item.hidden = true;
    });

    // Hapus status aktif dari semua tombol
    document.querySelectorAll('.pimpinan-tab').forEach(function(tab) {
        tab.classList.remove('active');
    });

    // Jika tombol Koordinator diklik, tampilkan dua koordinator
    if (id === 'koordinator') {
        document.getElementById('koordinator-gender').hidden = false;
        document.getElementById('koordinator-anak').hidden = false;
    } else {
        // Untuk Pimpinan atau Staf, tampilkan bagian sesuai ID
        document.getElementById(id).hidden = false;
    }

    // Tandai tombol yang dipilih
    tombol.classList.add('active');
}

</script>



<!-- =========================
     BERITA
========================= -->

<section class="section">

   
<div class="news-grid">

    @forelse ($beritaBeranda as $item)
        <article class="news-card">

            @if ($item->gambar)
                <img
                    src="{{ asset('storage/' . $item->gambar) }}"
                    alt="{{ $item->judul }}"
                    class="berita-img"
                >
            @endif

            <div class="news-content">

                <span class="news-date">
                    {{ $item->tanggal_terbit
                        ? \Carbon\Carbon::parse($item->tanggal_terbit)->translatedFormat('d F Y')
                        : $item->created_at->translatedFormat('d F Y') }}
                </span>

                <h3>{{ $item->judul }}</h3>

                <div class="berita-ringkasan">
                        @if ($item->ringkasan)
                        {!! $item->ringkasan !!}
                        @else
                   {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 180) }}
                        @endif
                </div>

              <a href="{{ route('berita.show', $item->slug) }}">
    Baca Selengkapnya →
</a>

            </div>

        </article>
    @empty
        <p>Belum ada berita yang diterbitkan.</p>
    @endforelse

</div>

</section>


<!-- =========================
     FOOTER
========================= -->

<footer class="footer-psga">

    <!-- MEDIA SOSIAL -->
    <div class="footer-social">

    <a href="https://wa.me/6285773966700"
       title="WhatsApp"
       target="_blank"
       rel="noopener noreferrer">
        <i class="fab fa-whatsapp"></i>
    </a>

     <a href="mailto:psgauinofficial@gmail.com"
        title="Email">
        <i class="fas fa-envelope"></i>
    </a>

    <a href="https://www.instagram.com/psgauinjkt/"
        title="Instagram"
        target="_blank"
        rel="noopener noreferrer">
        <i class="fab fa-instagram"></i>
    </a>
        
        <a href="https://www.youtube.com/@SudutPSGA"
        title="YouTube"
        target="_blank"
        rel="noopener noreferrer">
        <i class="fab fa-youtube"></i>
    </a>

    </div>

    <!-- IDENTITAS PSGA -->
    <div class="footer-main">

        <div class="footer-brand">
            <h2>PSGA UIN Jakarta</h2>

            <p>
                Pusat Studi Gender dan Anak
                UIN Syarif Hidayatullah Jakarta
            </p>
        </div>

        <!-- MENU -->
        <div class="footer-menu">
            <h3>Menu</h3>

            <a href="{{ url('/') }}">Beranda</a>
            <a href="{{ route('pengaduan.create') }}">Pengaduan</a>
            <a href="{{ route('pengaduan.tracking') }}">Tracking Pengaduan</a>
            <a href="#berita">Informasi</a>
        </div>

        <!-- KONTAK -->
        <div class="footer-address">

    <h3>Alamat</h3>

    <div class="footer-map">
        <iframe
            src="https://www.google.com/maps?q=UIN%20Syarif%20Hidayatullah%20Jakarta&output=embed"
            width="100%"
            height="180"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

</div>

    <!-- GARIS -->
    <div class="footer-line"></div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        <span>
            © 2026 Created By Aldi Haryadi
</span>
    </div>

</footer>

<script>

    /* =========================
       MOBILE MENU
    ========================= */

    function toggleMenu() {

        const menu = document.getElementById("menu");

        menu.classList.toggle("show");

    }


    /* =========================
       SLIDER
    ========================= */

    let slideIndex = 1;

    showSlide(slideIndex);


    function changeSlide(n) {

        showSlide(slideIndex += n);

    }


    function currentSlide(n) {

        showSlide(slideIndex = n);

    }


    function showSlide(n) {

        let slides = document.querySelectorAll(".slide");

        let dots = document.querySelectorAll(".dot");


        if (n > slides.length) {

            slideIndex = 1;

        }


        if (n < 1) {

            slideIndex = slides.length;

        }


        slides.forEach(function(slide) {

            slide.classList.remove("active");

        });


        dots.forEach(function(dot) {

            dot.classList.remove("active");

        });


        slides[slideIndex - 1].classList.add("active");

        dots[slideIndex - 1].classList.add("active");

    }


    /* AUTO SLIDER */

    setInterval(function() {

        changeSlide(1);

    }, 5000);

</script>


</body>
</html>