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
            <h2>Comprar entradas</h2>
        </div>
     </header>

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
    <br>

    <script>
        
        
        
        
        var url = new URL(window.location.href);

        let pk_eventos = url.searchParams.get('pk_eventos');

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
            alert("Se agoto su tiempo para realizar la compra, sera redirijido a la sala de espera.")
            window.location.href = '../Controllers/salaController.php?pk_eventos='+pk_eventos'&ingreso=true'
        }


        setInterval(function () {enviarSolicitudPOSTParaCerrarSesion(pk_eventos);}, 300000);

        
        function verificarEstadoSesion(pk_eventos) {
            // Hacer una solicitud AJAX al archivo PHP que obtiene el estado de la sesión
            fetch('archivo.php?SESSION=ESTADO')
                .then(response => response.text())
                .then(data => {
                    // Verificar si el estado es "false"
                    if (data.trim() === 'false') {
                        // Llamar a la función para cerrar la sesión
                        enviarSolicitudPOSTParaCerrarSesion(pk_eventos);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener el estado de la sesión:', error);
                });
        }
        
        setInterval(function () {verificarEstadoSesion(pk_eventos);}, 1000);



    </script>
</body>
</html>