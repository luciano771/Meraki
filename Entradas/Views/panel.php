<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
    <!-- Incluye jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- A continuación, incluye Moment.js en español antes de Tempus Dominus Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.js"></script>

    <!-- A continuación, incluye Bootstrap 4 CSS y JavaScript -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

    <!-- A continuación, incluye Tempus Dominus Bootstrap 4 CSS y JavaScript -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>

    <link rel="stylesheet" href="Estilos/panel.css">
</head>
<body>
<?php
    session_start();

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['autenticado'])) {
        // Si no está autenticado, redirigir al inicio de sesión
        header('Location: login.html');
        exit;
    }
?>
    <header>
        <div class="cabecera">
            <h1>Subir Nuevo Evento</h1>
        </div>    
    </header>
     
    <div class="principal">
            <form   action="../Controllers/panelController.php" method="POST"  enctype="multipart/form-data">
                <div class="contenedor" id="contenedor"> 
                    <div class="mb-3">
                        <label for="Titulo" class="form-label">Titulo:</label>
                        <input type="text" class="form-control" id="Titulo" name="Titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="Descripcion" class="form-label">Descripcion:</label>
                        <input type="text" class="form-control" id="Descripcion" name="Descripcion" required>
                    </div>
                    <div class="mb-3">
                        <label for="Fecha_inicio" class="form-label">Fecha inicio:</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="Fecha_inicio" id="Fecha_inicio" required/>
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Fecha_fin" class="form-label">Fecha fin:</label>
                            <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="Fecha_fin" id="Fecha_fin" required/>
                                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="file">Imagen:</label>
                        <input type="hidden" id="img"></label>
                        <input type="file" class="form-control-file" id="file" name="file">
                    </div>
                    <br>
                    <label class="col-xs-2 control-label">Adjuntar Listado de actores:</label>
                    <div class="col-xs-3">
                    <input type="file" name="archivo" value="archivo" size="80" id="archivo" />
                    <br>
                    <a href="../Models/Modelo.xlsx">Descarge archivo de modelo</a>
                    </div>
                    <br>
                    <input type="hidden" name="accion" id="accion" value="agregar">
                    <input type="hidden" name="pk_eventos" id="pk_eventos" value="">
                    <button type="submit" id ="boton" class="btn btn-primary">Cargar evento</button>
                </div>
            </form>
            <div id="administrador"></div>       
    </div>


 
        
        <script>


            $(document).ready(function () {
                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss',
                    locale: 'es'
                });
                $('#datetimepicker2').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm:ss',
                    locale: 'es'
                });
            });

            let Modificar = false;

            fetch('../Controllers/panelController.php?TraerEventos=true')
            .then(response => response.json())
            .then(data => {
                // Iterar sobre los datos y generar divs para cada evento
                const eventosContainer = document.getElementById('administrador');
                data.forEach(evento => {
                    const divEvento = document.createElement('div');
                    divEvento.className = 'eventoPanel';
                    divEvento.innerHTML = `
                        <p>${evento.pk_eventos}: ${evento.titulo} 
                        <button class="btn btn-primary boton" accion="modificar" pkeventos="${evento.pk_eventos}">Modificar</button>
                        <button class="btn btn-primary boton" accion="eliminar" pkeventos="${evento.pk_eventos}">Eliminar</button></p>
                        `;
                    eventosContainer.appendChild(divEvento);
                    const Btns = divEvento.querySelectorAll('.boton');
                        Btns.forEach(Btn => {
                                Btn.addEventListener("click", function() {
                                    const pkEventos = this.getAttribute('pkeventos');
                                    const accion = this.getAttribute('accion');
                                    console.log('pkeventos es:', pkEventos," y la accion es: ",accion);
                                    //cargar popup del admin
                                    if(accion=='modificar'){
                                            MandarGet(pkEventos, accion)
                                            .then(data => {
                                                if(data.length>0){
                                                    const evento = data[0];
                                                    console.log("Respuesta JSON:", evento);
                                                    document.getElementById('Titulo').value = evento.titulo;
                                                    document.getElementById('Descripcion').value = evento.descripcion;
                                                    document.getElementById('Fecha_inicio').value = evento.fecha_inicio;
                                                    document.getElementById('Fecha_fin').value = evento.fecha_fin;
                                                    var titulo = evento.titulo;
                                                    var descripcion = evento.descripcion;
                                                    var fecha_inicio = evento.fecha_inicio;
                                                    var fecha_fin = evento.fecha_fin;
                                                    var img = evento.img;
                                                    if(!Modificar){
                                                        agregarbtnVer(pkEventos);
                                                    }
                                                    Cargar_A_Actualizar(pkEventos);
                                                    Modificar = true;
                                                }
                                            })

                                        .catch(error => {
                                            console.error("Error en la solicitud AJAX:", error);
                                        });
                                       
                                                                                                   
                                    }else{
                                        // const divContenedor = document.getElementById("contenedor");
                                        // const btnListado = document.getElementById("VerListado");
                                        // divContenedor.removeChild(btnListado);

                                        var boton = document.getElementById("boton").innerText = "Cargar Evento";
                                        var accionpost = document.getElementById('accion').value = "agregar";

                                        console.log(boton,accionpost);

                                        
                                        // document.getElementById('Titulo').value = "";
                                        // document.getElementById('Descripcion').value = "";
                                        // document.getElementById('Fecha_inicio').value = "";
                                        // document.getElementById('Fecha_fin').value = "";
                                        Modificar = false;

                                        EliminarEvento(pkEventos);
                                        alert("Se elimino el evento: "+pkEventos);

                                        // Recargar la página actual
                                        location.reload();

                                    }
                                      
                         });
                    });
                });
            })
            .catch(error => {
                console.error('Error al obtener los datos de eventos:', error);
            });




            function MandarGet(pkEventos, accion) {
                const url = `../Controllers/panelController.php?accion=${accion}&pkEvento=${pkEventos}`;
                return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la solicitud AJAX");
                    }
                    return response.json();
                });
            }

            function MandarGetImg(pkEventos) {
                const url = `../Controllers/panelController.php?VerImagen=true&pkEvento=${pkEventos}`;
                return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Error en la solicitud AJAX");
                    }
                    return response.json();
                });
            }
 
         
 
            function EliminarEvento(pkEvento) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "../Controllers/panelController.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // La solicitud POST se ha completado con éxito
                            // Puedes mostrar un mensaje o realizar otras acciones aquí
                            console.log(xhr.responseText);
                        } else {
                            // Manejar errores aquí
                            console.error("Error en la solicitud AJAX");
                        }
                    }
                };
                xhr.send("EliminarEvento=true&pkEvento=" + pkEvento);
            }

  

            function agregarbtnVer(pkeventos) {
                
                var miBoton = document.createElement("button");
                miBoton.textContent = "Ver listado actual";
                miBoton.type = "button";
                miBoton.id="VerListado";
                miBoton.className = "btn btn-primary boton";
                miBoton.addEventListener("click", function () {
                    console.log("Botón ver listado");
                    var urlCompleta = window.location.href;
                    // Separar la URL en partes (dominio y resto)
                    var partesURL = urlCompleta.split('/');
                    // Obtener la parte del dominio
                    var dominio = partesURL.slice(0, 5).join('/'); // Esto captura "http://localhost/meraki/entradas"
                    // Concatenar el resto de la URL que desees
                    var restoDeLaURL = '/Controllers/panelController.php?Listado=true&pkEvento='+pkeventos;
                    // Construir la nueva URL
                    var nuevaURL = dominio + restoDeLaURL;
                    // Imprimir la nueva URL en la consola (puedes usarla como desees)
                    console.log(dominio);
                    console.log(restoDeLaURL);
                    console.log(nuevaURL);
                    window.location.href = nuevaURL;
                });

                // Agregar el botón al contenedor
                var contenedor = document.getElementById("contenedor");
                contenedor.appendChild(miBoton);
            }

            

            function Cargar_A_Actualizar(pkevento) {
                var accion = document.getElementById('accion');
                accion.value = "actualizar";
                var boton = document.getElementById('boton'); 
                boton.innerText = "Actualizar";   
                var pk_eventos = document.getElementById('pk_eventos'); 
                pk_eventos.value = pkevento;    

                console.log(accion,boton,pk_eventos);
            }







        </script>
















 
</body>
</html>