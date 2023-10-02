<?php

include '../Models/SessionesModel.php';
include '../Models/EventosModel.php';
$db = new conexion();
$instancia = new EventosModel($db);
$instancia2 = new SessionesModel($db);
  
if (isset($_GET['consultarEventos']) && $_GET['consultarEventos'] == 'true') {
    $instancia->ObtenerEventos();
} 
if (isset($_POST['activo']) && $_POST['activo'] == 'no') {
     $instancia2->BorrarSession();
    session_destroy();
} 

if (isset($_GET['ESTADOSESSION']) && $_GET['ESTADOSESSION'] == 'ESTADO') {
    if (session_status() == PHP_SESSION_ACTIVE) {
        echo 'Ya posee una sesión activa.';
    } else {
        header('Location: ../index.php');
    }
}


if (isset($_GET['VerificarOrden']) && $_GET['VerificarOrden'] == 'true') {
     $sessionOrden = $instancia2->SessionFilas();
    if($sessionOrden){
        $resultado = 'true';
        
    }else{
        $resultado = 'false';
    }

    echo trim($resultado);
     
} 
 
unset($db);
unset($instancia);
unset($instancia2);
 

?>
