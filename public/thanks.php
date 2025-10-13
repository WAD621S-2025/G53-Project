
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$user = current_user();
view_partial_header('Thank you');
$orderId = (int)($_GET['order'] ?? 0);
?>
<div class="card">
  <h2>Thank you<?= $user ? ', ' . e($user['name']) : '' ?>!</h2>
  <p>Your order #<?= e($orderId) ?> has been placed.</p>
  <p>A receipt has been saved and an email copy attempted. You may also view it from your email client if SMTP is configured.</p>
  <p><a class="btn-primary" href="<?= BASE_URL ?>/buyer/orders.php">View my orders</a></p>
</div>
<?php view_partial_footer(); ?>
