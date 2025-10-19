

<?php
// Admin product creation page: allows adding a new product to the system
require_once __DIR__ . '/../../app/bootstrap.php';
// Only allow access for admin users
require_login('ADMIN');
$repo = new ProductRepository(db());
$error = null;
// Handle form submission (POST): create new product
if (is_post()) {
    $data = [
        'type' => $_POST['type'] ?? 'CROP',
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'unit' => trim($_POST['unit'] ?? 'kg'),
        'unit_price' => (float)($_POST['unit_price'] ?? 0),
        'quantity' => (int)($_POST['quantity'] ?? 0),
        'avg_weight_kg' => $_POST['avg_weight_kg'] !== '' ? (float)$_POST['avg_weight_kg'] : null,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];
    // Validate required fields
    if ($data['name'] && $data['unit'] && $data['unit_price'] > 0) {
        $repo->create($data);
        // Redirect to products list after creation
        header('Location: ' . BASE_URL . '/admin/products.php');
        exit;
    } else {
        $error = "Please provide a name, unit, and positive unit price.";
    }
}
// Render header and navigation
view_partial_header('New Product');
?>
<h2>New Product</h2>
<!-- Show error if validation fails -->
<?php if ($error): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<!-- Product creation form -->
<form method="post">
  <div class="form-row">
    <div><label>Type<br>
      <select name="type" class="input">
        <option value="CROP">CROP</option>
        <option value="LIVESTOCK">LIVESTOCK</option>
      </select></label></div>
    <div><label>Name<br><input class="input" type="text" name="name" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Description<br><input class="input" type="text" name="description"></label></div>
    <div><label>Unit (e.g., kg, head)<br><input class="input" type="text" name="unit" value="kg" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Unit Price (N$)<br><input class="input" type="number" step="0.01" min="0" name="unit_price" required></label></div>
    <div><label>Quantity<br><input class="input" type="number" min="0" name="quantity" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Avg Weight (kg, for livestock)<br><input class="input" type="number" step="0.01" name="avg_weight_kg"></label></div>
    <div><label><input type="checkbox" name="is_active" checked> Active</label></div>
  </div>
  <p><button class="btn-primary" type="submit">Create</button></p>
</form>
<!-- Render footer -->
<?php view_partial_footer(); ?>
