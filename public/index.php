

<?php
// Homepage: lists all crops and livestock products for buyers and guests
require_once __DIR__ . '/../app/bootstrap.php';
$repo = new ProductRepository(db());
// Get all active crops and livestock
$crops = $repo->allByType('CROP');
$livestock = $repo->allByType('LIVESTOCK');
// Get current user (if logged in)
$user = $_SESSION['user'] ?? null;
// Render header and navigation
view_partial_header('AgriPulse  Home');
?>

  <section class="hero">
  <div class="hero-content">
    <h1>Welcome to AgriPulse</h1>
    <p>Fresh from Namibian farms to your table</p>
    <a href="#products" class="btn">Explore products</a>
  </div>
</section>

<h2 id="products">🫛Crops</h2>
<div class="grid">
  <?php foreach ($crops as $p): ?>
    <div class="card">
              <img src="../public/assets/Imgs/crops.png" alt="crops illustration">
  <h3><?= e($p['name']) ?></h3>
  <p><?= e($p['description'] ?? '') ?></p>
  <div class="card-price">
  <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
  </div>

  <div class="availability">
  <p>Available: <?= e($p['quantity']) ?> <?php if ((int)$p['quantity'] <= 0): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
    </div>

    <div class="card-actions">
      <?php if ((int)$p['quantity'] > 0): ?>
        <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
<!-- Crops listing -->
<h2>Crops</h2>
<div class="grid">
  <?php foreach ($crops as $p): ?>
    <div class="card">
      <!-- Product name -->
      <h3><?= e($p['name']) ?></h3>
      <!-- Product description -->
      <p><?= e($p['description'] ?? '') ?></p>
      <!-- Unit and price -->
      <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
      <!-- Stock status -->
      <p>Available: <?= e($p['quantity']) ?> <?php if ((int)$p['quantity'] <= 0): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
      <!-- View button logic: admin goes to admin/products.php, buyers/guests go to product page, out of stock disables button -->
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
    </div>
  <?php endforeach; ?>
</div>

<h2 style="margin-top:24px">🐄Livestock</h2>
<div class="grid">
  <?php foreach ($livestock as $p): ?>
    <div class="card">
        <img src="../public/assets/Imgs/livestock.png" alt="livestock illustration">
  <h3><?= e($p['name']) ?></h3>
  <p><?= e($p['description'] ?? '') ?></p>
  <div class="card-price">
    <p><span class="badge"><?= e($p['unit']) ?></span> N$<?= e(number_format($p['unit_price'],2)) ?></p>
  </div>

  <div class="availability">
    <p>Available: <?= e($p['quantity']) ?> <?php if ((int)$p['quantity'] <= 0): ?><span style="color:#f59e0b">(Unavailable)</span><?php endif; ?></p>
  </div>

    <div>
      <?php if ((int)$p['quantity'] > 0): ?>
        <a class="btn-primary" href="<?= BASE_URL ?>/product.php?id=<?= $p['id'] ?>">View</a>
<!-- Livestock listing -->
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
    </div>
  <?php endforeach; ?>
</div>

<!-- Render footer -->
<?php view_partial_footer(); ?>
