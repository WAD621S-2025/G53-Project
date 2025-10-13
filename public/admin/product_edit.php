
<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_login('ADMIN');
$repo = new ProductRepository(db());
$id = (int)($_GET['id'] ?? 0);
$p = $repo->find($id);
if (!$p) { http_response_code(404); die('Not found'); }
$error = null;
if (is_post()) {
    $data = [
        'type' => $_POST['type'] ?? $p['type'],
        'name' => trim($_POST['name'] ?? $p['name']),
        'description' => trim($_POST['description'] ?? $p['description']),
        'unit' => trim($_POST['unit'] ?? $p['unit']),
        'unit_price' => (float)($_POST['unit_price'] ?? $p['unit_price']),
        'quantity' => (int)($_POST['quantity'] ?? $p['quantity']),
        'avg_weight_kg' => $_POST['avg_weight_kg'] !== '' ? (float)$_POST['avg_weight_kg'] : null,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];
    if ($data['name'] && $data['unit'] && $data['unit_price'] > 0) {
        $repo->update($id, $data);
        header('Location: ' . BASE_URL . '/admin/products.php');
        exit;
    } else {
        $error = "Please provide a name, unit, and positive unit price.";
    }
}
view_partial_header('Edit Product');
?>
<h2>Edit Product</h2>
<?php if ($error): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<form method="post">
  <div class="form-row">
    <div><label>Type<br>
      <select name="type" class="input">
        <option value="CROP" <?= $p['type']==='CROP'?'selected':'' ?>>CROP</option>
        <option value="LIVESTOCK" <?= $p['type']==='LIVESTOCK'?'selected':'' ?>>LIVESTOCK</option>
      </select></label></div>
    <div><label>Name<br><input class="input" type="text" name="name" value="<?= e($p['name']) ?>" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Description<br><input class="input" type="text" name="description" value="<?= e($p['description']) ?>"></label></div>
    <div><label>Unit<br><input class="input" type="text" name="unit" value="<?= e($p['unit']) ?>" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Unit Price (N$)<br><input class="input" type="number" step="0.01" min="0" name="unit_price" value="<?= e($p['unit_price']) ?>" required></label></div>
    <div><label>Quantity<br><input class="input" type="number" min="0" name="quantity" value="<?= e($p['quantity']) ?>" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Avg Weight (kg)<br><input class="input" type="number" step="0.01" name="avg_weight_kg" value="<?= e((string)$p['avg_weight_kg']) ?>"></label></div>
    <div><label><input type="checkbox" name="is_active" <?= $p['is_active']?'checked':'' ?>> Active</label></div>
  </div>
  <p><button class="btn-primary" type="submit">Save</button></p>
</form>
<?php view_partial_footer(); ?>
