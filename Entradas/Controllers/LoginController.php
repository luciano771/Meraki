<?php
 
if($_POST['correo'] == 'admin@gmail.com' && $_POST['contraseña'] == 'contraseñaprueba'){

    session_start();
    $_SESSION['autenticado'] = true;
    header('Location: ../Views/panel.html');

}
else{echo'usuario o contraseña incorrectos';}


?>