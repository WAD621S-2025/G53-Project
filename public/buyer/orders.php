
<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login('BUYER');
$svc = new OrderService(db());
$orders = $svc->listOrders($_SESSION['user']['id']);
view_partial_header('My Orders');
?>
<h2>My Orders</h2>
<?php if (!$orders): ?>
  <p>No orders yet.</p>
<?php else: ?>
<table class="table">
  <thead><tr><th>ID</th><th>Date</th><th>Total</th><th>Receipt</th></tr></thead>
  <tbody>
  <?php foreach ($orders as $o): ?>
    <tr>
      <td>#<?= e($o['id']) ?></td>
      <td><?= e($o['created_at']) ?></td>
      <td>N$<?= e(number_format($o['total_amount'],2)) ?></td>
      <td><a class="btn-secondary" href="<?= BASE_URL ?>/../storage/receipts/order_<?= $o['id'] ?>.html" target="_blank">Open</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
<?php view_partial_footer(); ?>
