
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());
$crops = $repo->allByType('CROP');
$livestock = $repo->allByType('LIVESTOCK');
$user = $_SESSION['user'] ?? null;
view_partial_header('AgriPulse  Home');
?>
<h2>Crops</h2>
<div class="grid">
  <?php foreach ($crops as $p): ?>
    <div class="card">
  <h3><?= e($p['name']) ?></h3>
  <p><?= e($p['description'] ?? '') ?></p>
  <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
  <p>Available: <?= e($p['quantity']) ?> <?php if ((int)$p['quantity'] <= 0): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
      <?php if ($user && ($user['role'] ?? '') === 'ADMIN'): ?>
        <a class="btn-primary" href="<?= BASE_URL ?>/admin/products.php">View</a>
      <?php else: ?>
        <?php if ((int)$p['quantity'] > 0): ?>
          <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
        <?php else: ?>
          <button class="btn-primary" disabled>Out of stock</button>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

<h2 style="margin-top:24px">Livestock</h2>
<div class="grid">
  <?php foreach ($livestock as $p): ?>
    <div class="card">
  <h3><?= e($p['name']) ?></h3>
  <p><?= e($p['description'] ?? '') ?></p>
  <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
  <p>Available: <?= e($p['quantity']) ?> <?php if ((int)$p['quantity'] <= 0): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
      <?php if ($user && ($user['role'] ?? '') === 'ADMIN'): ?>
        <a class="btn-primary" href="<?= BASE_URL ?>/admin/products.php">View</a>
      <?php else: ?>
        <?php if ((int)$p['quantity'] > 0): ?>
          <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
        <?php else: ?>
          <button class="btn-primary" disabled>Out of stock</button>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
<?php view_partial_footer(); ?>
