

<?php
require_once __DIR__ . '/../app/bootstrap.php';

// Initialize error variable
$error = null;
// Handle login form submission
if (is_post()) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    // Attempt login; redirect on success
    if (login($email, $password)) {
        redirect('/');
    } else {
        $error = "Invalid credentials";
    }
}

// Render login page
view_partial_header('Login');
?>
<h2>Login</h2>
<?php if ($error): ?><div class="notice" style="border-color:var(--danger);color:#fecaca"><?= e($error) ?></div><?php endif; ?>
<form method="post">
  <div class="form-row">
    <div><label>Email<br><input class="input" type="email" name="email" required></label></div>
    <div><label>Password<br><input class="input" type="password" name="password" required></label></div>
  </div>
  <p><button class="btn-primary" type="submit">Login</button>
  <a class="btn-secondary" href="<?= BASE_URL ?>/register.php">Create an account</a></p>
</form>
<p>Admin demo: farmer@agripulse.com / farmer1</p>
<?php view_partial_footer(); ?>
