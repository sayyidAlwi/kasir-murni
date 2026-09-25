
<?php 
    session_start();
    include "../config/database.php";

    if (!isset($_SESSION['id'])) {
        header('Location: ../auth/login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $rawInput = file_get_contents('php://input');
        $payload = json_decode($rawInput, true);

        if (!is_array($payload) || ($payload['action'] ?? '') !== 'checkout') {
            echo json_encode(['success' => false, 'message' => 'Permintaan checkout tidak valid.']);
            exit;
        }

        $cart = is_array($payload['cart'] ?? null) ? $payload['cart'] : [];
        $paymentAmount = isset($payload['payment_amount']) ? (float) $payload['payment_amount'] : 0;
        $userId = (int) ($_SESSION['id'] ?? 0);

        $response = ['success' => false, 'message' => 'Transaksi gagal.'];

        if ($userId <= 0 || empty($cart)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang masih kosong.']);
            exit;
        }

        $items = [];
        $total = 0.00;

        foreach ($cart as $item) {
            $productId = (int) ($item['id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            $price = isset($item['price']) ? (float) $item['price'] : 0;

            if ($productId <= 0 || $quantity <= 0 || $price < 0) {
                continue;
            }

            $productResult = mysqli_query($conn, "SELECT id, stock, selling_price, name FROM products WHERE id = $productId LIMIT 1");
            $product = mysqli_fetch_assoc($productResult);

            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
                exit;
            }

            if ((int) $product['stock'] < $quantity) {
                echo json_encode(['success' => false, 'message' => 'Stok produk ' . htmlspecialchars($product['name']) . ' tidak mencukupi.']);
                exit;
            }

            $price = (float) $product['selling_price'];
            $subTotal = $price * $quantity;
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subTotal,
            ];
            $total += $subTotal;
        }

        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang tidak valid.']);
            exit;
        }

        if ($paymentAmount < $total) {
            echo json_encode(['success' => false, 'message' => 'Uang yang dibayarkan belum mencukupi total transaksi.']);
            exit;
        }

        $invoiceNumber = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
        $changeAmount = $paymentAmount - $total;

        mysqli_begin_transaction($conn);

        try {
            $transactionSql = "INSERT INTO transactions (invoice_number, user_id, customer_id, total, paid, change_amount, created_at)
                VALUES (?, ?, NULL, ?, ?, ?, NOW())";
            $transactionStmt = mysqli_prepare($conn, $transactionSql);

            if (!$transactionStmt) {
                throw new Exception('Gagal menyiapkan transaksi.');
            }

            mysqli_stmt_bind_param($transactionStmt, 'siddd', $invoiceNumber, $userId, $total, $paymentAmount, $changeAmount);

            if (!mysqli_stmt_execute($transactionStmt)) {
                throw new Exception('Gagal menyimpan transaksi.');
            }

            $transactionId = mysqli_insert_id($conn);

            foreach ($items as $item) {
                $detailStmt = mysqli_prepare($conn, "INSERT INTO transaction_details (transaction_id, product_id, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
                if (!$detailStmt) {
                    throw new Exception('Gagal menyiapkan detail transaksi.');
                }

                mysqli_stmt_bind_param($detailStmt, 'iiddd', $transactionId, $item['product_id'], $item['price'], $item['quantity'], $item['subtotal']);

                if (!mysqli_stmt_execute($detailStmt)) {
                    throw new Exception('Gagal menyimpan detail produk.');
                }

                $stockUpdate = mysqli_query($conn, "UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['product_id']} AND stock >= {$item['quantity']}");
                if (!$stockUpdate || mysqli_affected_rows($conn) <= 0) {
                    throw new Exception('Stok produk tidak berhasil diperbarui.');
                }
            }

            mysqli_commit($conn);

            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'invoice_number' => $invoiceNumber,
                'total' => $total,
                'change' => $changeAmount,
            ]);
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
    ?>

<!DOCTYPE html>
<html lang="id">

<body>

    <?php
    include("includes/header.php");
    ?>
    <?php
    include("includes/sidebar.php");
    ?>

    

<main class="main">
    <section class="content">
    <header class="topbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="searchProduct"
                    placeholder="Cari produk... (nama / kode)"
                >

            </div>
        </header>


        <!-- CONTENT -->
        <div class="content-wrapper">

            <!-- BAGIAN PRODUK -->
            <section class="product-section">

                <h4 class="section-title">
                    Kategori
                </h4>


                <!-- CATEGORY -->
                <div class="category-list">

                    <button
                        class="category-btn active"
                        data-category="Semua"
                    >
                        <i class="bi bi-grid"></i>
                        <span>Semua</span>
                    </button>


                    <button
                        class="category-btn"
                        data-category="Makanan"
                    >
                        <i class="bi bi-egg-fried"></i>
                        <span>Makanan</span>
                    </button>


                    <button
                        class="category-btn"
                        data-category="Minuman"
                    >
                        <i class="bi bi-cup-hot"></i>
                        <span>Minuman</span>
                    </button>


                    <button
                        class="category-btn"
                        data-category="Snack"
                    >
                        <i class="bi bi-cookie"></i>
                        <span>Snack</span>
                    </button>


                    <button
                        class="category-btn"
                        data-category="Lainnya"
                    >
                        <i class="bi bi-three-dots"></i>
                        <span>Lainnya</span>
                    </button>

                </div>


                <!-- PRODUCT HEADER -->
                <div class="product-header">

                    <h4 class="section-title mb-0">
                        Produk
                    </h4>

                    <div class="view-button">

                        <button class="active">
                            <i class="bi bi-grid"></i>
                        </button>

                        <button>
                            <i class="bi bi-list"></i>
                        </button>

                    </div>

                </div>


                <!-- PRODUCT GRID -->
                <div
                    class="product-grid"
                    id="productContainer"
                >

                <?php 

                    $query = mysqli_query($conn, "SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");
                    $produk = mysqli_fetch_all($query, MYSQLI_ASSOC);

                ?>

                    <?php foreach ($produk as $item): ?>

                        <div
                            class="product-card"
                            data-category="<?= $item['category_name']; ?>"
                            data-name="<?= strtolower($item['name']); ?>"
                            data-code="<?= strtolower($item['code']); ?>"
                        >

                            <div class="product-info">

                                <h6>
                                    <?= $item['name']; ?>
                                </h6>

                                <small>
                                    <?= $item['code']; ?>
                                </small>

                                <div class="product-price">
                                    Rp <?= number_format($item['selling_price'], 0, ',', '.'); ?>
                                </div>

                            </div>


                            <button
                                class="btn-add"
                                onclick='addToCart(
                                    <?= json_encode($item); ?>
                                )'
                            >
                                <i class="bi bi-plus-lg"></i>
                                Tambah
                            </button>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>


            <!-- ================= CART ================= -->
            <aside class="cart-section">

                <div class="cart-header">

                    <h4>
                        <i class="bi bi-cart3"></i>
                        Keranjang Penjualan
                    </h4>

                    <button
                        class="btn-clear"
                        onclick="clearCart()"
                    >
                        <i class="bi bi-trash"></i>
                        Hapus Semua
                    </button>

                </div>


                <!-- CART ITEMS -->
                <div
                    class="cart-items"
                    id="cartItems"
                >

                    <div
                        class="empty-cart"
                        id="emptyCart"
                    >

                        <i class="bi bi-cart3"></i>

                        <h6>
                            Belum ada produk
                        </h6>

                        <p>
                            Pilih produk dari daftar di sebelah kiri
                            untuk memulai transaksi.
                        </p>

                    </div>

                </div>


                <!-- SUMMARY -->
                <div class="cart-summary">

                    <div class="summary-row">

                        <span>
                            Total Item
                        </span>

                        <strong id="totalItem">
                            0
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong id="subtotal">
                            Rp 0
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Diskon
                        </span>

                        <strong id="discount">
                            Rp 0
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="total-row">

                        <span>
                            Total
                        </span>

                        <strong id="total">
                            Rp 0
                        </strong>

                    </div>


                    <button
                        class="btn-payment"
                        onclick="openPayment()"
                    >
                        <i class="bi bi-credit-card"></i>
                        Bayar
                        <span>(F1)</span>
                    </button>

                </div>

            </aside>

        </div>

    </main>

