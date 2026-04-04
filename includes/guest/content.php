<?php
$page = isset($_GET["page"]) ? $_GET["page"] : "";

switch ($page) {
    case "":	
        require_once "pages/welcome.php";
        break;
    case "login":
        require_once "pages/auth.php";
        break;
    case "register":
        require_once "pages/reg.php";
        break;
    case "logout":
        session_destroy();
		header("Location: /");
        break;
    default:
        http_response_code(404);
        echo "Сторінки немає (404)";
        break;
}

?>
