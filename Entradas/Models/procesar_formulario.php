<?php

include 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Titulo = $_POST['Titulo'];
    $Descripcion = $_POST['Descripcion'];
    $Fecha_inicio = $_POST['Fecha_inicio'];

    // Acceder al archivo subido
    $file = $_FILES["file"]["name"];


    // Verificar si no hubo errores al cargar el archivo
    if ($_FILES["file"]["error"] === 0) {
        $url_temp= $_FILES["file"]["tmp_name"];

        // Mueve el archivo del directorio temporal a la ubicación deseada

        $url_insert = dirname(__FILE__) . "/../imagenes/";
        $url_target = str_replace('\\', '/', $url_insert) . '/' . $file;

        if (!file_exists($url_insert)) {
            mkdir($url_insert, 0777, true);
        };

        if (move_uploaded_file($url_temp, $url_target)) {
            echo "El archivo " . htmlspecialchars(basename($file)) . " ha sido cargado con éxito.";
        } else {
            echo "Ha habido un error al cargar tu archivo.";
        }



        // Crear una instancia de la clase de conexión
        $db = new conexion();

        // Ahora puedes utilizar $db para interactuar con la base de datos
        // Por ejemplo, insertar estos datos en la tabla 'eventos'
        try {
            $sql = "INSERT INTO eventos (titulo, descripcion, fecha_inicio, img) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$Titulo, $Descripcion, $Fecha_inicio, $url_target]);

            echo "Evento insertado con éxito en la base de datos.";
        } catch (PDOException $e) {
            echo "Error al insertar el evento: " . $e->getMessage();
        }
    } else {
        echo "Error al cargar el archivo. Código de error: " . $archivo['error'];
    }
}

?>
