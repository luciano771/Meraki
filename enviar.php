<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $mensaje = $_POST["mensaje"];

    // Realizar alguna acción con los datos, como enviar un correo electrónico
    // Aquí puedes agregar tu código para enviar el correo o procesar los datos de otra manera

    // Ejemplo: Enviar un correo electrónico de confirmación
    $to = "pereyraluciano771@gmail.com"; // Cambia esto por la dirección de correo a la que quieres enviar el mensaje
    $subject = "Mensaje de contacto de $nombre";
    $message = "Nombre: $nombre\n";
    $message .= "Email: $email\n";
    $message .= "Mensaje:\n$mensaje\n";

    // Utiliza la función mail() para enviar el correo (configura la función mail() según tus necesidades)
    if (mail($to, $subject, $message)) {
        header("Location: okresponseMail.html");
    } else {
        header("Location: badrequestMail.html");
    }
}
?>
