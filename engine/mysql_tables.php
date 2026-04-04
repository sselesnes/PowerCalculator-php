<?php
// Визначаємо корінь, якщо файл викликано напряму через fetch/AJAX
if (!defined("ROOT_PATH")) {
    define("ROOT_PATH", realpath(__DIR__ . "/.."));
}

// Обов'язково запускаємо сесію (для перевірки $u_id)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Підключаємо базу (використовуємо ROOT_PATH для надійності)
require_once ROOT_PATH . "/engine/mysql.php";

// Перевірка авторизації
if (!isset($_SESSION["user"]["id"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        die(json_encode(["success" => false, "error" => "Unauthorized"]));
    }
    return; // Якщо це звичайний include в content.php
}
$u_id = (int) $_SESSION["user"]["id"];

// Завантаження даних для відображення на сторінці
$res = $db->query("SELECT * FROM user_settings WHERE user_id = $u_id");
$user_set = $res ? $res->fetch_assoc() : null;

if (!$user_set) {
    $user_set = [
        "bat_type" => "LiFePO4",
        "bat_voltage" => 12,
        "bat_capacity" => 105,
        "bat_temp" => 25,
        "inv_power" => 2000,
        "inv_eff" => 92,
        "inv_peak" => 4000,
    ];
}

$res_apps = $db->query("SELECT * FROM user_appliances WHERE user_id = $u_id");
$appliances = [];
if ($res_apps) {
    while ($row = $res_apps->fetch_assoc()) {
        $appliances[] = $row;
    }
}

// Збереження даних (для AJAX)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["field"])) {
    $field = $db->real_escape_string($_POST["field"]);
    $value = $db->real_escape_string($_POST["value"]);

    $sql = "INSERT INTO user_settings (user_id, `$field`) 
            VALUES ($u_id, '$value') 
            ON DUPLICATE KEY UPDATE `$field` = '$value'";

    if ($db->query($sql)) {
        // echo json_encode(["success" => true, "sql" => $sql]);
        echo json_encode([
            "success" => true,
            "affected" => $db->affected_rows, // 1 - вставлено новий, 2 - оновлено старий, 0 - дані ті самі
            "sql_debug" => $sql, // Виведіть це, щоб скопіювати і вставити прямо в phpMyAdmin для тесту
        ]);
    } else {
        echo json_encode(["success" => false, "error" => $db->error]);
    }
    exit();
}

// Додавання приладу
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_appliance"])) {
    $name = $db->real_escape_string($_POST["name"]);
    $rated = (int) $_POST["rated_power"];
    $peak = (int) $_POST["peak_power"];
    $hours = (float) $_POST["daily_hours"];

    $sql = "INSERT INTO user_appliances (user_id, name, rated_power, peak_power, daily_hours) 
            VALUES ($u_id, '$name', $rated, $peak, $hours)";

    if ($db->query($sql)) {
        echo json_encode(["success" => true, "new_id" => $db->insert_id]);
    } else {
        echo json_encode(["success" => false, "error" => $db->error]);
    }
    exit();
}

// Видалення приладу
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
    $del_id = (int) $_POST["delete_id"];

    // Важливо: $u_id має бути визначений вище у файлі
    $sql = "DELETE FROM user_appliances WHERE id = $del_id AND user_id = $u_id";

    if ($db->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $db->error]);
    }
    exit();
}
?>
