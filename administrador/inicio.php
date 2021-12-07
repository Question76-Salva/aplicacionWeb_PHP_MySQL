<?php
    // === header ===
    include 'template/header.php';
?>

    <div class="col-md-12">
        <div class="jumbotron shadow">
            <h1 class="display-3">Bienvenido <?php echo $nombreUsuario; ?></h1>
            <p class="lead">Vamos a administrar nuestros libros en el sitio web</p>
            <hr class="my-2">
            <p>Más información</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="seccion/productos.php" role="button">Administrar libros</a>
            </p>
        </div>
    </div>
            
<?php
    // === footer ===
    include 'template/footer.php';
?>