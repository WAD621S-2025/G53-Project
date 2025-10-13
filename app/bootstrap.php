
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/ProductRepository.php';
require_once __DIR__ . '/OrderService.php';

start_session();
ensure_default_admin();
