

<?php
// Admin products page: lists all products (active and inactive) for management
require_once __DIR__ . '/../../app/bootstrap.php';
// Only allow access for admin users
require_login('ADMIN');
$repo = new ProductRepository(db());

// Handle product deletion (POST request)
if (is_post() && ($_POST['action'] ?? '') === 'delete') {
    $repo->delete((int)$_POST['id']);
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit;
}

// Get all crops and livestock (including inactive)
$all = array_merge($repo->allByTypeWithInactive('CROP'), $repo->allByTypeWithInactive('LIVESTOCK'));
// Render header and navigation
view_partial_header('Products');
?>
<h2>Products</h2>
<!-- Link to create a new product -->
<p><a class="btn-primary" href="<?= BASE_URL ?>/admin/product_new.php">New Product</a></p>
<table class="table">
  <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Unit</th><th>Price</th><th>Qty</th><th>Active</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($all as $p): ?>
    <tr>
      <!-- Product details -->
      <td><?= e($p['id']) ?></td>
      <td><?= e($p['name']) ?></td>
      <td><?= e($p['type']) ?></td>
      <td><?= e($p['unit']) ?></td>
      <td>N$<?= e(number_format($p['unit_price'],2)) ?></td>
      <td><?= e($p['quantity']) ?></td>
      <td><?= $p['is_active'] ? 'Yes' : 'No' ?></td>
      <td>
        <!-- Edit and delete actions -->
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
<!-- Render footer -->
<?php view_partial_footer(); ?>
