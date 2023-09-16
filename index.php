<?php
include 'model.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $model = new Conexion();
        if ($model->insertUser($name, $email, $password)) {
            echo "Usuario registrado con éxito.";
        } else {
            echo "Error al registrar el usuario.";
        }
    } else {
        include 'vista.php';
    }
} catch (PDOException $e) {
    echo 'Error en el controlador: ' . $e->getMessage();
    exit;
}
?>

  