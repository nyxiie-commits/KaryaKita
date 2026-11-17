<?php
require 'functions.php';
need_login();
$s = $pdo->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC');
$s->execute([user()['id']]);
$orders = $s->fetchAll();
$pageTitle = 'Pesanan Saya';
require 'header.php';
?>
<section class="section">
    <h2>Pesanan Saya</h2>
    <?php foreach ($orders as $o): ?>
        <div class="order-card">
            <div><b>Pesanan #<?=e($o['id'])?></b><small><?=e($o['created_at'])?></small></div>
            <div><?=rupiah($o['total'])?></div>
            <div><span class="status"><?=e(str_replace('_', ' ', $o['payment_status']))?></span></div>
            <div>
                <?php if (in_array($o['payment_status'], ['menunggu_pembayaran', 'ditolak'], true)): ?>
                    <a class="btn small" href="<?=url('payment.php?id=' . $o['id'])?>">Bayar / Upload Bukti</a>
                <?php endif; ?>

                <?php if ($o['payment_proof']): ?>
                    <a href="<?=url('uploads/payments/' . rawurlencode($o['payment_proof']))?>" target="_blank" rel="noopener">Lihat bukti pembayaran</a>
                    <a class="btn small" href="<?=url('receipt.php?id=' . $o['id'])?>" target="_blank" rel="noopener">Cetak / Download Struk</a>
                    <?php if ($o['payment_status'] === 'menunggu_verifikasi'): ?>
                        <small style="display:block;margin-top:8px;color:#8a5a16">Struk pesanan tersedia. Pembayaran tetap menunggu pemeriksaan admin.</small>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$orders): ?><p>Belum ada pesanan.</p><?php endif; ?>
</section>
<?php require 'footer.php'; ?>
