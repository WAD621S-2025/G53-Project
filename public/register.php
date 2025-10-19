
<?php
require_once __DIR__ . '/../app/bootstrap.php';
$error = null;
$ok = null;
if (is_post()) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (strlen($name) < 2) $error = "Please enter your full name.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = "Invalid email.";
    elseif (strlen($password) < 6) $error = "Password must be at least 6 characters.";
    else {
        if (register_buyer($name, $email, $password)) {
            $ok = "Account created! You can log in now.";
        } else {
            $error = "Email already registered.";
        }
    }
}
view_partial_header('Register');
?>
<h2>Create Buyer Account</h2>
<?php if ($error): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="notice" style="border-color:var(--ok);color:#bbf7d0"><?= e($ok) ?></div><?php endif; ?>
<form method="post">
  <div class="form-row">
    <div><label>Full Name <span style="color:red">*</span><br><input class="input" type="text" name="name" required></label></div>
    <div><label>Email <span style="color:red">*</span><br><input class="input" type="email" name="email" required></label></div>
  </div>
  <div class="form-row">
    <div><label>Password <span style="color:red">*</span><br><input class="input" type="password" name="password" required></label></div>
  </div>
  <p><button class="btn-secondary" type="submit">Create Account</button></p>
</form>
<?php view_partial_footer(); ?>
