<?php
require_once __DIR__ . "/password.php";
require_once __DIR__ . "/env.php";

$db = new mysqli(
    $_ENV["DB_HOST"],
    $_ENV["DB_USER"],
    $_ENV["DB_PASS"],
    $_ENV["DB_NAME"]
);

if ($db->connect_error) {
    die("Database error:" . $db->connect_error);
}

// Функція для очищення даних (Anti-SQL Injection)
// trim() прибирає випадкові пробіли, real_escape_string() - небезпечні символи
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
            $_SESSION["error"] = "Неправильний пароль!";
        }
    } else {
        $_SESSION["error"] = "Користувача з таким Email не існує!";
    }
}
?>
