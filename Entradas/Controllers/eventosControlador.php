<?php
 

class LoginController {
    public function showLoginForm() {
        include 'Views/Eventos.html';
     }

    public function processLogin() {
        // Obtener los datos del formulario de inicio de sesión
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validar el inicio de sesión usando el modelo
        if (UserModel::validateUser($username, $password)) {
            // Iniciar sesión y almacenar el nombre de usuario en una variable de sesión
            session_start();
            $_SESSION['username'] = $username;

            // Redirigir a la página de bienvenida
            header('Location: welcome.php');
        } else {
            // Si la validación falla, mostrar un mensaje de error
            echo 'Inicio de sesión fallido. Por favor, inténtalo de nuevo.';
        }
    }
}
?>
