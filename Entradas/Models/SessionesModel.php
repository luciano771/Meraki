<?php

class SessionesModel  
{   
    private $db; // Almacena la instancia de la conexión a la base de datos
    private $session;


    public function session_usuarios(){
        // Iniciar una sesión de PHP si aún no ha sido iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtener el ID de la sesión
        $this->session = session_id();
    }
}

?>