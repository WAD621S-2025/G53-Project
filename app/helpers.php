

<?php
// Redirects the user to a given path and exits script execution
function redirect(string $path) {
    header('Location: ' . BASE_URL . $path);
    exit;
}

// Returns the full asset URL for a given relative path
function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

// Escapes a string for safe HTML output
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// Returns true if the current request is a POST
function is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

// Renders the HTML header and navigation bar
// Shows different nav links depending on user role
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
            <!-- Home link always visible -->
            <a href="<?= BASE_URL ?>/">Home</a>
            <!-- Cart link only for buyers and guests -->
            <?php if (!$user || ($user['role'] ?? '') === 'BUYER'): ?>
                <a href="<?= BASE_URL ?>/cart.php">Cart</a>
            <?php endif; ?>
            <?php if ($user): ?>
                <!-- Buyer-only orders link -->
                <?php if ($user['role'] === 'BUYER'): ?>
                    <a href="<?= BASE_URL ?>/buyer/orders.php">My Orders</a>
                <?php endif; ?>
                <!-- Admin dashboard link -->
                <?php if ($user['role'] === 'ADMIN'): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <!-- Welcome message and logout for logged-in users -->
                <span class="welcome">Welcome, <?= e($user['name']) ?></span>
                <a class="btn" href="<?= BASE_URL ?>/logout.php">Logout</a>
            <?php else: ?>
                <!-- Login and signup for guests -->
                <a class="btn" href="<?= BASE_URL ?>/login.php">Login</a>
                <a class="btn" href="<?= BASE_URL ?>/register.php">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">
    <?php
}

// Renders the HTML footer
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
