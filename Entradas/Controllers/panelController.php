<?php
require '../vendor/autoload.php'; // Carga la biblioteca Spout
 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include '../Models/EventosModel.php';
include '../Models/ActoresModel.php';
 

$db = new conexion();
$instancia = new EventosModel($db);
$instancia2 = new ActoresModel($db);

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST['Titulo']) && isset($_POST['Descripcion']) && isset($_POST['Fecha_inicio']) && isset($_POST['Fecha_fin']) && isset($_FILES["file"]) && isset($_FILES['archivo'])) {
            
            $Titulo = $_POST['Titulo'];
            $Descripcion = $_POST['Descripcion'];
            $Fecha_inicio = $_POST['Fecha_inicio'];
            $Fecha_fin = $_POST['Fecha_fin'];
            $Fecha_inicio_Guardarbase = date('Y-m-d', strtotime(str_replace('/', '-', $Fecha_inicio)));
            $Fecha_fin_Guardarbase = date('Y-m-d', strtotime(str_replace('/', '-', $Fecha_fin)));

    
            $file = $_FILES["file"]["name"];
            $url_temp= $_FILES["file"]["tmp_name"];
            



            if ($_FILES["file"]["error"] === 0) {
                $url_temp= $_FILES["file"]["tmp_name"];
        
                // Mueve el archivo del directorio temporal a la ubicación deseada
        
                $url_insert = dirname(__FILE__) . "/../imagenes";
                $url_target = str_replace('\\', '/', $url_insert) . '/' . $file;
        
                if (!file_exists($url_insert)) {
                    mkdir($url_insert, 0777, true);
                };
        
                if (move_uploaded_file($url_temp, $url_target)) {

                    try {
                        $instancia->setTitulo($Titulo);
                        $instancia->setDescripcion($Descripcion);
                        $instancia->setFechaInicio($Fecha_inicio_Guardarbase);
                        $instancia->setFechaFin($Fecha_fin_Guardarbase);
                        $instancia->setUrlBase('../imagenes/' . $file);
                        $instancia->insertarEvento();

        
                    } catch (PDOException $e) {
                        echo "Error al insertar el evento: " . $e->getMessage();
                    }

                } else {
                    echo "Ha habido un error al cargar tu archivo.";
                }
            }
            else {
                echo "Error al cargar el archivo. Código de error: " . $_FILES["file"]['error'];
            }

            $archivo = $_FILES["archivo"]["tmp_name"]; 
    

            try {
                $instancia2->setarchivo($archivo);
                $instancia2->insertarListado();

            } catch (PDOException $e) {
                echo "Error al insertar el evento: " . $e->getMessage();
            }

            echo '<script>
            alert("Se ha cargado el evento con éxito.");
            window.location.href = "../Views/panel.php"; // Redirige a panel.php
            </script>';



        } else {
            
            echo '<script>alert("fila es nulo. Por favor, complete todos los campos antes de continuar.");</script>';
        }
     }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["TraerEventos"]) &&$_GET["TraerEventos"]=="true"){
            $instancia->ObtenerEventos();
        }
        if(isset($_GET["Listado"]) && $_GET["Listado"]=="true" && isset($_GET["pkEvento"])){
            $resultado = $instancia2->ObtenerActoresPorId($_GET["pkEvento"]);
            $bool = $instancia2->ConsultarListado($_GET["pkEvento"]);
            if($bool!=null){
                $jsonData = json_encode($resultado);
                generarExcelActores($jsonData);
            }
            else{
                echo '
                <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Página Actual</title>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    </head>
                    <body>
                        <h4>No hay un listado de actores asociado al evento seleccionado</h4>
                        <!-- Tu contenido de página actual aquí -->

                        <!-- Botón "Volver" que redirige al usuario a la página anterior -->
                        &nbsp; <a href="javascript:history.back()" class="btn btn-primary">Volver</a>
                    </body>
                </html>';
            }
            
            
        }
        if(isset($_GET["accion"]) && $_GET["accion"]=="modificar"&& isset($_GET["pkEvento"])){
            $instancia->ObtenerEventosPorId($_GET["pkEvento"]);
        }
        else if(isset($_GET["accion"]) && $_GET["accion"]=="eliminar"){
            $pkevento = $_GET["pkEvento"];
            //elimino el evento y los actores de ese evento.
        }
    }
    

    function generarExcelActores($jsonData) {
    // Decodifica el JSON en un array asociativo
    $datos = json_decode($jsonData, true);

    // Abre el archivo CSV para escritura
    $nombreArchivo = "datos.csv";
    $archivo = fopen($nombreArchivo, "w");

    if ($archivo) {
         
        fputcsv($archivo,[],' '); // Usar un espacio como separador, SEGUNDO ARGUMENTO SON LOS ENCABEZADOS.

        // Escribe los datos en el archivo CSV
        foreach ($datos as $dato) {
            fputcsv($archivo, $dato, ' '); // Usar un espacio como separador
        }

        fclose($archivo);

        // Configura la respuesta HTTP para descargar el archivo
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=datos.csv');
        readfile($nombreArchivo);

        // Elimina el archivo después de enviarlo al cliente (opcional)
        unlink($nombreArchivo);
        exit;
    } else {
        echo "No se pudo abrir el archivo temporal para escritura.";
    }
}

    
    
unset($db);
unset($instancia);
unset($instancia2);
   
 
     

 


   
    
 
 

  


 





?>