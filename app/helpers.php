
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
        <div class="logo"><a href="<?= BASE_URL ?>/">AgriPulse</a></div>
            <nav class="nav">
                <ul>
                <li><a href="<?= BASE_URL ?>/">Home</a></li>
                <li><a href="<?= BASE_URL ?>/cart.php">Cart</a></li>
                <?php if ($user): ?>
                    <?php if ($user['role'] === 'BUYER'): ?>
                <li><a href="<?= BASE_URL ?>/buyer/orders.php">My Orders</a></li>
                    <?php endif; ?>
                    <?php if ($user['role'] === 'ADMIN'): ?>
                <li><a href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a></li>
                    <?php endif; ?>
                <li><div class="user">
                    <span class="welcome">Welcome, <?= e($user['name']) ?></span>
                <li><a class="btn" href="<?= BASE_URL ?>/logout.php">Logout</a></li>
                </div></li>
                <?php else: ?>
                <li><a class="btn" href="<?= BASE_URL ?>/login.php">Login</a></li>
                <li><a class="btn" href="<?= BASE_URL ?>/register.php">Sign Up</a></li>
                <?php endif; ?>
                </ul>
            </nav>
    </header>
    <main class="container">
    <?php
}

function view_partial_footer() {
    ?>
    </main>
    <footer class="footer">
      <p>&copy; <?= date('Y') ?> Empowering Farmers | 🌽AgriPulse Namibia</p>
    <button class="btn" id="theme-toggle"><span class="icon1">🌙</span><span class="icon2">☀️</span></button>
    </footer>
    </body>
    </html>
    <?php
}
