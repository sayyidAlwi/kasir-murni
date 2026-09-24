<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$userId = (int) $_SESSION['id'];
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $username === '') {
        $message = 'Nama dan username wajib diisi.';
        $messageType = 'danger';
    } else {
        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $statement = mysqli_prepare($conn, 'UPDATE users SET name = ?, username = ?, password = ? WHERE id = ?');
            mysqli_stmt_bind_param($statement, 'sssi', $name, $username, $hashedPassword, $userId);
        } else {
            $statement = mysqli_prepare($conn, 'UPDATE users SET name = ?, username = ? WHERE id = ?');
            mysqli_stmt_bind_param($statement, 'ssi', $name, $username, $userId);
        }

        if (mysqli_stmt_execute($statement)) {
            $message = 'Profil berhasil diperbarui.';
        } else {
            $message = 'Profil gagal diperbarui. Username mungkin sudah digunakan.';
            $messageType = 'danger';
        }

        mysqli_stmt_close($statement);
    }
}

$userResult = mysqli_query($conn, "SELECT name, username, role FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($userResult);

if (!$user) {
    $message = 'Sesi pengguna tidak valid. Silakan logout lalu login kembali.';
    $messageType = 'danger';
    $user = [
        'name' => '',
        'username' => '',
        'role' => '',
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - KasirKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/sidebar.php'; ?>

    <main class="main">
        <section class="content">
            <div class="mb-4">
                <h2 class="page-title">Profil</h2>
                <p class="page-subtitle mb-0">Kelola informasi akun kamu.</p>
            </div>

            <div class="dashboard-card" style="max-width: 720px;">
                <div class="card-header">
                    <i class="bi bi-person me-2"></i>Informasi Akun
                </div>
                <div class="card-body">
                    <?php if ($message !== ''): ?>
                        <div class="alert alert-<?= $messageType ?>" role="alert">
                            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label" for="name">Nama</label>
                            <input class="form-control" type="text" id="name" name="name" value="<?= $user['name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password Baru</label>
                            <input class="form-control" type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input class="form-control" type="text" value="<?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check2 me-1"></i>Simpan Profil
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
