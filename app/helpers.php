
<?php
function redirect(string $path) {
    header('Location: ' . BASE_URL . $path);
    exit;
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function view_partial_header(string $title = 'AgriPulse') {
    $user = $_SESSION['user'] ?? null;
    ?>
    <!doctype html>
    <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= e($title) ?></title>
      <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
      <script defer src="<?= asset('js/app.js') ?>"></script>
    </head>
    <body>
    <header class="header">
        <div class="brand"><a href="<?= BASE_URL ?>/">AgriPulse</a></div>
        <nav class="nav">
            <a href="<?= BASE_URL ?>/">Home</a>
            <a href="<?= BASE_URL ?>/cart.php">Cart</a>
            <?php if ($user): ?>
                <?php if ($user['role'] === 'BUYER'): ?>
                    <a href="<?= BASE_URL ?>/buyer/orders.php">My Orders</a>
                <?php endif; ?>
                <?php if ($user['role'] === 'ADMIN'): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <span class="welcome">Welcome, <?= e($user['name']) ?></span>
                <a class="btn" href="<?= BASE_URL ?>/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn" href="<?= BASE_URL ?>/login.php">Login</a>
                <a class="btn" href="<?= BASE_URL ?>/register.php">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">
    <?php
}

function view_partial_footer() {
    ?>
    </main>
    <footer class="footer">
      <p>&copy; <?= date('Y') ?> AgriPulse Namibia</p>
    </footer>
    </body>
    </html>
    <?php
}
