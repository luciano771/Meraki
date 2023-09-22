<?php
// Cargar el controlador y procesar la solicitud
include 'Controllers/eventosControlador.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $controller = new LoginController();
    $controller->processLogin(); //aca tengo que mostrar el panel de admin
} else {
    $controller = new LoginController();
    $controller->showLoginForm(); // sino muestro la pagina eventos a los clientes
}
?>