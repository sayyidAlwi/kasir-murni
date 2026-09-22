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
                    Data supplier
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah supplier</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="create.php" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Supplier</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
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
                            <th scope="col">NAMA SUPPLIER</th>
                            <th scope="col">ALAMAT</th>
                            <th scope="col">TELEPON</th>
                            <th scope="col">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
      $supplierQuery = mysqli_query($conn, "SELECT * FROM suppliers");
      $data = mysqli_fetch_all($supplierQuery, MYSQLI_ASSOC);
    ?>
                        <?php foreach ($data as $supplier) : ?>
                        <tr>
                            <th scope="row"><?= $supplier['id'] ?></th>
                            <td><?= $supplier['name'] ?></td>
                            <td><?= $supplier['address'] ?></td>
                            <td><?= $supplier['phone'] ?></td>
                            <td>
                                <a href="edit.php" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <div class="modal fade" id="edit" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit supplier
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="edit.php" method="post">
                                                    <input type="hidden" name="id" value="<?= $supplier['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama Supplier</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="<?= $supplier['name'] ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Alamat</label>
                                                        <input type="text" class="form-control" id="address"
                                                            name="address" value="<?= $supplier['address'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Telepon</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $supplier['phone'] ?>"
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

                                <a href="delete.php?idsupplier=<?= $supplier['id'] ?>" class="btn btn-danger"><i
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