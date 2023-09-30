<?php
require_once 'Conexion.php';

class CompradoresModel {
    private $db;
    private $email;
    private $nombre;
    private $apellido;
    private $dni;
    private $dni_actor;
    private $cantidad_entradas;
    private $fk_eventos;
    private $TokenEntrada;
    public function __construct($db) {
        $this->db = $db;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    public function setDni($dni) {
        $this->dni = $dni;
    }
    public function setDni_actor($dni_actor) {
        $this->dni_actor = $dni_actor;
    }
    public function setCantidadEntradas($cantidad_entradas) {
        $this->cantidad_entradas = $cantidad_entradas;
    }
    public function setTokenEntrada() {
        $this->TokenEntrada = $this->ConsultarToken();
    }
    public function setFk_eventos($fk_eventos) {
        $this->fk_eventos = $fk_eventos;
    }
    
    public function insertarComprador() {
        $this->setTokenEntrada();
        try {
            // CONFIGURAR E Iniciar una transacción con el nivel de aislamiento READ COMMITTED
            $this->db->exec("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
            $this->db->beginTransaction();
            // Bloquear la fila del actor con el DNI correspondiente
            $consulta = "SELECT compra FROM actores WHERE dni = :dni_actor AND fk_eventos = :fk_eventos";
            $stmt = $this->db->prepare($consulta);
            $stmt->bindParam(':dni_actor', $this->dni_actor, PDO::PARAM_STR);
            $stmt->bindParam(':fk_eventos', $this->fk_eventos, PDO::PARAM_INT); // Agregamos esto
            $stmt->execute();
            $compra = $stmt->fetchColumn();
            if ($compra === false || $compra === null) {
                // El DNI no existe en la tabla de actores, muestra un mensaje de error o toma medidas adecuadas
                echo '<script>
                alert("El dni no esta registrado.");
                //window.location.href = "../Views/reservar.php?pk_eventos=' . $this->fk_eventos . '";
                </script>';
            } elseif ($compra == 1) {
                // Ya se ha realizado una compra para este actor, manejar esto según tus requerimientos
                echo '<script>
                alert("Ya se compraron entradas para este actor.");
                //window.location.href = "../Views/reservar.php?pk_eventos=' . $this->fk_eventos . '";
                </script>';
            } else {

                // No se ha realizado una compra, proceder con la inserción y actualización
                $sql = "INSERT INTO comprador (email, nombre, apellido, dni, cantidad_entradas, CodigoEntrada, fk_eventos) VALUES (:email, :nombre, :apellido, :dni, :cantidad_entradas, :TokenEntrada, :fk_eventos)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
                $stmt->bindParam(':apellido', $this->apellido, PDO::PARAM_STR);
                $stmt->bindParam(':dni', $this->dni, PDO::PARAM_STR);
                $stmt->bindParam(':cantidad_entradas', $this->cantidad_entradas, PDO::PARAM_INT);
                $stmt->bindParam(':TokenEntrada', $this->TokenEntrada, PDO::PARAM_STR);
                $stmt->bindParam(':fk_eventos', $this->fk_eventos, PDO::PARAM_INT);
                $stmt->execute();
                echo "Comprador insertado con éxito.";

    
                // Actualizar el campo "compra" en la tabla "actores" a 1
                $sql = "UPDATE actores SET compra = 1 WHERE dni = :dni_actor and fk_eventos = :fk_eventos ";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':dni_actor', $this->dni_actor, PDO::PARAM_STR);
                $stmt->bindParam(':fk_eventos', $this->fk_eventos, PDO::PARAM_INT); // Agregamos esto
                $stmt->execute();
                echo "Campo compra actualizado con éxito.";
            }
    
            // Confirmar la transacción
            $this->db->commit();
            echo '<script>
            alert("Compra realizada con exito!.");
            </script>';
            $this->enviarMail();
        } catch (PDOException $e) {
            // Si hay un error, deshacer la transacción
            $this->db->rollBack();
    
            // Manejar el error de alguna manera adecuada, por ejemplo, lanzando una excepción
            throw new Exception('Error al verificar la compra: ' . $e->getMessage());
        }
    }
    
   
    
    public function enviarMail(){
       
        $to = $this->email; // Cambia esto por la dirección de correo a la que quieres enviar el mensaje
        $subject = "Mensaje de contacto de $this->nombre";
        $message = "Nombre: $this->nombre\n";
        $message .= "La compra de su entrada fue efectiva. El codigo de compra es el siguiente: $this->TokenEntrada\n";

    
        // Utiliza la función mail() para enviar el correo (configura la función mail() según tus necesidades)
        if (mail($to, $subject, $message)) {
            echo '<script>
            alert("Se envio un correo a su email con el codigo de compra. Porfavor revisar en spam en caso de no encontrarlo en la carpeta principal.");
            //window.location.href = "../Views/Eventos.html";
            </script>';
        } else {
            echo '<script>
            alert("Hubo un error al mandar el correo con su codigo de compra. Comunicarse con el organizador del evento para obtenerlo.");
            //window.location.href = "../Views/Eventos.html";
            </script>';
        }
    }


    public function ConsultarToken(){
        try{
        $consulta = "SELECT MAX(CodigoEntrada) AS maxCodigo FROM comprador WHERE fk_eventos = :fk_eventos";
        $stmt = $this->db->prepare($consulta);
        $stmt->bindParam(':fk_eventos', $this->fk_eventos, PDO::PARAM_INT); 
        $stmt->execute();
        $token = $stmt->fetchColumn();

        if($token !== false && $token !== null){ 
            $token = $token + 1;
        }
        else{
            $token= 1;
        }

        }
        
        catch(PDOException $e){
            echo 'no se pudo traer el valor de codigoenetrada'. $e;
        }
        
        return $token;
    }











}
?>
