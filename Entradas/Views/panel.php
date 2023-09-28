<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
     
    
    <form action="../Controllers/panelController.php" method="POST"  enctype="multipart/form-data">
        <div class="contenedor"> 
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
                <input type="file" class="form-control-file" id="file" name="file">
            </div>
            <br>
            <label class="col-xs-2 control-label">Adjuntar Listado de actores:</label>
            <div class="col-xs-3">
            <input type="file" name="archivo" value="archivo" size="80" />
            <br>
            <a href="../Models/Modelo.xlsx">Descarge archivo de modelo</a>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Cargar evento</button>
        </div>
        <br>
    </form> 
    <br><br>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>