<?php
require 'functions.php';
need_login();

$cart = $_SESSION['cart'] ?? [];
if (!$cart) go('cart.php');

$items = [];
$subtotal = 0;
foreach ($cart as $id => $qty) {
    $s = $pdo->prepare('SELECT * FROM products WHERE id=?');
    $s->execute([$id]);
    $p = $s->fetch();
    if (!$p || $qty > $p['stock']) {
        flash('Stok salah satu produk berubah. Periksa keranjang.');
        go('cart.php');
    }
    $p['qty'] = (int)$qty;
    $p['line_total'] = $p['price'] * $p['qty'];
    $items[] = $p;
    $subtotal += $p['line_total'];
}

/* Tarif contoh untuk simulasi pengiriman wilayah Jabodetabek.
   Tarif dapat disesuaikan dengan kebijakan UMKM atau API kurir. */
$couriers = [
    'JNE REG' => 12000,
    'J&T EZ' => 12000,
    'SiCepat REG' => 11000,
    'AnterAja Reguler' => 10000
];

$selectedCourier = $_POST['courier'] ?? 'JNE REG';
if (!isset($couriers[$selectedCourier])) $selectedCourier = 'JNE REG';
$shippingCost = $couriers[$selectedCourier];
$grandTotal = $subtotal + $shippingCost;
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$method = $_POST['method'] ?? 'Transfer Bank';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    if (strlen($address) < 8 || $phone === '') {
        $error = 'Alamat lengkap dan nomor telepon wajib diisi.';
    } elseif (!isset($couriers[$_POST['courier'] ?? ''])) {
        $error = 'Silakan pilih ekspedisi yang tersedia.';
    } elseif (!in_array($method, ['Transfer Bank', 'E-Wallet'], true)) {
        $error = 'Metode pembayaran tidak valid.';
    } else {
        $selectedCourier = $_POST['courier'];
        $shippingCost = $couriers[$selectedCourier];
        $grandTotal = $subtotal + $shippingCost;

        $pdo->beginTransaction();
        try {
            $s = $pdo->prepare("INSERT INTO orders
                (user_id,total,subtotal,shipping_courier,shipping_cost,address,phone,payment_method,payment_status,order_status)
                VALUES(?,?,?,?,?,?,?,?,'menunggu_pembayaran','menunggu_pembayaran')");
            $s->execute([user()['id'], $grandTotal, $subtotal, $selectedCourier, $shippingCost, $address, $phone, $method]);
            $oid = $pdo->lastInsertId();

            $ins = $pdo->prepare('INSERT INTO order_items(order_id,product_id,product_name,price,quantity) VALUES(?,?,?,?,?)');
            $up = $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND stock>=?');
            foreach ($items as $p) {
                $ins->execute([$oid, $p['id'], $p['name'], $p['price'], $p['qty']]);
                $up->execute([$p['qty'], $p['id'], $p['qty']]);
                if (!$up->rowCount()) throw new Exception('Stok tidak cukup');
            }
            $pdo->commit();
            unset($_SESSION['cart']);
            go('payment.php?id=' . $oid);
        } catch (Throwable $e) {
            $pdo->rollBack();
            error_log('KaryaKita checkout: ' . $e->getMessage());
            $error = 'Checkout gagal diproses. Periksa kembali stok dan struktur database.';
        }
    }
}

$pageTitle = 'Checkout';
require 'header.php';
?>
<section class="section narrow">
    <h2>Checkout</h2>
    <?php if ($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?>

    <div class="panel">
        <h3>Ringkasan Produk</h3>
        <?php foreach ($items as $p): ?>
            <p><?=e($p['name'])?> × <?=e($p['qty'])?> <span style="float:right"><?=rupiah($p['line_total'])?></span></p>
        <?php endforeach; ?>
        <hr>
        <p>Subtotal produk <b style="float:right"><?=rupiah($subtotal)?></b></p>
        <p>Biaya pengiriman <b id="shipping-display" style="float:right"><?=rupiah($shippingCost)?></b></p>
        <h3 style="border-top:1px solid #eadfc9;padding-top:12px">Total pembayaran <span id="grand-total" style="float:right"><?=rupiah($grandTotal)?></span></h3>
    </div>

    <form method="post" class="form-card"><?=csrf_field()?>
        <label>Alamat lengkap (wilayah Jabodetabek)
            <textarea name="address" required><?=e($address)?></textarea>
        </label>
        <label>Nomor telepon
            <input name="phone" value="<?=e($phone)?>" required>
        </label>
        <label>Pilih ekspedisi
            <select name="courier" id="courier" required>
                <?php foreach ($couriers as $name => $fee): ?>
                    <option value="<?=e($name)?>" data-fee="<?=$fee?>" <?=$selectedCourier===$name?'selected':''?>>
                        <?=e($name)?> — <?=rupiah($fee)?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <small>Tarif di atas merupakan tarif simulasi untuk kebutuhan demonstrasi sistem di wilayah Jabodetabek, bukan tarif resmi real-time dari perusahaan ekspedisi.</small>
        <label>Metode pembayaran
            <select name="method">
                <option value="Transfer Bank" <?=$method==='Transfer Bank'?'selected':''?>>Transfer Bank</option>
                <option value="E-Wallet" <?=$method==='E-Wallet'?'selected':''?>>E-Wallet</option>
            </select>
        </label>
        <button class="btn" type="submit" name="create_order" value="1">Buat Pesanan</button>
    </form>
</section>
<script>
(function(){
    const courier = document.getElementById('courier');
    const shipping = document.getElementById('shipping-display');
    const grand = document.getElementById('grand-total');
    const subtotal = <?=json_encode((float)$subtotal)?>;
    const rupiah = n => 'Rp' + Math.round(n).toLocaleString('id-ID');
    courier.addEventListener('change', function(){
        const fee = Number(this.options[this.selectedIndex].dataset.fee || 0);
        shipping.textContent = rupiah(fee);
        grand.textContent = rupiah(subtotal + fee);
    });
})();
</script>
<?php require 'footer.php'; ?>
