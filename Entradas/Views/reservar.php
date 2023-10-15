<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="Estilos/reservar.css">
</head>
<body>
    <header>    
        <div class="cabecera">
            <h2>Reservar entradas</h2>&nbsp;
            <h3> (No actualizar la pagina mientras realiza la reserva).</h3>
        </div>
     </header>
<!-- 
    <form action="../Controllers/compradoresController.php" method="post">
        <div class="contenedor"> 
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
             </div>
             <div class="mb-3">
                <label for="Telefono" class="form-label">Telefono</label>
                <input type="text" class="form-control" id="Telefono" name="Telefono" required>
             </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI actor</label>
                <input type="text" class="form-control" id="dni_actor" name="dni_actor" required>
            </div>
            <div class="mb-3">
                <label for="cantidad_entradas" class="form-label">Cantidad de Entradas</label>
                <input type="number" class="form-control" id="cantidad_entradas" name="cantidad_entradas" required>
            </div>
            <input type="hidden" name="pk_eventos" value="<?php echo $_GET['pk_eventos']; ?>">
            <button type="submit" class="btn btn-primary">Comprar</button>
        </div>
    </form>
    <br> -->

    
    <form action="../Controllers/compradoresController.php" method="post">
        <div class="contenedor"> 
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI actor</label>
                <input type="text" class="form-control" id="dni_actor" name="dni_actor" required>
            </div>
            <input type="hidden" name="pk_eventos" value="<?php echo $_GET['pk_eventos']; ?>">
            <button type="submit" class="btn btn-primary">Reservar</button>
        </div>
    </form>
    <br>
 








    <script>
        
         
        var url = new URL(window.location.href);

        let pk_eventos = url.searchParams.get('pk_eventos');


        function enviarHeartbeat(pk_eventos) {
            fetch("../Controllers/salaController.php?ESTADOSESSION=ESTADO&pkeventos="+pk_eventos)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la solicitud AJAX");
                    }
                    return response.text();
                    console.log("heartbeat enviado");
                })
                .catch(error => {
                    // Maneja errores en la solicitud AJAX
                    console.error("Error en la solicitud AJAX:", error);
                });
        }



        function enviarSolicitudPOSTParaCerrarSesion(pk_eventos) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Controllers/salaController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // La solicitud POST se ha completado con éxito
                    // Puedes mostrar un mensaje o realizar otras acciones aquí
                }
            };
            xhr.send("activo=no");
            alert("Se agoto su tiempo para realizar la compra, sera redirijido a la sala de espera.");
            window.location.href = 'Eventos.html';
        }


        //setInterval(function () {enviarSolicitudPOSTParaCerrarSesion(pk_eventos);}, TiempodeExpiracion);

      

        
        function verificarEstadoSesion(pk_eventos) {
            // Hacer una solicitud AJAX al archivo PHP que obtiene el estado de la sesión
            fetch('../Controllers/salaController.php?SESSION=ESTADO')
                .then(response => response.text())
                .then(data => {
                    // Verificar si el estado es "false"
                    if (data.trim() == 'false') {
                        // Llamar a la función para cerrar la sesión
                        enviarSolicitudPOSTParaCerrarSesion(pk_eventos);
                        enviarHeartbeat(pk_eventos);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener el estado de la sesión:', error);
                });
        }
        
        setInterval(function () {verificarEstadoSesion(pk_eventos);}, 60000);


        window.addEventListener("beforeunload", function (e) {
            console.log("Evento unload disparado"); // Agrega un mensaje de depuración en la consola
            enviarSolicitudPOSTParaCerrarSesion(pk_eventos); //
        });

 









        let tiempoExpiracion = 30000; // 2 minutos
        let timerId = null;

        function extenderSession(pk_eventos) {
            if (timerId) {
                // Si el temporizador está en funcionamiento, el usuario ya respondió "extender la sesión"
                clearTimeout(timerId); // Cancela el temporizador actual
                timerId = null;
                tiempoExpiracion += 30000; // Agrega 2 minutos más al tiempo de expiración
            }

            // Muestra la alerta
            const resultado = window.confirm("Esta alerta se cerrará automáticamente después de 2 minutos. ¿Desea extender la sesión?");
            if (!resultado) {
                // Si el usuario no hizo clic en "Cancelar," programa el cierre automático después de 2 minutos
                timerId = setTimeout(() => {
                    // Cierra la alerta
                    alert("Será redirigido a la página principal.");
                    // Envia una solicitud para cerrar la sesión
                    enviarSolicitudPOSTParaCerrarSesion(pk_eventos);
                }, tiempoExpiracion);
            }
        }

        // Ejecuta la función para extender la sesión cada 2 minutos (120,000 milisegundos)
        setInterval(() => extenderSession(pk_eventos), tiempoExpiracion);

        // Ejecuta la función para enviar la solicitud cada 2 minutos (120,000 milisegundos)
        setInterval(() => enviarSolicitudPOSTParaCerrarSesion(pk_eventos), tiempoExpiracion);



    </script>
</body>
</html>