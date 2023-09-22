<?php
class conexion extends PDO
{
    private $hostBd = 'localhost';
    private $nombreBd = 'patron-mvc';
    private $usuarioBd = 'root';
    private $passwordBd = '4226';

    public function __construct()
    {
        try{
            parent::__construct('mysql:host='. $this->hostBd
            . ';dbname=' . $this->nombreBd .
            ';charset=utf8',$this->usuarioBd,$this->passwordBd,
            array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));

        }
        catch(PDOException $e)
        {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }

    public string function session_usuarios(){
        session_start();
        $session_id = session_id();
        return  $session_id;
    }
 

    public function insertUser($name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = $this->prepare("INSERT INTO usuarios (nombre, correo_electronico, contrasena) VALUES (?, ?, ?)");
            return $query->execute([$name, $email, $hashedPassword]);
        } catch (PDOException $e) {
            echo 'Error en el modelo: ' . $e->getMessage();
            exit;
        }
    }


    public function verifyPassword($email, $password) {
        try {
            $query = $this->prepare("SELECT contraseña FROM usuarios WHERE correo_electronico = ?");
            $query->execute([$email]);
            $row = $query->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $hashedPassword = $row['contraseña'];
                return password_verify($password, $hashedPassword);
            } else {
                return false; // El usuario no existe
            }
        } catch (PDOException $e) {
            echo 'Error en el modelo: ' . $e->getMessage();
            exit;
        }
    }

     

}

?>

 