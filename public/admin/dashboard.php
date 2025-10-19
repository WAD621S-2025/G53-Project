

<?php
// Admin dashboard: main entry for admin management actions
require_once __DIR__ . '/../../app/bootstrap.php';
// Only allow access for admin users
require_login('ADMIN');
// Render header and navigation
view_partial_header('Admin Dashboard');
?>
<h2>Admin</h2>
<div class="grid">
  <!-- Card for product management -->
  <div class="card"><h3>Products</h3><p>Manage crops & livestock</p><a class="btn-primary" href="<?= BASE_URL ?>/admin/products.php">Open</a></div>
  <!-- Card for order management -->
  <div class="card"><h3>Orders</h3><p>View all orders</p><a class="btn-primary" href="<?= BASE_URL ?>/admin/orders.php">Open</a></div>
</div>
<!-- Render footer -->
<?php view_partial_footer(); ?>
