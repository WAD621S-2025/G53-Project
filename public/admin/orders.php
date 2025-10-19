

<?php
// Admin orders page: lists all orders for review and management
require_once __DIR__ . '/../../app/bootstrap.php';
// Only allow access for admin users
require_login('ADMIN');
// Create order service and fetch all orders
$svc = new OrderService(db());
$orders = $svc->listOrders();
// Render header and navigation
view_partial_header('All Orders');
?>
<h2>All Orders</h2>
<div class="table-wrapper">
<table class="table">
  <thead><tr><th>ID</th><th>Buyer</th><th>Date</th><th>Total</th><th>Receipt</th></tr></thead>
  <tbody>
  <?php foreach ($orders as $o): ?>
    <tr>
      <td data-label="id">#<?= e($o['id']) ?></td>
      <td data-label="name"><?= e($o['buyer_name']) ?></td>
      <td data-label="date"><?= e($o['created_at']) ?></td>
      <td data-label="price">N$<?= e(number_format($o['total_amount'],2)) ?></td>
      <td data-label=""><a class="btn-secondary" href="<?= BASE_URL ?>/../storage/receipts/order_<?= $o['id'] ?>.html" target="_blank">Open</a></td>
      <!-- Order details -->
      <td>#<?= e($o['id']) ?></td>
      <td><?= e($o['buyer_name']) ?></td>
      <td><?= e($o['created_at']) ?></td>
      <td>N$<?= e(number_format($o['total_amount'],2)) ?></td>
      <!-- Link to open order receipt -->
      <td><a class="btn-secondary" href="<?= BASE_URL ?>/../storage/receipts/order_<?= $o['id'] ?>.html" target="_blank">Open</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<!-- Render footer -->
<?php view_partial_footer(); ?>
