<?php 
    // === header ===
    include ('template/header.php');
?>

    <!-- ====================================================== -->
    <!-- === mostrando los productos/libros en el sitio web === -->
    <!-- ====================================================== -->

    <?php 
        // === conexión bbdd ===
        include 'administrador/config/bd.php';

        // ==================================
        // === mostrando datos del libros ===
        // ==================================
        
        // prepara la consulta sql | para seleccionar (y mostrar) todos los libros de la tabla 'libros' de la bbdd
        $sentenciaSQL = $conexion->prepare("SELECT * FROM libros");

        // ejecuta la consulta sql
        $sentenciaSQL->execute();

        // guardar lo que devuelve la consulta en una variable para mostrar en la interfaz
        // fetchAll -> recupera todos los registros para poder mediante la variable $listaLibros, y todo esto lo genera con
        // FETCH_ASSOC -> genera una asociación entre los datos que vienen de la tabla y los nuevos registros
        // $listaLibros -> guarda todos los registros | así desde el interfaz podemos ir imprimiendo datos dinámicamente en html
        // mirar en la <table></table> para ver lo que hace este método
        $listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    ?>

    
    <!-- === por cada libro una card | bucle foreach que recorre la consulta sql === -->
    <?php foreach($listaLibros as $libro) { ?>
        <div class="col-md-3 mt-4">
            <div class="card">

            <img class="card-img-top" src="./img/<?php echo $libro['imagen']; ?>" alt="imagen del libro">

            <div class="card-body shadow">
                <h4 class="card-title"><?php echo $libro['nombre']; ?></h4>     
                <a name="" id="" class="btn btn-primary" href="#" role="button">Ver más</a>       
            </div>

            </div>
        </div>
    <?php } ?>
   
    
<?php 
    // === footer ===
    include ('template/footer.php');
?>