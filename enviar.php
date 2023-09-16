<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $mensaje = $_POST["mensaje"];

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 10;

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pereyraluciano771@gmail.com'; // Cambia esto a tu dirección de correo Gmail
        $mail->Password = '42269143'; // Cambia esto a tu contraseña de correo Gmail
        $mail->SMTPSecure = 'ssl'; // Puede ser 'ssl' o 'tls'
        $mail->Port = 587; // Puerto SMTP

        $mail->setFrom($email, $nombre);
        $mail->addAddress('pereyraluciano771@gmail.com'); // Cambia esto a la dirección de destino
        $mail->isHTML(false);

        $mail->Subject = "Mensaje de contacto de $nombre";
        $mail->Body = "Nombre: $nombre\nEmail: $email\nMensaje:\n$mensaje";

        $mail->send();
        echo "Gracias por contactarnos. Su mensaje ha sido enviado.";
    } catch (Exception $e) {
        echo  $e;
    }
} else {
    echo "Acceso no permitido.";
}
?>
