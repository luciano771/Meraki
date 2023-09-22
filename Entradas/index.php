<?php
// Cargar el controlador y procesar la solicitud
include 'controllers/eventosControlador.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $controller = new LoginController();
    $controller->processLogin();
} else {
    $controller = new LoginController();
    $controller->showLoginForm();
}
?>