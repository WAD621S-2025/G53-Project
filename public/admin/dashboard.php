
<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login('ADMIN');
view_partial_header('Admin Dashboard');
?>
<h2>Admin</h2>
<div class="grid">
  <div class="card"><h3>Products</h3><p>Manage crops & livestock</p><a class="btn-primary" href="<?= BASE_URL ?>/admin/products.php">Open</a></div>
  <div class="card"><h3>Orders</h3><p>View all orders</p><a class="btn-primary" href="<?= BASE_URL ?>/admin/orders.php">Open</a></div>
</div>
<?php view_partial_footer(); ?>
