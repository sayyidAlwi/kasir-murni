<?php 
    session_start();
    include("../../config/database.php");
?>

<?php 
    include "../../includes/header.php";
    include "../../includes/sidebar.php";
?>

<body>
    <main class="main">
        <section class="content">
            <div class="mb-4">
                <h2 class="page-title">
                    Data Pengguna
                </h2>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Tambah<i class="bi bi-person-plus-fill m-2"></i>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah pengguna</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="create.php" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Role</label>
                                    <select class="form-select" name="role" id="">
                                        <option value="kasir">Kasir</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="create" class="btn btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">USERNAME</th>
                            <th scope="col">PASSWORD</th>
                            <th scope="col">AKSES</th>
                            <th scope="col">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
      $penggunaQuery = mysqli_query($conn, "SELECT * FROM users");
      $data = mysqli_fetch_all($penggunaQuery, MYSQLI_ASSOC);
    ?>
                        <?php foreach ($data as $pengguna) : ?>
                        <tr>
                            <th scope="row"><?= $pengguna['id'] ?></th>
                            <td><?= $pengguna['name'] ?></td>
                            <td><?= $pengguna['username'] ?></td>
                            <td><?= md5($pengguna['password']) ?></td>
                            <td><?= $pengguna['role'] ?></td>
                            <td>
                                <a href="edit.php" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <div class="modal fade" id="edit" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Pengguna
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="edit.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $pengguna['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="<?= $pengguna['name'] ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" id="username"
                                                            name="username" value="<?= $pengguna['username'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="phone" name="password" value="<?= $pengguna['password'] ?>"
                                                            required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update"
                                                            class="btn btn-primary">Tambah</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="delete.php?idpengguna=<?= $pengguna['id'] ?>" class="btn btn-danger"><i
                                        class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>