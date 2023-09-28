<?php
 
 include '../Models/EventosModel.php';
 $db = new conexion();
 $instancia = new EventosModel($db);
 $instancia->ObtenerEventos();
 
     
unset($db);
unset($instancia);

 

?>
