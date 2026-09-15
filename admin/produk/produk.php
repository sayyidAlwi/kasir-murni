<?php 
    session_start();
    include("../../config/database.php");
<<<<<<< HEAD

    $categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
    $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
=======
>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
?>

<?php 
    include "../../includes/header.php";
    include "../../includes/sidebar.php";
?>
<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
=======

>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
<body>
<main class="main">
    <section class="content"> 
        <div class="mb-4">
            <h2 class="page-title">
                Data Produk
            </h2>
        </div>

    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Tambah<i class="bi bi-person-plus-fill m-2"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
<<<<<<< HEAD
        <form action="create.php" method="post">
          <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="purchase_price" class="form-label">Harga Beli</label>
            <input type="number" class="form-control" id="purchase_price" name="purchase_price" required>
          </div>
          <div class="mb-3">
            <label for="selling_price" class="form-label">Harga Jual</label>
            <input type="number" class="form-control" id="selling_price" name="selling_price" required>
          </div>
          <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-select" aria-label="Default select example" name="category" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="create" class="btn btn-primary">Tambah</button>
          </div>
        </form>
=======
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
      </div>
    </div>
  </div>
</div>

        <div class="mt-2">
            <table class="table table-striped table-hover">
    <thead>
    <tr>
<<<<<<< HEAD
      <th scope="col">ID</th>
      <th scope="col">KODE PRODUK</th>
      <th scope="col">NAMA PRODUK</th>
      <th scope="col">HARGA BELI</th>
      <th scope="col">HARGA JUAL</th>
      <th scope="col">STOK</th>
      <th scope="col">KATEGORI</th>
      <th scope="col">OPSI</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $produk = mysqli_query($conn, "SELECT products.*, categories.name AS category_name
                                    FROM products
                                    INNER JOIN categories ON categories.id = products.category_id
                                    ORDER BY products.id ASC");
      $data = mysqli_fetch_all($produk, MYSQLI_ASSOC);
    ?>
    <?php foreach ($data as $datas) : ?>
    <tr>
      <th><?= $datas['id'] ?></th>
      <td><?= $datas['code'] ?></td>
      <td><?= $datas['name'] ?></td>
      <td><?= "Rp" . number_format($datas['purchase_price'], 0, ',', '.') ?></td>
      <td><?= "Rp" . number_format($datas['selling_price'], 0, ',', '.') ?></td>
      <td><?= $datas['stock'] ?></td>
      <td><?= htmlspecialchars($datas['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
      <td>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $datas['id'] ?>">
          <i class="bi bi-pencil-square"></i>
    </button>

        <div class="modal fade" id="edit<?= $datas['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $datas['id'] ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel<?= $datas['id'] ?>">Edit Produk</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="edit.php" method="post">
          <div class="mb-3">
            <input type="hidden" class="form-control" name="id" value="<?= $datas['id'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-name-<?= $datas['id'] ?>" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="edit-name-<?= $datas['id'] ?>" name="name" value="<?= htmlspecialchars($datas['name'], ENT_QUOTES, 'UTF-8') ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-purchase-price-<?= $datas['id'] ?>" class="form-label">Harga Beli</label>
            <input type="number" class="form-control" id="edit-purchase-price-<?= $datas['id'] ?>" name="purchase_price" value="<?= $datas['purchase_price'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-selling-price-<?= $datas['id'] ?>" class="form-label">Harga Jual</label>
            <input type="number" class="form-control" id="edit-selling-price-<?= $datas['id'] ?>" name="selling_price" value="<?= $datas['selling_price'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-stock-<?= $datas['id'] ?>" class="form-label">Stok</label>
            <input type="number" class="form-control" id="edit-stock-<?= $datas['id'] ?>" name="stock" value="<?= $datas['stock'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="edit-category-<?= $datas['id'] ?>" class="form-label">Kategori</label>
            <select class="form-select" aria-label="Default select example" name="category" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['id'] ?>" <?= $category['id'] == $datas['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="update" class="btn btn-primary">Edit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

        <a href="delete.php?idPelanggan=<?= $datas['id'] ?>" name="delete" class="btn btn-danger"><i class="bi bi-trash"></i></a>
      </td>
    </tr>
    <?php endforeach; ?>
=======
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>John</td>
      <td>Doe</td>
      <td>@social</td>
    </tr>
>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
  </tbody>
</table>
        </div>
    </section>
</main>
<<<<<<< HEAD
</body>
</html>
=======
</body>
>>>>>>> 5fd104489c427d5bdc1d62d4d7c79fd812daa44a
