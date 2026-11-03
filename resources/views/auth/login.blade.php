<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | PSGA UIN Jakarta</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #fff4fb, #f8e8f4);
            color: #4a1645;
        }

        .login-card {
            width: 100%;
            max-width: 410px;
            padding: 36px 32px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(112, 20, 94, 0.13);
            text-align: center;
        }

        .mascot {
            display: block;
            width: 155px;
            height: 155px;
            object-fit: contain;
            margin: 0 auto 12px;
        }

        h1 {
            margin: 8px 0;
            color: #a20d7b;
            font-size: 27px;
        }

        .subtitle {
            margin: 0 0 28px;
            color: #887184;
            font-size: 14px;
        }

        .field {
            margin-bottom: 18px;
            text-align: left;
        }

        .field label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #e4d5e1;
            border-radius: 11px;
            outline: none;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input:focus {
            border-color: #c21b94;
            box-shadow: 0 0 0 3px rgba(194, 27, 148, 0.1);
        }

        .error {
            margin-top: 6px;
            color: #d62839;
            font-size: 12px;
        }

        .alert {
            padding: 11px;
            margin-bottom: 18px;
            border-radius: 9px;
            background: #fff0f2;
            color: #b42336;
            font-size: 13px;
            text-align: left;
        }

        .login-button {
            width: 100%;
            margin-top: 5px;
            padding: 14px;
            border: none;
            border-radius: 11px;
            background: #b51288;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }

        .login-button:hover {
            background: #8f0d6c;
        }

        .footer {
            margin-top: 24px;
            color: #9b8498;
            font-size: 12px;
            line-height: 1.7;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 28px 22px;
            }

            .mascot {
                width: 130px;
                height: 130px;
            }
        }
    </style>
</head>
<body>

    <main class="login-card">

        <img
            src="{{ asset('images/kucing-psga-.png') }}"
            alt="Maskot Kucing PSGA"
            class="mascot"
        >

        <h1>Welcome Back!</h1>
        <p class="subtitle">Login untuk melanjutkan ke Admin PSGA</p>

        @if ($errors->any())
            <div class="alert">
                Username atau password salah. Silakan periksa kembali.
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="field">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    required
                    autofocus
                >

                @error('username')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="login-button">
                Login Admin
            </button>
        </form>

        <div class="footer">
            Pusat Studi Gender dan Anak<br>
            UIN Syarif Hidayatullah Jakarta
        </div>

    </main>

</body>
</html>