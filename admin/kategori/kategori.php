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
                    Data Kategori
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="create.php" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
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
                            <th scope="col">KATEGORI</th>
                            <th scope="col">JUMLAH PRODUK</th>
                            <th scope="col">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
      $kategoriQuery = mysqli_query($conn, "SELECT c.id, c.name, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id, c.name");
      $data = mysqli_fetch_all($kategoriQuery, MYSQLI_ASSOC);
    ?>
                        <?php foreach ($data as $kategori) : ?>
                        <tr>
                            <th scope="row"><?= $kategori['id'] ?></th>
                            <td><?= $kategori['name'] ?></td>
                            <td><?= $kategori['product_count'] ?></td>
                            <td>
                                <a href="edit.php" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <div class="modal fade" id="edit" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit kategori
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="edit.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $kategori['id'] ?>">
                                                    <div class="mb-3">
                                                        <input type="hidden" class="form-control" id="name" name="name"
                                                            value="<?= $kategori['name'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Kategori</label>
                                                        <input type="text" class="form-control" id="address"
                                                            name="address" required>
                                                    </div>
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

                                <a href="delete.php?idKategori=<?= $kategori['id'] ?>" class="btn btn-danger"><i
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