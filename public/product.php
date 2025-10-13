
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());
$id = (int)($_GET['id'] ?? 0);
$p = $repo->find($id);
if (!$p || !$p['is_active']) { http_response_code(404); die('Not found'); }
view_partial_header('Product: ' . $p['name']);
?>
<div class="card">
  <h2><?= e($p['name']) ?></h2>
  <p><?= e($p['description'] ?? '') ?></p>
  <p>Unit: <span class="badge"><?= e($p['unit']) ?></span></p>
  <p>Price: <strong>N$<?= e(number_format($p['unit_price'],2)) ?></strong></p>
  <p>Available: <?= e($p['quantity']) ?></p>
  <form method="post" action="<?= BASE_URL ?>/cart.php">
    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
    <label>Quantity<br><input class="input" type="number" name="quantity" min="1" max="<?= $p['quantity'] ?>" value="1" required></label>
    <p><button class="btn-primary" type="submit" name="action" value="add">Add to Cart</button></p>
  </form>
</div>
<?php view_partial_footer(); ?>