</div>


<!-- ================= PAYMENT MODAL ================= -->

<div
    class="modal fade"
    id="paymentModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Pembayaran
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <div class="payment-total">

                    <small>
                        Total Pembayaran
                    </small>

                    <h2 id="paymentTotal">
                        Rp 0
                    </h2>

                </div>


                <label class="form-label">
                    Uang Dibayar
                </label>

                <input
                    type="number"
                    class="form-control form-control-lg"
                    id="paymentAmount"
                    placeholder="Masukkan uang..."
                >


                <div class="change-box">

                    <span>
                        Kembalian
                    </span>

                    <strong id="change">
                        Rp 0
                    </strong>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="processPayment()"
                >
                    Proses Pembayaran
                </button>

            </div>

        </div>

    </div>

</div>
                    </section>
                    </main>

<script>
const cart = [];

function addToCart(product) {
    const productId = Number(product.id);
    const existingItem = cart.find((item) => Number(item.id) === productId);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: product.name,
            code: product.code,
            price: Number(product.selling_price || 0),
            quantity: 1,
        });
    }

    renderCart();
}

function updateQty(productId, delta) {
    const item = cart.find((entry) => Number(entry.id) === Number(productId));
    if (!item) {
        return;
    }

    item.quantity += delta;

    if (item.quantity <= 0) {
        const index = cart.findIndex((entry) => Number(entry.id) === Number(productId));
        cart.splice(index, 1);
    }

    renderCart();
}

