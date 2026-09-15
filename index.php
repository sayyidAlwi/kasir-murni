<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Kasir</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .login-container {
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
        }

        .login-icon {
            width: 70px;
            height: 70px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center login-container">

            <div class="col-12">

                <div class="card shadow login-card mx-auto">
                    <div class="card-body p-2">

                        <!-- Icon -->
                        <div class="login-icon">
                            DKV
                        </div>

                        <!-- Judul -->
                        <h3 class="text-center fw-bold mb-1">
                            Login
                        </h3>

                        <p class="text-center text-muted mb-4">
                            Sistem Kasir
                        </p>

                        <!-- Form Login -->
                        <form action="auth/login.php" method="POST">

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username
                                </label>

                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukkan username" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                </label>

                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password" required>
                            </div>


                            <!-- Button -->
                            <button type="submit" class="btn btn-primary w-100">
                                Login
                            </button>
                        </form>

                        <div class="d-flex justify-content-center pt-2">
                            <a class="link-opacity-100" href="#">Registrasi</a>
                        </div>

                    </div>

                    <div class="card-footer text-center bg-white border-0 pb-2">
                        <small class="text-muted">
                            © 2026 Sistem Kasir
                        </small>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>