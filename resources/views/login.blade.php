<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/loginPage.css') }}">
</head>
<body>
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-container">
            <h2 class="text-center mb-4">Admin Login</h2>
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" 
                        name="email" 
                        required 
                        autofocus 
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        id="password" 
                        name="password" 
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-center">
                    <x-button class="w-100 w-md-50">Masuk</x-button>
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS (opsional, jika butuh komponen interaktif) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
