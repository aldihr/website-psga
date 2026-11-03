<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tracking Pengaduan PSGA</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6a1b9a;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            background: #6a1b9a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #4a126b;
        }

        .error {
            background: #ffe5e5;
            color: #b00020;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .hasil {
            margin-top: 30px;
        }

        .info {
            background: #f8f4fb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info p {
            margin: 8px 0;
        }

        /* TRACKING */

        .tracking {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin: 40px 10px 30px;
            position: relative;
        }

        .step {
            width: 18%;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .icon {
            width: 55px;
            height: 55px;
            margin: auto;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            color: white;
            transition: 0.3s;
        }

        .step.active .icon {
            background: #6a1b9a;
        }

        .step.completed .icon {
            background: #6a1b9a;
        }

        .step-name {
            margin-top: 10px;
            font-size: 13px;
            font-weight: bold;
            color: #777;
        }

        .step.active .step-name,
        .step.completed .step-name {
            color: #6a1b9a;
        }

        /* GARIS */

        .line {
            position: absolute;
            top: 27px;
            left: 10%;
            right: 10%;
            height: 5px;
            background: #ddd;
            z-index: 1;
        }

        .progress {
            height: 5px;
            background: #6a1b9a;
            width: 0%;
            transition: 0.5s;
        }

        .tanggapan {
            margin-top: 25px;
            background: #f8f4fb;
            padding: 15px;
            border-left: 5px solid #6a1b9a;
            border-radius: 5px;
        }

        @media (max-width: 700px) {

            .container {
                padding: 20px;
            }

            .tracking {
                flex-direction: column;
                align-items: flex-start;
                gap: 25px;
                margin-left: 20px;
            }

            .step {
                width: 100%;
                display: flex;
                align-items: center;
                text-align: left;
            }

            .icon {
                margin: 0 15px 0 0;
                flex-shrink: 0;
            }

            .step-name {
                margin-top: 0;
            }

            .line {
                left: 47px;
                top: 27px;
                bottom: 27px;
                width: 5px;
                height: auto;
            }

            .progress {
                width: 5px;
                height: 0%;
            }
        }

        .btn-home {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
}

.btn-home:hover {
    background-color: #5a6268;
    color: white;
}
    </style>
</head>

<body>

<div class="container">

    <h1>Tracking Pengaduan PSGA</h1>

    <p class="subtitle">
        Silakan masukkan nomor ticket untuk melihat status pengaduan.
    </p>

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif


    <form action="{{ route('pengaduan.tracking') }}" method="GET">

    <div class="form-group">

        <label for="no_ticket">
            Nomor Ticket
        </label>

        <input
            type="text"
            id="no_ticket"
            name="no_ticket"
            placeholder="Contoh: PSGA-001"
            value="{{ request('no_ticket') }}"
            required
        >

    </div>

    <button type="submit">
        Lacak Pengaduan
    </button>

    <a href="{{ url('/') }}" class="btn-home">
    ← Kembali ke Beranda
</a>

</form>


    @if(isset($pengaduan))

        <div class="hasil">

            <hr style="margin: 30px 0;">

            <h2>Hasil Pengaduan</h2>

            <div class="info">

                <p>
                    <strong>Nama Pelapor:</strong>
                    {{ $pengaduan->nama_pelapor }}
                </p>

                <p><strong>Terlapor:</strong>
                {{ $pengaduan->terlapor ?? '-' }}
            </p>

                <p>
                    <strong>Judul Pengaduan:</strong>
                    {{ $pengaduan->judul_pengaduan }}
                </p>

            </div>


            @php

                $status = strtolower(trim($pengaduan->status));

                $steps = [
                    'pengajuan' => 1,
                    'verifikasi' => 2,
                    'pendalaman' => 3,
                    'tindak lanjut' => 4,
                    'selesai' => 5,
                ];

                $currentStep = $steps[$status] ?? 1;

            @endphp


            <!-- TRACKING -->

            <div class="tracking">

                <div class="line">
                    <div
                        class="progress"
                        style="width: {{ (($currentStep - 1) / 4) * 100 }}%;">
                    </div>
                </div>


                <!-- STEP 1 -->

                <div class="step {{ $currentStep >= 1 ? 'completed' : '' }}">

                    <div class="icon">
                        📝
                    </div>

                    <div class="step-name">
                        Pengajuan
                    </div>

                </div>


                <!-- STEP 2 -->

                <div class="step {{ $currentStep >= 2 ? 'completed' : '' }}">

                    <div class="icon">
                        🔍
                    </div>

                    <div class="step-name">
                        Verifikasi
                    </div>

                </div>


                <!-- STEP 3 -->

                <div class="step {{ $currentStep >= 3 ? 'completed' : '' }}">

                    <div class="icon">
                        📋
                    </div>

                    <div class="step-name">
                        Pendalaman
                    </div>

                </div>


                <!-- STEP 4 -->

                <div class="step {{ $currentStep >= 4 ? 'completed' : '' }}">

                    <div class="icon">
                        🤝
                    </div>

                    <div class="step-name">
                        Tindak Lanjut
                    </div>

                </div>


                <!-- STEP 5 -->

                <div class="step {{ $currentStep >= 5 ? 'completed' : '' }}">

                    <div class="icon">
                        🏠
                    </div>

                    <div class="step-name">
                        Selesai
                    </div>

                </div>

            </div>


            <!-- STATUS -->

            <div class="info">

                <p>
                    <strong>Status Saat Ini:</strong>
                    {{ $pengaduan->status }}
                </p>

                @if($pengaduan->tanggapan)

                    <div class="tanggapan">

                        <strong>Tanggapan PSGA:</strong>

                        <p>
                            {{ $pengaduan->tanggapan }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

</body>
</html>