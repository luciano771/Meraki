<?php

include_once('../Models/CompradoresModel.php');
include_once('../Models/Conexion.php');

$db = new conexion();
$instancia = new CompradoresModel($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $dni_actor = $_POST["dni_actor"];
    $cantidad_entradas = $_POST["cantidad_entradas"];

    $instancia->setEmail($email);
    $instancia->setNombre($nombre);
    $instancia->setApellido($apellido);
    $instancia->setDni($dni);
    $instancia->setDni_actor($dni_actor);
    $instancia->setCantidadEntradas($cantidad_entradas);
    $clave = GenerarToken();
    $instancia->setTokenEntrada($clave);
    $id = $_POST['pk_eventos'];
    $instancia ->setFk_eventos($id);
 
        $instancia->insertarComprador($dni_actor,$id);
   
}
 





function GenerarToken() {
    $letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Letras disponibles
    $numeros = '0123456789'; // Números disponibles

    $clave = '';

    // Generar 3 letras aleatorias
    for ($i = 0; $i < 3; $i++) {
        $clave .= $letras[rand(0, strlen($letras) - 1)];
    }

    // Generar 3 números aleatorios
    for ($i = 0; $i < 3; $i++) {
        $clave .= $numeros[rand(0, strlen($numeros) - 1)];
    }

    return $clave;
}



unset($db);
unset($instancia);

?>