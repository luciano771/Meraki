<?php
require '../vendor/autoload.php'; // Carga la biblioteca Spout
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Cell;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
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
            generarArchivoXLSX($resultado);

             
        }
        if(isset($_GET["accion"]) && $_GET["accion"]=="modificar"&& isset($_GET["pkEvento"])){
            $instancia->ObtenerEventosPorId($_GET["pkEvento"]);
        }
        else if(isset($_GET["accion"]) && $_GET["accion"]=="eliminar"){
            $pkevento = $_GET["pkEvento"];
            //elimino el evento y los actores de ese evento.
        }
    }
    


 
    function generarArchivoXLSX($jsonData) {
        $data = json_decode($jsonData, true);
    
        // Nombre del archivo de salida
        $nombreArchivo = "datos.xlsx";
    
        // Crea un escritor (Writer) para el archivo Excel
        $writer = WriterEntityFactory::createXLSXWriter();
    
        // Abre el archivo Excel para escritura
        $writer->openToFile($nombreArchivo);
    
        // Escribe encabezados
        $headerRow = WriterEntityFactory::createRowFromArray(['Nombre', 'Apellido', 'DNI']);
        $writer->addRow($headerRow);
    
        // Llena la hoja de cálculo con los datos
        foreach ($data as $row) {
            $rowData = WriterEntityFactory::createRow();
            foreach ($row as $value) {
                $cell = WriterEntityFactory::createCell($value);
                $rowData->addCell($cell);
            }
            $writer->addRow($rowData);
        }
    
        // Cierra el archivo Excel
        $writer->close();
    
        // Descargar el archivo XLSX
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$nombreArchivo");
        readfile($nombreArchivo);
    
        // Elimina el archivo temporal después de enviarlo al cliente (opcional)
        unlink($nombreArchivo);
        exit;
    }
    

    
unset($db);
unset($instancia);
unset($instancia2);
   
 
     

 


   
    
 
 

  


 





?>