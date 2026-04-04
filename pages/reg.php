<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email1 = $db->real_escape_string($_POST['email1']);
	$name1 = $db->real_escape_string($_POST['name1']);
    $pass1 = $_POST['pass1'];

    require_once 'engine/password.php';
    $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

    try {
        // Спроба виконати запит
        $result = $db->query("
            INSERT INTO `users`(`id`, `email`, `password`, `name`, `date_reg`)
            VALUES (NULL, '$email1', '$hashed_password', '$name1', NOW());
        ");

        // Якщо Exception не вилетів, значить все ок
        $message = '<div style="background: #d4edda; color: #155724; padding: 15px;">
                        Реєстрація успішна! Перехід через 3 сек...
                    </div>
                    <script>setTimeout(function(){ window.location.href = "index.php"; }, 3000);</script>';
        echo $message;
        return; 

    } catch (mysqli_sql_exception $e) {
        // Ловимо помилку дубліката (код 1062)
        if ($e->getCode() == 1062) {
            $message = '<div style="color: #721c24; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb;">
                            Цей E-mail вже зареєстрований! Спробуйте інший.
                        </div>';
        } else {
            // Будь-яка інша помилка бази
            $message = '<div style="color: red; padding: 10px;">Помилка бази даних: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<div id="middle">
    <?php echo $message; ?>
    
    <p class="title">Реєстрація нового користувача</p>
    <form method="post">
        <div class="row"><label for="email1">E-mail:</label> <input id="email1" type="email" name="email1" required></div>
        <div class="row"><label for="pass1">Пароль:</label> <input id="pass1" type="password" name="pass1" required></div>
        <div class="row"><label for="name1">Ім'я:</label> <input id="name1" type="text" name="name1" required></div>
        <div class="row"><button type="submit" name="login">Створити акаунт</button></div>
    </form>
</div>