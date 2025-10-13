
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = &$_SESSION['cart'];

if (is_post()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $pid = (int)$_POST['product_id'];
        $qty = max(1, (int)$_POST['quantity']);
        $p = $repo->find($pid);
        if ($p && $p['is_active'] && $qty <= $p['quantity']) {
            if (!isset($cart[$pid])) $cart[$pid] = 0;
            $cart[$pid] += $qty;
        }
    } elseif ($action === 'remove') {
        $pid = (int)$_POST['product_id'];
        unset($cart[$pid]);
    } elseif ($action === 'clear') {
        $cart = [];
    }
    header('Location: ' . BASE_URL . '/cart.php');
    exit;
}

// build cart lines
$lines = [];
$total = 0;
foreach ($cart as $pid => $qty) {
    $p = $repo->find((int)$pid);
    if ($p) {
        $line = [
            'product_id' => (int)$p['id'],
            'name' => $p['name'],
            'unit_price' => (float)$p['unit_price'],
            'quantity' => (int)$qty,
            'subtotal' => (float)$p['unit_price'] * (int)$qty
        ];
        $total += $line['subtotal'];
        $lines[] = $line;
    }
}

view_partial_header('Cart');
?>
<h2>Your Cart</h2>
<?php if (!$lines): ?>
  <p>Cart is empty.</p>
<?php else: ?>
  <table class="table">
    <thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($lines as $ln): ?>
      <tr>
        <td><?= e($ln['name']) ?></td>
        <td><?= e($ln['quantity']) ?></td>
        <td>N$<?= e(number_format($ln['unit_price'],2)) ?></td>
        <td>N$<?= e(number_format($ln['subtotal'],2)) ?></td>
        <td>
          <form method="post" style="display:inline">
            <input type="hidden" name="product_id" value="<?= $ln['product_id'] ?>">
            <button class="btn-secondary" name="action" value="remove" data-confirm="Remove this item?">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <tr><th colspan="3" style="text-align:right">Total</th><th>N$<?= e(number_format($total,2)) ?></th><th></th></tr>
    </tbody>
  </table>
  <p>
    <form method="post" style="display:inline">
      <button class="btn-secondary" name="action" value="clear" data-confirm="Clear cart?">Clear</button>
    </form>
    <a class="btn-primary" href="<?= BASE_URL ?>/checkout.php">Checkout</a>
  </p>
<?php endif; ?>
<?php view_partial_footer(); ?>
