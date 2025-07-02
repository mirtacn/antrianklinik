<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Mabarrot Hasyimiyah Manyar Gresik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
</head>
<body style="background: url('{{ asset('admin/img/background.png') }}') no-repeat center center fixed; background-size: cover;">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4">
            <div class="d-flex align-items-center mb-4">
                <img src="admin/img/logo.png" alt="Logo" class="me-3" style="height: 50px;">
                <h1 class="h4 mb-0" style="font-weight: bold; font-size: 20px;">Klinik Mabarrot Hasyimiyah Manyar Gresik</h1>
            </div>
            <h2 class="h5 mb-3 text-center" style="color: #75A288; font-weight: bold; font-size: 25px;">Silahkan Login</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password :</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
                <button type="submit" class="btn btn-submit w-100">Login</button>
                {{-- <p style="margin-top: 10px;" class="text-end">
                    Belum punya akun? <a href="{{ route('register') }}" class="register-link">Registrasi</a>
                </p> --}}
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
