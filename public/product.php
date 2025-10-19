

<?php
// Product detail page: shows info and purchase option for a single product
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());
// Get product ID from query string
$id = (int)($_GET['id'] ?? 0);
// Fetch product from DB
$p = $repo->find($id);
// If product not found, show 404
if (!$p) { http_response_code(404); die('Not found'); }
// If the product exists but is not active, show the page but mark it unavailable
$isAvailable = (bool)$p['is_active'];
// Get current user (if logged in)
$user = $_SESSION['user'] ?? null;
// Render header and navigation
view_partial_header('Product: ' . $p['name']);
?>

<div class="product-page">
  <div class="product-details card">
<div class="card">
  <!-- Product name -->
  <h2><?= e($p['name']) ?></h2>
  <!-- Product description -->
  <p><?= e($p['description'] ?? '') ?></p>
  <!-- Product unit -->
  <p>Unit: <span class="badge"><?= e($p['unit']) ?></span></p>
  <!-- Product price -->
  <p>Price: <strong>N$<?= e(number_format($p['unit_price'],2)) ?></strong></p>
  <!-- Stock status -->
  <p>Available: <?= e($p['quantity']) ?> <?php if (!$isAvailable): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
  <?php if ($isAvailable && $p['quantity'] > 0): ?>
  </div>
</div>

<div class="product-actions">
  <!-- Show Add to Cart form only if product is available, in stock, and user is not admin -->
  <?php if ($isAvailable && $p['quantity'] > 0 && (! $user || ($user['role'] ?? '') !== 'ADMIN')): ?>
  <form method="post" action="<?= BASE_URL ?>/cart.php">
    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
    <label>Quantity<br><input class="input" type="number" name="quantity" min="1" max="<?= $p['quantity'] ?>" value="1" required></label>
    <p><button class="btn-primary" type="submit" name="action" value="add">Add to Cart</button></p>
  </form>
  <?php else: ?>
    <!-- Disabled button if not available, out of stock, or admin -->
    <p><button class="btn-primary" disabled>Out of stock / Unavailable</button></p>
  <?php endif; ?>
</div>
<!-- Render footer -->
<?php view_partial_footer(); ?>
