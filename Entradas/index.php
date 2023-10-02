<?php
include_once ("Models/SessionesModel.php");
$db = new conexion();
$instancia = new SessionesModel($db);
$instancia->InsertarSession();
header('Location: Views/Eventos.html');
unset($instancia);
unset($db);
?>