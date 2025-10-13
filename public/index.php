
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());
$crops = $repo->allByType('CROP');
$livestock = $repo->allByType('LIVESTOCK');
view_partial_header('AgriPulse — Home');
?>
<h2>Crops</h2>
<div class="grid">
  <?php foreach ($crops as $p): ?>
    <div class="card">
      <h3><?= e($p['name']) ?></h3>
      <p><?= e($p['description'] ?? '') ?></p>
      <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
      <p>Available: <?= e($p['quantity']) ?></p>
      <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
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
      <p>Available: <?= e($p['quantity']) ?></p>
      <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
    </div>
  <?php endforeach; ?>
</div>
<?php view_partial_footer(); ?>
