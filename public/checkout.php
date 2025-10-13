
<?php
require_once __DIR__ . '/../app/bootstrap.php';

$user = current_user();
if (!$user) { redirect('/login.php'); }
if ($user['role'] !== 'BUYER') {
    // buyers only checkout; admins can test by creating a buyer account
    redirect('/');
}

$repo = new ProductRepository(db());
$svc = new OrderService(db());

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
foreach ($cart as $pid => $qty) {
    $p = $repo->find((int)$pid);
    if ($p && $qty > 0) {
        $items[] = ['product_id'=>(int)$pid,'quantity'=>(int)$qty,'unit_price'=>(float)$p['unit_price'],'name'=>$p['name']];
        $total += (float)$p['unit_price'] * (int)$qty;
    }
}
if (!$items) { redirect('/cart.php'); }

if (is_post()) {
    try {
        $orderId = $svc->createOrder($user['id'], $items);
        // build receipt
        $order = $svc->getOrderWithItems($orderId);
        ob_start();
        ?>
        <h2>Receipt — Order #<?= $order['id'] ?></h2>
        <p>Buyer: <?= e($order['buyer_name']) ?> (<?= e($order['buyer_email']) ?>)</p>
        <p>Date: <?= e($order['created_at']) ?></p>
        <table border="1" cellpadding="6" cellspacing="0">
          <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
          <?php foreach ($order['items'] as $it): ?>
            <tr>
              <td><?= e($it['name']) ?></td>
              <td><?= e($it['quantity']) ?></td>
              <td>N$<?= e(number_format($it['unit_price'],2)) ?></td>
              <td>N$<?= e(number_format($it['unit_price']*$it['quantity'],2)) ?></td>
            </tr>
          <?php endforeach; ?>
          <tr><th colspan="3" align="right">Total</th><th>N$<?= e(number_format($order['total_amount'],2)) ?></th></tr>
        </table>
        <?php
        $html = ob_get_clean();

        // save receipt HTML
        $dir = __DIR__ . '/../storage/receipts';
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        $path = $dir . '/order_' . $order['id'] . '.html';
        file_put_contents($path, $html);

        // email (optional, per config)
        send_html_mail($order['buyer_email'], $order['buyer_name'], 'Your AgriPulse Receipt #' . $order['id'], $html);

        // clear cart
        $_SESSION['cart'] = [];
        redirect('/thanks.php?order=' . $orderId);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

view_partial_header('Checkout');
?>
<h2>Checkout</h2>
<?php if (!empty($error)): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<p>Review your order and confirm.</p>
<form method="post">
  <button class="btn-primary" type="submit">Place Order</button>
</form>
<?php view_partial_footer(); ?>
