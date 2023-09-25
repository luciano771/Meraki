<?php
include 'Conexion.php';
 try {

 

 // Consulta SQL para obtener los datos de la tabla "eventos"
 $db = new conexion();

 $consulta = "SELECT * FROM eventos";
 $stmt = $db->query($consulta);

 // Obtener los resultados en un array asociativo
 $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

 // Devolver los resultados en formato JSON
 header('Content-Type: application/json');
 echo json_encode($resultados);
} catch (PDOException $e) {
 // En caso de error en la conexión o consulta
 echo 'Error: ' . $e->getMessage();
}
?>
