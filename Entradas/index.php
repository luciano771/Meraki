<?php
// Cargar el controlador y procesar la solicitud
include 'Controllers/eventosControlador.php';


$controller = new PaginaPrincial();
$controller->showPaginaPrincial(); // sino muestro la pagina eventos a los clientes

?>