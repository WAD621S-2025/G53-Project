
<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login('ADMIN');
$repo = new ProductRepository(db());

// Handle deletion
if (is_post() && ($_POST['action'] ?? '') === 'delete') {
    $repo->delete((int)$_POST['id']);
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit;
}

$all = array_merge($repo->allByTypeWithInactive('CROP'), $repo->allByTypeWithInactive('LIVESTOCK'));
view_partial_header('Products');
?>
<h2>Products</h2>
<p><a class="btn-primary" href="<?= BASE_URL ?>/admin/product_new.php">New Product</a></p>
<div class="table-wrapper">
<table class="table">
  <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Unit</th><th>Price</th><th>Qty</th><th>Active</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($all as $p): ?>
    <tr>
      <td data-label="id"><?= e($p['id']) ?></td>
      <td data-label="name"><?= e($p['name']) ?></td>
      <td data-label="type"><?= e($p['type']) ?></td>
      <td data-label="unit"><?= e($p['unit']) ?></td>
      <td data-label="price">N$<?= e(number_format($p['unit_price'],2)) ?></td>
      <td data-label="quantity"><?= e($p['quantity']) ?></td>
      <td data-label="active"><?= $p['is_active'] ? 'Yes' : 'No' ?></td>
      <td data-label="">
        <a class="btn-secondary" href="<?= BASE_URL ?>/admin/product_edit.php?id=<?= $p['id'] ?>">Edit</a>
        <form method="post" style="display:inline">
          <input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button class="btn-secondary" name="action" value="delete" data-confirm="Delete this product?">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php view_partial_footer(); ?>
