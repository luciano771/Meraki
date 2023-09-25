<?php

require '../vendor/autoload.php'; // Carga la biblioteca Spout
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
    
include 'Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Titulo = $_POST['Titulo'];
    $Descripcion = $_POST['Descripcion'];
    $Fecha_inicio = $_POST['Fecha_inicio'];
    $Fecha_fin = $_POST['Fecha_fin'];

    // Crea una fecha en formato "yyyy-mm-dd"
    $Fecha_inicio_Guardarbase = date('Y-m-d', strtotime(str_replace('/', '-', $Fecha_inicio)));
    $Fecha_fin_Guardarbase = date('Y-m-d', strtotime(str_replace('/', '-', $Fecha_fin)));

    // Acceder al archivo subido
    $file = $_FILES["file"]["name"];
    


    // Verificar si no hubo errores al cargar el archivo
    if ($_FILES["file"]["error"] === 0) {
        $url_temp= $_FILES["file"]["tmp_name"];

        // Mueve el archivo del directorio temporal a la ubicación deseada

        $url_insert = dirname(__FILE__) . "/../imagenes";
        $url_target = str_replace('\\', '/', $url_insert) . '/' . $file;

        if (!file_exists($url_insert)) {
            mkdir($url_insert, 0777, true);
        };

        if (move_uploaded_file($url_temp, $url_target)) {
            echo "El archivo " . htmlspecialchars(basename($file)) . " ha sido cargado con éxito.";
        } else {
            echo "Ha habido un error al cargar tu archivo.";
        }

        $url_base = '../imagenes/' . $_FILES["file"]["name"];



        // Crear una instancia de la clase de conexión
        $db = new conexion();

        // Ahora puedes utilizar $db para interactuar con la base de datos
        // Por ejemplo, insertar estos datos en la tabla 'eventos'
        try {
            $sql = "INSERT INTO eventos (titulo, descripcion, fecha_inicio, fecha_fin, img) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$Titulo, $Descripcion, $Fecha_inicio_Guardarbase,$Fecha_fin_Guardarbase, $url_base]);
            echo "ARCHIVO insertarDO";

         } catch (PDOException $e) {
            echo "Error al insertar el evento: " . $e->getMessage();
        }
    } else {
        echo "Error al cargar el archivo. Código de error: " . $archivo['error'];
    }


}

 function getpk_eventos(){
    try {
        // Crear una instancia de la conexión a la base de datos
        $db = new conexion();
 
        // Consulta SQL para obtener los valores de la columna "pk_eventos"
        $consulta = "SELECT pk_eventos FROM eventos ORDER BY pk_eventos DESC LIMIT 1";
        $stmt = $db->query($consulta);
       
        // Obtener el resultado como un valor único (la última pk_eventos)
        $pk_eventos = $stmt->fetchColumn();
        
        // Cerrar la conexión a la base de datos
        $db = null;
        
        // Devolver los valores de pk_eventos
        return $pk_eventos;
    } catch (PDOException $e) {
        // En caso de error en la conexión o consulta
        echo 'Error: ' . $e->getMessage();
        return null; // Puedes manejar el error de alguna manera adecuada
    }
}
 
 
    
    $xlsxFilePath = $_FILES["archivo"]["tmp_name"]; // Reemplaza con la ruta de tu archivo XLSX
     
    $reader = ReaderEntityFactory::createXLSXReader();
    $reader->open($xlsxFilePath);
    
    $pk_eventos = getpk_eventos();
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {              
                $nombres = $row->getCellAtIndex(0)->getValue(); // Columna A
                $apellido = $row->getCellAtIndex(1)->getValue(); // Columna B
                $dni = $row->getCellAtIndex(2)->getValue(); // Columna C

                try {
                    $sql = "INSERT INTO actores (nombre,apellido,dni,fk_eventos) VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$nombres, $apellido,$dni,$pk_eventos]);
                    
                    header('Location: ../Views/Eventos.html');
                } catch (PDOException $e) {
                    echo "Error al insertar el evento: " . $e->getMessage();
                }
               
    
        }
        
        
    }
    
    
    $reader->close();
    
 


?>
