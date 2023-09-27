<?php
require_once 'Conexion.php';

class CompradoresModel {
    private $db;
    private $email;
    private $nombre;
    private $apellido;
    private $dni;
    private $cantidad_entradas;
    private $fk_eventos;
    private $compra;
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

    public function setCantidadEntradas($cantidad_entradas) {
        $this->cantidad_entradas = $cantidad_entradas;
    }

    public function setFkEventos($fk_eventos) {
        $this->fk_eventos = $fk_eventos;
    }

    public function insertarComprador() {
        try {
            $sql = "INSERT INTO compradores (email, nombre, apellido, dni, cantidad_entradas, fk_eventos) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$this->email, $this->nombre, $this->apellido, $this->dni, $this->cantidad_entradas, $this->fk_eventos]);
            echo "Comprador insertado con éxito.";
        } catch (PDOException $e) {
            echo "Error al insertar el comprador: " . $e->getMessage();
        }
    }

    public function VerificarCompra() {
        try {
            $consulta = "SELECT compra FROM actores";
            $stmt = $this->db->query($consulta);
            $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
            if (!empty($resultados)) {
                // Verificar si al menos un valor es 1
                if (in_array(1, $resultados)) {
                    $this->compra = 1;
                } else {
                    $this->compra = 0;
                }
            } else {
                // No se encontraron resultados, establecer compra a 0 por defecto
                $this->compra = 0;
            }
    
            // Devolver el valor de compra
            return $this->compra;
        } catch (PDOException $e) {
            // En caso de error, devolver un valor predeterminado (0 en este caso)
            $this->compra = 0;
            return $this->compra;
        }
    }
    


}
?>
