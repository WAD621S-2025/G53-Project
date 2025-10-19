

<?php
// Cart page: shows all items in the user's cart and allows checkout/removal
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());

// Initialize cart session if not present
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = &$_SESSION['cart'];

// Handle cart actions (add, remove, clear)
if (is_post()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        // Add item to cart (only for buyers/guests, not admins)
        $pid = (int)$_POST['product_id'];
        $qty = max(1, (int)$_POST['quantity']);
        $p = $repo->find($pid);
        $user = $_SESSION['user'] ?? null;
        $isAdmin = $user && ($user['role'] ?? '') === 'ADMIN';
        // Only add if not admin, product is active, and enough stock
        if (! $isAdmin && $p && $p['is_active'] && $qty <= $p['quantity']) {
            if (!isset($cart[$pid])) $cart[$pid] = 0;
            $cart[$pid] += $qty;
        }
    } elseif ($action === 'remove') {
        // Remove item from cart
        $pid = (int)$_POST['product_id'];
        unset($cart[$pid]);
    } elseif ($action === 'clear') {
        // Clear all items from cart
        $cart = [];
    }
    // Redirect to cart page after action
    header('Location: ' . BASE_URL . '/cart.php');
    exit;
}

// Build cart lines for display
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

// Render header and navigation
view_partial_header('Cart');
?>
<h2>Your Cart</h2>
<?php if (!$lines): ?>
  <!-- Empty cart message -->
  <p>Cart is empty.</p>
<?php else: ?>
  <div class="table-wrapper">
  <!-- Cart table -->
  <table class="table">
    <thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($lines as $ln): ?>
      <tr>
        <td data-label="name"><?= e($ln['name']) ?></td>
        <td data-label="quantity"><?= e($ln['quantity']) ?></td>
        <td data-label="price">N$<?= e(number_format($ln['unit_price'],2)) ?></td>
        <td data-label="total">N$<?= e(number_format($ln['subtotal'],2)) ?></td>
        <td data-label="">
        <td><?= e($ln['name']) ?></td>
        <td><?= e($ln['quantity']) ?></td>
        <td>N$<?= e(number_format($ln['unit_price'],2)) ?></td>
        <td>N$<?= e(number_format($ln['subtotal'],2)) ?></td>
        <td>
          <!-- Remove item button -->
          <form method="post" style="display:inline">
            <input type="hidden" name="product_id" value="<?= $ln['product_id'] ?>">
            <button class="btn-secondary" name="action" value="remove" data-confirm="Remove this item?">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <!-- Cart total row -->
      <tr><th colspan="3" style="text-align:right">Total</th><th>N$<?= e(number_format($total,2)) ?></th><th></th></tr>
    </tbody>
  </table>
  </div>
  <p>
    <!-- Clear cart button -->
    <form method="post" style="display:inline">
      <button class="btn-secondary" name="action" value="clear" data-confirm="Clear cart?">Clear</button>
    </form>
    <!-- Checkout link -->
    <a class="btn-primary" href="<?= BASE_URL ?>/checkout.php">Checkout</a>
  </p>
<?php endif; ?>
<!-- Render footer -->
<?php view_partial_footer(); ?>
