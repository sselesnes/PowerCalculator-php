<?php
require_once "password.php"; // немає хешування паролів у PHP5.40
require_once "env.php"; // підтримка .env

// Підключаємося до сервера без вибору бази даних
$db_server = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);

if ($db_server->connect_error) {
    die("Помилка підключення до сервера: " . $db_server->connect_error);
}

// Створюємо базу даних, якщо її не існує
$db_server->query(
    "CREATE DATABASE IF NOT EXISTS `{$_ENV["DB_NAME"]}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci"
);
$db_server->close();

$db = new mysqli(
    $_ENV["DB_HOST"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"],
    $_ENV["DB_NAME"]
);

if ($db->connect_error) {
    die("Database error:" . $db->connect_error);
}

// Таблиця користувачів
$db->query("CREATE TABLE IF NOT EXISTS `users` (
    `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `name` varchar(50) NOT NULL,
    `date_reg` date NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Таблиця налаштувань
$db->query("CREATE TABLE IF NOT EXISTS `user_settings` (
    `user_id` int(11) NOT NULL,
    `bat_type` varchar(20) DEFAULT 'LiFePO4',
    `bat_voltage` int(11) DEFAULT 12,
    `bat_capacity` int(11) DEFAULT 105,
    `bat_temp` int(11) DEFAULT 25,
    `inv_power` int(11) DEFAULT 2000,
    `inv_eff` float DEFAULT 92,
    `inv_peak` int(11) DEFAULT 4000,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Таблиця приладів
$db->query("CREATE TABLE IF NOT EXISTS `user_appliances` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `rated_power` INT(11) DEFAULT 0,
  `peak_power` INT(11) DEFAULT 0,
  `daily_hours` FLOAT DEFAULT 0,
  INDEX (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

// Очищення даних (Anti-SQL Injection): trim() прибирає випадкові пробіли, real_escape_string() - небезпечні символи
$email = isset($_POST["email"])
    ? $db->real_escape_string(trim($_POST["email"]))
    : "";
$pass = isset($_POST["pass"]) ? $_POST["pass"] : ""; // Пароль НЕ екрануємо, бо він не йде в SQL

// Обробка виходу (Logout)
if (isset($_GET["user"]) && $_GET["user"] == "logout") {
    session_destroy();
    header("Location: /");
    exit();
}

// Обробка входу (Login)
if (isset($_POST["sign"])) {
    // Екрануємо дані перед використанням у запиті
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $db->query($sql);

    if ($result && $result->num_rows == 1) {
        $user_info = $result->fetch_assoc();

        // Хеш з бази vs введений пароль
        if (password_verify($pass, $user_info["password"])) {
            // Успіх! Записуємо дані в сесію
            $_SESSION["user"]["id"] = $user_info["id"];
            $_SESSION["user"]["name"] = $user_info["name"];

            header("Location: /");
            exit();
        } else {
            $_SESSION["error"] = "Недійсний пароль!";
        }
    } else {
        $_SESSION["error"] = "Користувач з таким E-mail не зареєстрований!";
    }
}

// Реєстрація нового користувача
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email1 = $db->real_escape_string($_POST["email1"]);
    $name1 = $db->real_escape_string($_POST["name1"]);
    $pass1 = $_POST["pass1"];

    $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `users`(`id`, `email`, `password`, `name`, `date_reg`)
            VALUES (NULL, '$email1', '$hashed_password', '$name1', NOW())";

    // Якщо успіх - одразу входимо
    try {
        $result = $db->query($sql);
        if ($result) {
            $message =
                '
    <div class="auth-msg">
        <p>Вітаємо, ' .
                htmlspecialchars($name1) .
                '!</p>
        <p>Акаунт створено. Вхід через 3 секунди...</p>
        <div class="loader"></div> 
    </div>
    <script>
        setTimeout(function() {             
            window.location.href = "index.php?reg_success=' .
                $db->insert_id .
                "&name=" .
                urlencode($name1) .
                '"; 
        }, 3000);
    </script>';
        }
    } catch (Exception $e) {
        // помилка PHP>5.4
        if ($db->errno == 1062) {
            $message =
                '<div class="auth-err"><p>Цей E-mail вже зареєстрований!</p></div>';
        } else {
            $message =
                '<div class="auth-err"><p>Помилка реєстрації: ' .
                $db->error .
                "</p></div>";
        }
    }
} ?>
