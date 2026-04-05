<?php
require_once ROOT_PATH . "/engine/mysql.php";
if (!isset($message)) {
    $message = "";
}
?>

<div id="middle">
    <p class="title">Реєстрація нового користувача</p>
    
    <?php if (strpos($message, "успішна") === false): ?>
    <form method="post">
        <div class="row"><label for="name1">Ім'я:</label> <input id="name1" type="text" name="name1" required value="<?php echo isset(
            $_POST["name1"]
        )
            ? htmlspecialchars($_POST["name1"])
            : ""; ?>"></div>
        <div class="row">
            <label for="email1">E-mail:</label> 
            <input id="email1" type="email" name="email1" required value="<?php echo isset(
                $_POST["email1"]
            )
                ? htmlspecialchars($_POST["email1"])
                : ""; ?>">
        </div>
        <div class="row"><label for="pass1">Пароль:</label> <input id="pass1" type="password" name="pass1" required></div>        
        <div class="row"><button type="submit" name="login">Створити акаунт</button></div>
    </form>
    <?php endif; ?>
    
    <?php echo $message; ?>
</div>