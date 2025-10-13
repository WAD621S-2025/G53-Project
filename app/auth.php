
<?php
require_once __DIR__ . '/db.php';

function start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function current_user() {
    start_session();
    return $_SESSION['user'] ?? null;
}

function require_login($role = null) {
    start_session();
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
    if ($role && $_SESSION['user']['role'] !== $role) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}

function login(string $email, string $password): bool {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        start_session();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }
    return false;
}

function logout() {
    start_session();
    $_SESSION = [];
    session_destroy();
}

function register_buyer(string $name, string $email, string $password): bool {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) return false;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "BUYER")');
    return $ins->execute([$name, $email, $hash]);
}

// Bootstraps default admin if not present
function ensure_default_admin() {
    $pdo = db();
    $stmt = $pdo->query('SELECT COUNT(*) AS c FROM users WHERE role="ADMIN"');
    $c = (int)$stmt->fetch()['c'];
    if ($c === 0) {
        $hash = password_hash('farmer1', PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "ADMIN")');
        $ins->execute(['Farmer Admin', 'farmer@agripulse.com', $hash]);
    }
}
