

<?php
require_once __DIR__ . '/../app/bootstrap.php';

// Ensure user is logged in
$user = current_user();
if (!$user) { redirect('/login.php'); }
// Only buyers can checkout; admins are redirected
if ($user['role'] !== 'BUYER') {
    redirect('/');
}

// Initialize repositories and services
$repo = new ProductRepository(db());
$svc = new OrderService(db());

// Build cart items and calculate total
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
foreach ($cart as $pid => $qty) {
    $p = $repo->find((int)$pid);
    if ($p && $qty > 0) {
        $items[] = [
            'product_id' => (int)$pid,
            'quantity' => (int)$qty,
            'unit_price' => (float)$p['unit_price'],
            'name' => $p['name']
        ];
        $total += (float)$p['unit_price'] * (int)$qty;
    }
}
// Redirect if cart is empty
if (!$items) { redirect('/cart.php'); }

// Handle order submission
if (is_post()) {
    try {
        // Create order and get order details
        $orderId = $svc->createOrder($user['id'], $items);
        $order = $svc->getOrderWithItems($orderId);

        // Build receipt HTML
        ob_start();
        ?>
        <h2>Receipt &mdash; Order #<?= $order['id'] ?></h2>
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

        // Save receipt HTML to storage
        $dir = __DIR__ . '/../storage/receipts';
        if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
        $path = $dir . '/order_' . $order['id'] . '.html';
        file_put_contents($path, $html);

        // Optionally send receipt by email
        send_html_mail($order['buyer_email'], $order['buyer_name'], 'Your AgriPulse Receipt #' . $order['id'], $html);

        // Clear cart and redirect to thank you page
        $_SESSION['cart'] = [];
        redirect('/thanks.php?order=' . $orderId);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Render checkout page
view_partial_header('Checkout');
?>
<h2>Checkout</h2>
<?php if (!empty($error)): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<p>Review your order and confirm.</p>
<form method="post">
  <button class="btn-primary" type="submit">Place Order</button>
</form>
<?php view_partial_footer(); ?>