function clearCart() {
    cart.length = 0;
    renderCart();
    const paymentAmount = document.getElementById('paymentAmount');
    if (paymentAmount) {
        paymentAmount.value = '';
    }
    const change = document.getElementById('change');
    if (change) {
        change.textContent = 'Rp 0';
    }
}

function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);
}

function showPaymentNotification(message, type) {
    const oldNotification = document.getElementById('paymentNotification');
    if (oldNotification) {
        oldNotification.remove();
    }

    const notification = document.createElement('div');
    notification.id = 'paymentNotification';
    notification.className = `payment-notification ${type === 'success' ? 'notification-success' : 'notification-error'}`;
    notification.innerHTML = `
        <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    setTimeout(function () {
        notification.classList.add('notification-hide');
        setTimeout(function () {
            notification.remove();
        }, 300);
    }, 3000);
}

function renderCart() {
    const cartItems = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    const totalItem = document.getElementById('totalItem');
    const subtotal = document.getElementById('subtotal');
    const total = document.getElementById('total');
    const paymentTotal = document.getElementById('paymentTotal');

    if (!cartItems) {
        return;
    }

    if (!cart.length) {
        cartItems.innerHTML = '';
        if (emptyCart) {
            emptyCart.style.display = 'block';
        }
        if (totalItem) totalItem.textContent = '0';
        if (subtotal) subtotal.textContent = 'Rp 0';
        if (total) total.textContent = 'Rp 0';
        if (paymentTotal) paymentTotal.textContent = 'Rp 0';
        return;
    }

    if (emptyCart) {
        emptyCart.style.display = 'none';
    }

    const totalQuantity = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    cartItems.innerHTML = cart.map((item) => `
        <div class="cart-item">
            <div class="cart-item-main">
                <div>
                    <h6>${item.name}</h6>
                    <small>${item.code}</small>
                </div>
                <strong>${formatRupiah(item.price * item.quantity)}</strong>
            </div>

            <div class="cart-item-actions">
                <button type="button" class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                <span>${item.quantity}</span>
                <button type="button" class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
            </div>
        </div>
    `).join('');

    if (totalItem) totalItem.textContent = String(totalQuantity);
    if (subtotal) subtotal.textContent = formatRupiah(totalPrice);
    if (total) total.textContent = formatRupiah(totalPrice);
    if (paymentTotal) paymentTotal.textContent = formatRupiah(totalPrice);

    const paymentAmount = document.getElementById('paymentAmount');
    if (paymentAmount) {
        const amount = Number(paymentAmount.value || 0);
        const change = document.getElementById('change');
        if (change) {
            change.textContent = formatRupiah(Math.max(0, amount - totalPrice));
        }
    }
}

function openPayment() {
    if (!cart.length) {
        showPaymentNotification('Keranjang masih kosong.', 'error');
        return;
    }

    const paymentAmount = document.getElementById('paymentAmount');
    const paymentModalEl = document.getElementById('paymentModal');
    if (paymentAmount) {
        paymentAmount.value = '';
    }

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const paymentTotal = document.getElementById('paymentTotal');
    if (paymentTotal) {
        paymentTotal.textContent = formatRupiah(total);
    }

    const change = document.getElementById('change');
    if (change) {
        change.textContent = 'Rp 0';
    }

    if (paymentModalEl && window.bootstrap && bootstrap.Modal) {
        const paymentModal = bootstrap.Modal.getOrCreateInstance(paymentModalEl);
        paymentModal.show();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const paymentAmount = document.getElementById('paymentAmount');
    if (paymentAmount) {
        paymentAmount.addEventListener('input', function () {
            const amount = Number(this.value || 0);
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const change = document.getElementById('change');
            if (change) {
                change.textContent = formatRupiah(Math.max(0, amount - total));
            }
        });
    }
});

function processPayment() {
    if (!cart.length) {
        showPaymentNotification('Keranjang masih kosong.', 'error');
        return;
    }

    const paymentAmount = Number(document.getElementById('paymentAmount')?.value || 0);
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (!paymentAmount) {
        showPaymentNotification('Masukkan jumlah uang yang dibayarkan.', 'error');
        return;
    }

    if (paymentAmount < total) {
        showPaymentNotification('Uang yang dibayarkan belum mencukupi total transaksi.', 'error');
        return;
    }

    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
            action: 'checkout',
            payment_amount: paymentAmount,
            cart: cart,
        }),
    })
        .then(async (response) => {
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Transaksi gagal diproses.');
            }

            showPaymentNotification('Transaksi berhasil! No. Invoice: ' + data.invoice_number, 'success');

            if (window.bootstrap && bootstrap.Modal) {
                const modalEl = document.getElementById('paymentModal');
                const modal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
                if (modal) {
                    modal.hide();
                }
            }

            clearCart();
            document.getElementById('paymentAmount').value = '';
            document.getElementById('change').textContent = 'Rp 0';
        })
        .catch((error) => {
            showPaymentNotification(error.message || 'Terjadi kesalahan saat memproses transaksi.', 'error');
        });
}
</script>