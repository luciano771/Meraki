<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="Estilos/panel.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
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
                        <input type="text" class="form-control" id="Fecha_inicio" name="Fecha_inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="Fecha_fin" class="form-label">Fecha fin:</label>
                        <input type="text" class="form-control" id="Fecha_fin" name="Fecha_fin" required>
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
                    <button type="submit" id ="boton" class="btn btn-primary">Cargar evento</button>
                </div>
            </form>
            <div id="administrador"></div>       
    </div>





        <script>

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
                                                    agregarbtnActualizar(titulo,descripcion,fecha_inicio,fecha_fin,img);
                                                }
                                            })
                                        .catch(error => {
                                            console.error("Error en la solicitud AJAX:", error);
                                        });
                                        //descargar csv listado
                                        agregarbtnVer(pkEventos);
                                                                                                   
                                    }else{
                                        const divContenedor = document.getElementById("contenedor");
                                        const btnListado = document.getElementById("VerListado");
                                        divContenedor.removeChild(btnListado);

                                        const btnActualizar = document.getElementById("Actualizar");
                                        divContenedor.removeChild(btnActualizar);

                                        document.getElementById('Titulo').value = "";
                                        document.getElementById('Descripcion').value = "";
                                        document.getElementById('Fecha_inicio').value = "";
                                        document.getElementById('Fecha_fin').value = "";
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

 


            function ActualizarEvento(pkEventos, accion) {
                const url = `../Controllers/panelController.php?accion=${accion}&pkEvento=${pkEventos}`;
                return fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error en la solicitud AJAX");
                        }
                        return response.json();
                    });
                }
            

            function agregarbtnVer(pkeventos) {
                
                var miBoton = document.createElement("button");
                miBoton.textContent = "Ver listado actual";
                miBoton.type = "button";
                miBoton.id="VerListado";
                miBoton.className = "btn btn-primary boton";
                miBoton.addEventListener("click", function () {
                    console.log("Botón ver listado");
                    ActualizarEvento();
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

            

            function agregarbtnActualizar(titulo,descripcion,fecha_inicio,fecha_fin,img) {
                

                var Titulo = document.getElementById('Titulo');
                var Descripcion = document.getElementById('Descripcion');
                var Fecha_inicio = document.getElementById('Fecha_inicio');
                var Fecha_fin = document.getElementById('Fecha_fin');

                

                var miBoton = document.createElement("button");
                miBoton.textContent = "Actualizar";
                miBoton.type = "button";
                miBoton.id="Actualizar";
                miBoton.className = "btn btn-primary boton";
                miBoton.addEventListener("click", function () {
                    console.log("Botón ver Actualizar");
                });
                // Agregar el botón al contenedor
                var contenedor = document.getElementById("contenedor");
                contenedor.appendChild(miBoton);
            }







        </script>
















<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>