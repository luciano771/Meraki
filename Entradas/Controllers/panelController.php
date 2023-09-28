<?php
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

    
unset($db);
unset($instancia);
unset($instancia2);
   
 
     

 


   
    
 
 

  


 





?>