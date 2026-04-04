<?php
session_start();
require_once __DIR__ . "/mysql.php";

if (!isset($_SESSION["user"]["id"])) {
    die(json_encode(["success" => false, "error" => "Unauthorized"]));
}

// Отримуємо дані з POST
$u_id = (int) $_SESSION["user"]["id"];
$field = isset($_POST["field"]) ? $_POST["field"] : "";
$value = isset($_POST["value"]) ? $_POST["value"] : "";

$db_field = $field;
$safe_value = $db->real_escape_string($value);

// Оновлюємо або вставляємо запис
$sql = "INSERT INTO user_settings (user_id, `$db_field`) 
        VALUES ($u_id, '$safe_value') 
        ON DUPLICATE KEY UPDATE `$db_field` = '$safe_value'";

if ($db->query($sql)) {
    echo json_encode([
        "success" => true,
        "affected" => $db->affected_rows, // 1 - вставлено новий, 2 - оновлено старий, 0 - дані ті самі
        "sql_debug" => $sql, // Виведіть це, щоб скопіювати і вставити прямо в phpMyAdmin для тесту
    ]);
} else {
    echo json_encode(["success" => false, "error" => $db->error]);
}
