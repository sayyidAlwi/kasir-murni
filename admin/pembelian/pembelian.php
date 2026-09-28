<?php
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.php');
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : '';
$dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : '';

$suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name ASC");
$products = mysqli_query($conn, "SELECT id, code, name, purchase_price FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <main class="main">
        <section class="content">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="mb-4">
                    <h2 class="page-title">Data Pembelian</h2>
                    <p>Kelola dan pantau pembelian</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit">
                        Tambah Pembelian<i class="bi bi-person-plus-fill m-2"></i>
                    </button>

                    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pembelian</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="create.php" method="post" id="purchaseForm">
                                        <div class="mb-3">
                                            <label for="supplier" class="form-label">Supplier</label>
                                            <select class="form-select" id="supplier" name="supplier_id" required>
                                                <option value="">Pilih Supplier</option>
                                                <?php while ($supplier = mysqli_fetch_assoc($suppliers)) : ?>
                                                    <option value="<?= (int) $supplier['id'] ?>"><?= htmlspecialchars($supplier['name']) ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="date" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date" name="date" required>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label mb-0">Produk</label>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">Tambah Produk</button>
                                            </div>

                                            <div id="purchaseItems">
                                                <div class="purchase-item border rounded p-3 mb-3">
                                                    <div class="row g-2 align-items-end">
                                                        <div class="col-md-5">
                                                            <label class="form-label">Produk</label>
                                                            <select class="form-select product-select" name="items[0][product_id]" required>
                                                                <option value="">Pilih Produk</option>
                                                                <?php
                                                                mysqli_data_seek($products, 0);
                                                                while ($product = mysqli_fetch_assoc($products)) :
                                                                ?>
                                                                    <option value="<?= (int) $product['id'] ?>" data-price="<?= (float) $product['purchase_price'] ?>">
                                                                        <?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['code']) ?>)
                                                                    </option>
                                                                <?php endwhile; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Qty</label>
                                                            <input type="number" class="form-control qty-input" name="items[0][quantity]" min="1" value="1" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Harga Beli</label>
                                                            <input type="number" class="form-control price-input" name="items[0][price]" min="0" step="0.01" value="0" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">Subtotal</label>
                                                            <div class="fw-bold subtotal-label">Rp 0</div>
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item mt-2">Hapus</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                            <span class="fw-semibold">Total Pembelian</span>
                                            <span id="purchaseTotal" class="fw-bold fs-5">Rp 0</span>
                                            <input type="hidden" name="total" id="totalInput" value="0">
                                        </div>

                                        <div class="modal-footer mt-3">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="create" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <form action="" method="GET" class="d-flex flex-column gap-3 w-100">
                    <div class="search-wrapper flex-grow-1">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" id="searchInput" class="search-input" placeholder="Cari kode atau supplier..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
                    </div>

                    <div class="date-filter">
                        <div class="date-group">
                            <label for="dateFrom">Dari</label>
                            <input type="date" name="dateFrom" id="dateFrom" value="<?= htmlspecialchars($dateFrom) ?>">
                        </div>

                        <div class="date-group">
                            <label for="dateTo">Sampai</label>
                            <input type="date" name="dateTo" id="dateTo" value="<?= htmlspecialchars($dateTo) ?>">
                        </div>

                        <button type="submit" class="filter-button btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">KODE</th>
                            <th scope="col">SUPPLIER</th>
                            <th scope="col">TANGGAL</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">OPSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $where = [];

                        if ($search !== '') {
                            $searchEsc = mysqli_real_escape_string($conn, $search);
                            $where[] = "(p.invoice_number LIKE '%{$searchEsc}%' OR s.name LIKE '%{$searchEsc}%')";
                        }

                        if ($dateFrom !== '') {
                            $dateFromEsc = mysqli_real_escape_string($conn, $dateFrom);
                            $where[] = "DATE(p.created_at) >= '$dateFromEsc'";
                        }

                        if ($dateTo !== '') {
                            $dateToEsc = mysqli_real_escape_string($conn, $dateTo);
                            $where[] = "DATE(p.created_at) <= '$dateToEsc'";
                        }

                        $sql = "SELECT p.id, p.invoice_number, p.total, p.created_at, s.name AS supplier_name
                                FROM purchases p
                                INNER JOIN suppliers s ON p.supplier_id = s.id";

                        if (!empty($where)) {
                            $sql .= " WHERE " . implode(' AND ', $where);
                        }

                        $sql .= " ORDER BY p.created_at DESC";
                        $query = mysqli_query($conn, $sql);

                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                                <tr>
                                    <th><?= htmlspecialchars($data['invoice_number']) ?></th>
                                    <td><?= htmlspecialchars($data['supplier_name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($data['created_at'])) ?></td>
                                    <td><?= 'Rp' . number_format((float) $data['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="detail.php?idPembelian=<?= (int) $data['id'] ?>" class="btn btn-primary p-1">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data pembelian</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        function formatRupiah(value) {
            return 'Rp ' + Number(value).toLocaleString('id-ID');
        }

        function updatePurchaseTotal() {
            let total = 0;
            document.querySelectorAll('.purchase-item').forEach((item) => {
                const qty = parseFloat(item.querySelector('.qty-input')?.value || 0);
                const price = parseFloat(item.querySelector('.price-input')?.value || 0);
                const subtotal = qty * price;
                item.querySelector('.subtotal-label').textContent = formatRupiah(subtotal);
                total += subtotal;
            });

            document.getElementById('purchaseTotal').textContent = formatRupiah(total);
            document.getElementById('totalInput').value = total;
        }

        document.getElementById('addItemBtn')?.addEventListener('click', () => {
            const container = document.getElementById('purchaseItems');
            const count = container.querySelectorAll('.purchase-item').length;
            const template = `
                <div class="purchase-item border rounded p-3 mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Produk</label>
                            <select class="form-select product-select" name="items[${count}][product_id]" required>
                                <option value="">Pilih Produk</option>
                                <?php
                                mysqli_data_seek($products, 0);
                                while ($product = mysqli_fetch_assoc($products)) :
                                ?>
                                    <option value="<?= (int) $product['id'] ?>" data-price="<?= (float) $product['purchase_price'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['code']) ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Qty</label>
                            <input type="number" class="form-control qty-input" name="items[${count}][quantity]" min="1" value="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" class="form-control price-input" name="items[${count}][price]" min="0" step="0.01" value="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal</label>
                            <div class="fw-bold subtotal-label">Rp 0</div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item mt-2">Hapus</button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
            bindItemEvents();
            updatePurchaseTotal();
        });

        function bindItemEvents() {
            document.querySelectorAll('.qty-input, .price-input').forEach((input) => {
                input.oninput = updatePurchaseTotal;
            });

            document.querySelectorAll('.product-select').forEach((select) => {
                select.onchange = function () {
                    const selected = this.options[this.selectedIndex];
                    const parentItem = this.closest('.purchase-item');
                    const priceInput = parentItem.querySelector('.price-input');
                    const price = Number(selected.dataset.price || 0);
                    priceInput.value = price;
                    updatePurchaseTotal();
                };
            });

            document.querySelectorAll('.remove-item').forEach((button) => {
                button.onclick = function () {
                    const item = this.closest('.purchase-item');
                    if (document.querySelectorAll('.purchase-item').length > 1) {
                        item.remove();
                        updatePurchaseTotal();
                    }
                };
            });
        }

        bindItemEvents();
        updatePurchaseTotal();
    </script>
</body>

</html>