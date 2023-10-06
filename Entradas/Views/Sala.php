<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala de espera</title>
    <link rel="stylesheet" href="Estilos/sala.css">
</head>
<body>
    <header>
        <h1>Sala de espera</h1>
    </header>
    <section>
    <div id="contenedor">
            <br><br>
            <div class="contenedor-loader">
                <div class="rueda"></div>
            </div>
            <div class="cargando"></div>  
        </div>
        <div class="principal">
            <p>La fila ya inicio, aguarde a ser redirigido al formulario de reserva.</p>  
            <p>Recuerde no cerrar ni actualizar la pagina ya que perdera su turno. </p>  
        </div>
        </div>
            <div id="sessiones-listado">
        </div>
    </section>
    <script>


        var url = new URL(window.location.href);

 
        let pk_eventos = url.searchParams.get('pk_eventos');

        const sessionesContainer = document.getElementById("sessiones-listado")
         
        fetch('../Controllers/sessionesController.php')
        .then(response => response.json())
        .then(data => {
            // Verifica que data sea un array

            // Verifica que la propiedad "sessiones" exista en el objeto
            if (Array.isArray(data)) {
            const divSessiones = document.createElement('div');
            divSessiones.className = 'divSessiones';
            divSessiones.innerHTML = `
                <h4>Cantidad de usuarios en espera: ${data.length}</h4>
            `;
            sessionesContainer.appendChild(divSessiones);
            }

         })
        .catch(error => {
            console.error('Error al obtener los usuarios en espera:', error);
        });

 
 

        window.addEventListener("beforeunload", function (e) {
            console.log("Evento beforeunload disparado"); // Agrega un mensaje de depuración en la consola
            enviarSolicitudPOSTParaCerrarSesion();
        });


        function enviarSolicitudPOSTParaCerrarSesion() {
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
        }



        //    Obtén el estado de sesión del servidor y configúralo en estadoSession

        function enviarHeartbeat(pk_eventos) {
            fetch("../Controllers/salaController.php?ESTADOSESSION=ESTADO&pk_eventos="+pk_eventos)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la solicitud AJAX");
                    }
                    return response.text();

                })
                .catch(error => {
                    // Maneja errores en la solicitud AJAX
                    console.error("Error en la solicitud AJAX:", error);
                });
        }

 

        function VerificarOrden(pk_eventos) {
            fetch("../Controllers/salaController.php?VerificarOrden=true&pk_eventos="+pk_eventos)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la solicitud AJAX");
                    }
                    return response.text();

                })
                .catch(error => {
                    // Maneja errores en la solicitud AJAX
                    console.error("Error en la solicitud AJAX:", error);
                });
        }
        
        //setInterval(VerificarOrden(pk_eventos),60000);

    


    </script>



</body>
</html>