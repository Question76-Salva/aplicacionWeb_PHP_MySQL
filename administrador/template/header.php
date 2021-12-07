<?php

  // ==================================================
  // === bloquear acceso al administrador de libros ===
  // ==================================================

  // iniciar sesión para poder trabajar con sesiones
  session_start();

  // preguntar si hay un usuario logueado | pregunta si la session tiene información  
  if (!isset($_SESSION['usuario'])) {
    // si está vacio redirecconar al index.php (login del usuario)
    header('Location: ../index.php');
  } else {
    // preguntar si el usuario tiene información
    if ($_SESSION['usuario'] == 'ok') {
      $nombreUsuario = $_SESSION['nombreUsuario'];
    }
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

  <?php 
    // ====================
    // === redirecconar ===
    // ====================
    // nos da info del host en el que estamos | nos devuelve la url en que estamos
    $url = "http://" . $_SERVER['HTTP_HOST'] . "/PHP/sitioweb"  
  ?>

    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Administrador del sitio web<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/inicio.php">Inicio</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/productos.php">Libros</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>/administrador/seccion/cerrar.php">Cerrar sesión</a>
            <a class="nav-item nav-link" href="<?php echo $url; ?>">Ver sitio web</a>
        </div>
    </nav>
      
    <div class="container mt-4">
        <div class="row">