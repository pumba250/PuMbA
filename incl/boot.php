<?php
// Простой способ сделать глобально доступным подключение в БД
function pdo(): PDO
{
    static $pdo;

    if (!$pdo) {
        $config = include __DIR__.'/db.php';
        // Подключение к БД
        $pdo = new PDO($dsn, $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}
function flash(?string $message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
          <div class="alert alert-danger mb-3">
              <?=$_SESSION['flash']?>
          </div>
        <?php }
        unset($_SESSION['flash']);
    }
}
function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}