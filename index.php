<?php
session_start();

$loginError = $_SESSION['login_error'] ?? '';
$oldUsername = $_SESSION['old_username'] ?? '';
unset($_SESSION['login_error'], $_SESSION['old_username']);
?>

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

                        <?php if ($loginError !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Form Login -->
                        <form id="loginForm" action="auth/login.php" method="POST" novalidate>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username
                                </label>

                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?= htmlspecialchars($oldUsername, ENT_QUOTES, 'UTF-8') ?>"
                                    placeholder="Masukkan username" maxlength="50" autocomplete="username" required>
                                <div id="usernameError" class="invalid-feedback"></div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                </label>

                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan password" minlength="8" autocomplete="current-password"
                                    aria-describedby="passwordError" required>
                                <div id="passwordError" class="invalid-feedback"></div>
                            </div>


                            <!-- Button -->
                            <button type="submit" class="btn btn-primary w-100">
                                Login
                            </button>
                        </form>

                        

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
    

    <!-- <script>
        (function () {
            const form = document.getElementById("loginForm");
            const usernameInput = document.getElementById("username");
            const passwordInput = document.getElementById("password");
            const usernameError = document.getElementById("usernameError");
            const passwordError = document.getElementById("passwordError");

            function setFieldError(input, errorElement, message) {
                const hasError = message !== "";
                input.classList.toggle("is-invalid", hasError);
                input.setAttribute("aria-invalid", hasError ? "true" : "false");
                errorElement.textContent = message;
            }

            function validasi() {
                const username = usernameInput.value.trim();
                const password = passwordInput.value;
                let isValid = true;

                if (username === "") {
                    setFieldError(usernameInput, usernameError, "Username wajib diisi.");
                    isValid = false;
                } else if (username.length > 50) {
                    setFieldError(usernameInput, usernameError, "Username maksimal 50 karakter.");
                    isValid = false;
                } else {
                    setFieldError(usernameInput, usernameError, "");
                }

                if (password === "") {
                    setFieldError(passwordInput, passwordError, "Password wajib diisi.");
                    isValid = false;
                } else if (password.length < 8) {
                    setFieldError(passwordInput, passwordError, "Password minimal 8 karakter.");
                    isValid = false;
                } else {
                    setFieldError(passwordInput, passwordError, "");
                }

                return isValid;
            }

            form.addEventListener("submit", function (event) {
                if (!validasi()) {
                    event.preventDefault();

                    const firstInvalid = form.querySelector(".is-invalid");
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
            });

            [usernameInput, passwordInput].forEach(function (input) {
                input.addEventListener("input", function () {
                    if (input.classList.contains("is-invalid")) {
                        validasi();
                    }
                });
            });
        }());
    </script> -->
</body>

</html>