<?php
session_start(); 
require_once 'Conexion.php';
class SessionesModel  
{   
    private $db; 
    private $session;
    private $PrimerasSessiones;
    private $ListadoSessiones;
    private $pkevento;

    public function __construct($db) {
        $this->db = $db;
    }
    public function setSession(){
         
        $this->session = $this->session_usuarios();
        $_SESSION['estado']='true';
    }
  
    public function setListadoSessiones(){
        $this->ListadoSessiones = $this->SessionListado();
    }
    public function setPkevento($pkevento){
        $this->pkevento = $pkevento;
    }
    public function getSession() {
        return $this->session;
    }
    public function getListadoSessiones() {
        return $this->ListadoSessiones;
    }
    public function session_usuarios(){
        // Iniciar una sesión de PHP si aún no ha sido iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return session_id();
    }

    public function boolsession(){
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true; // La sesión está activa
        } else {
            return false; // La sesión no está activa
        }
    }

    public function InsertarSession(){
        $this->setSession();
        if(!$this->SessionBool()){
            try{
                $this->session = session_id();
                $this->db->exec("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
                $this->db->beginTransaction();
                $sql = "INSERT INTO sessiones (sessiones, fk_eventos) VALUES (:sessiones, :fk_eventos)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':sessiones', $this->session, PDO::PARAM_STR);
                $stmt->bindParam(':fk_eventos', $this->pkevento, PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();
                echo "session insertada con éxito. <br>";
                $_SESSION["estado"] = 'true';       
            }
            catch(PDOException $e){
                $this->db->rollBack();
                throw new Exception('Error al insertar la session: ' . $e->getMessage());
            } 
        }  
         
    }

    public function BorrarSession(){
        $this->setSession();
            try{
                $this->session = session_id();
                $this->db->exec("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
                $this->db->beginTransaction();
                $sql = "DELETE FROM sessiones WHERE sessiones = :sessiones";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':sessiones', $this->session, PDO::PARAM_STR);
                $stmt->execute();
                $this->db->commit();
                echo "session borrada con éxito. <br>";  
                $this->session = null;
                $_SESSION["estado"] = 'false';     
            }
            catch(PDOException $e){
                $this->db->rollBack();
                throw new Exception('Error al borrar la session: ' . $e->getMessage());
            }       
    }

    

    public function ConsultaSessionesMin() {
        $this->setSession();
    
        try {
            $sql = "SELECT sessiones 
                    FROM sessiones 
                    WHERE id_sessiones = (SELECT MIN(id_sessiones) FROM sessiones WHERE fk_eventos = :fk_eventos)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fk_eventos', $this->pkevento, PDO::PARAM_INT);
            $stmt->execute();
    
            $id_sessiones = $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Manejar el error apropiadamente (por ejemplo, registrar o lanzar una excepción)
            error_log('Error en ConsultaSessionesMin: ' . $e->getMessage());
            $id_sessiones = null;
        }
    
        return $id_sessiones;
    }
    

    public function SessionListado(){
        $this->setSession();
        try{
            $sql = "SELECT sessiones FROM sessiones";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $id_sessiones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            $id_sessiones = null;
            echo $e; 
        }
        return $id_sessiones; 
    }

    public function SessionBool(){
        
        $listadoSessiones = $this->SessionListado();
        $guardada = false;
        foreach ($listadoSessiones as $fila) {
            if($fila['sessiones'] == $this->session){
                $guardada = true;
            }
        }
        return $guardada;

    }

    public function SessionFilas() {
        $this->setSession();
        if ($this->session == $this->ConsultaSessionesMin()) {
            return true; // Tu sesión tiene el valor más pequeño
        } else {
            return false; // Tu sesión no tiene el valor más pequeño
        }
    }
    


    



}

?>