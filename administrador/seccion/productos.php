<?php
    // =============================
    // === gestión libros | crud ===
    // =============================

    // === header ===
    include '../template/header.php';
?>

<?php
    // ===============================================
    // === recepción de datos | desde el formuario ===
    // ===============================================

    // si se envia algo por POST en 'txtID' ('name' de los inputs del form) guardalo en $txtID | de lo contrario viene vacio ""
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : ""; 

    // si se envia algo por POST en 'txtNombre' ('name' de los inputs del form) guardalo en $txtNombre |
    // de lo contrario viene vacio ""
    $txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
    
    // si se envia algo por FLES capturar el nombre 'name' del archivo
    $txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";

    // preguntar que acción llegó | si hay una 'accion' la guardamos en la variable | sino viene vacia ""
    $accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

    // comprobar que lleguen los valores | comprobar que la información viaja y llega
    // es muy importante hacer esta comprobación antes de insertar los datos en la bbdd
    // echo "<pre>";
    // var_dump($txtID);
    // var_dump($txtNombre);
    // var_dump($txtImagen);
    // var_dump($accion); 
    // echo "</pre>";

    // ==============================================================================================================

    // ==========================
    // === conexión a la bbdd ===   (la conexión está en 'administrador/config/bd.php') (se ha llevado allí)
    // ==========================   desde aquí se llama con include, para hacerlo más funcional

    include '../config/bd.php';

    // ==============================================================================================================

    // =======================================
    // === captura de aciones para el CRUD ===
    // =======================================
    // identificar y evaluar que acción (botón) presionó el usuario | comparar los 'value' (Agregar | Modificar | Cancelar)

    switch ($accion) {

        case "Agregar":
           
            // prepara la consulta sql | para insertar dataos en la bbdd desde el formulario
            $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre, :imagen);");

            // inserción mediante parámetros | (:nombre, :imagen) -> estos son los 2 parámetros que insertamos en la bbdd
            // con los datos que el usuario manda desde el formulario
            $sentenciaSQL->bindParam(':nombre', $txtNombre);

            // *=== adjuntar imagen del libro | guardar en la carpeta 'img' del proyecto | no en la bbdd ===
            // generar un nombre para los archivos de imagen y que no se sobrescriban | 
            // si no hay, le ponemos 'imagen.jpg'            
            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : "imagen.jpg";

            // usar una imagen temporal, para poder copiarla a nuestra carpeta (servidor)
            $tmpImagen = $_FILES['txtImagen']['tmp_name'];

            // validar si 'tmp_name' tiene algo 
            if($tmpImagen != "") {

                // se inserta el archivo de imagen en la carpeta 'img'
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
            }

            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);

            // ejecuta la consulta sql
            $sentenciaSQL->execute();

            // redirigir a productos/libros | para que no vuelva a enviar los datos al actualizar la página
            header('Location: productos.php');

            //echo "Presionado botón Agregar";
            break;

            // =========================================================

        case "Modificar":

            // prepara la consulta sql | para actualizar/modificar un registro de la tabla
            $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre = :nombre WHERE id = :id");

            // seleccionar mediante parámetros | (:nombre, :id ) -> le pasamos el parámetro 'id' de la consulta
            // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
            // y lo preparamos
            $sentenciaSQL->bindParam(':nombre', $txtNombre);
            $sentenciaSQL->bindParam(':id', $txtID);

            // ejecuta la consulta sql
            $sentenciaSQL->execute(); 

            // *=== modificar la imagen | si el input NO está vacio | modificar si tiene una imagen ===     

            if ($txtImagen != "") {               

                // generar un nombre para los archivos de imagen y que no se sobrescriban | 
                // si no hay, le ponemos 'imagen.jpg'            
                $fecha = new DateTime();
                $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES['txtImagen']['name'] : "imagen.jpg";

                // usar una imagen temporal, para poder copiarla a nuestra carpeta (servidor)
                $tmpImagen = $_FILES['txtImagen']['tmp_name'];

                // se inserta el archivo de imagen en la carpeta 'img'
                move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);

                //* === borrar la imagen antigua ===

                // prepara la consulta sql | para seleccionar un registro de la tabla
                $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id = :id");

                // seleccionar mediante parámetros | (:id) -> le pasamos el parámetro 'id' de la consulta
                // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
                // y lo preparamos
                $sentenciaSQL->bindParam(':id', $txtID);

                // ejecuta la consulta sql
                $sentenciaSQL->execute();

                // fetch -> cargar los datos de uno en uno  
                $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

                // si la imagen existe y es diferente al nombre de 'imagen.jpg' (nombre genérico si no se adjunta imagen)
                // buscala...
                if (isset($libro['imagen']) && $libro['imagen'] != 'imagen.jpg') {

                    // si existe el archivo | buscalo en la ruta...
                    if (file_exists('../../img/' . $libro['imagen'])) {

                        // borrarlo
                        unlink('../../img/' . $libro['imagen']);
                    }
                }

                //* === actuazliar la imagen con una imagen nueva ===

                // prepara la consulta sql | para actualizar/modificar un registro de la tabla
                $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen = :imagen WHERE id = :id");

                // seleccionar mediante parámetros | (:imagen, :id ) -> le pasamos el parámetro 'id' de la consulta
                // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
                // y lo preparamos
                $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
                $sentenciaSQL->bindParam(':id', $txtID);

                // ejecuta la consulta sql
                $sentenciaSQL->execute();    
            }

            // redirigir a productos/libros | para que no vuelva a enviar los datos al actualizar la página
            header('Location: productos.php');
                                          
            //echo "Presionado botón Modificar";
            break;

            // =========================================================

        case "Cancelar":

            // redirigir a productos/libros | para que no vuelva a enviar los datos al actualizar la página
            header('Location: productos.php');

            //echo "Presionado botón Cancelar";
            break;

            // =========================================================

        case "Seleccionar":

            // prepara la consulta sql | para seleccionar un registro de la tabla
            $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id = :id");

            // seleccionar mediante parámetros | (:id) -> le pasamos el parámetro 'id' de la consulta
            // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
            // y lo preparamos
            $sentenciaSQL->bindParam(':id', $txtID);

            // ejecuta la consulta sql
            $sentenciaSQL->execute();

            // fetch -> cargar los datos de uno en uno  
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            // asigna los valores que recupero de la selección | para poder mostrarlos en los inputs del formulario
            // mirar los 'value' del formualio, antes de los 'name' | importante!!!
            // lo que seleccionamos se va a imprimir en los inputs del formulario
            $txtNombre = $libro['nombre'];
            $txtImagen = $libro['imagen'];

            //echo "Presionado botón Seleccionar";
            break;

            // =========================================================

        case "Borrar":

            //* === borrar la imagen ===

            // prepara la consulta sql | para seleccionar un registro de la tabla
            $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id = :id");

            // seleccionar mediante parámetros | (:id) -> le pasamos el parámetro 'id' de la consulta
            // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
            // y lo preparamos
            $sentenciaSQL->bindParam(':id', $txtID);

            // ejecuta la consulta sql
            $sentenciaSQL->execute();

            // fetch -> cargar los datos de uno en uno  
            $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            // si la imagen existe y es diferente al nombre de 'imagen.jpg' (nombre genérico si no se adjunta imagen)
            // buscala...
            if (isset($libro['imagen']) && $libro['imagen'] != 'imagen.jpg') {

                // si existe el archivo | buscalo en la ruta...
                if (file_exists('../../img/' . $libro['imagen'])) {

                    // borrarlo
                    unlink('../../img/' . $libro['imagen']);
                }
            }

            //* === borrar el registro ===

            // prepara la consulta sql | para eliminar el registro seleccionado
            $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id = :id");

            // eliminar mediante parámetros | (:id) -> le pasamos el parámetro 'id' de la consulta
            // $txtID -> lo recibimos arriba en la 'recepción de datos', mediante POST desde el formulario,
            // y lo preparamos
            $sentenciaSQL->bindParam(':id', $txtID);

            // ejecuta la consulta sql
            $sentenciaSQL->execute();

            // redirigir a productos/libros | para que no vuelva a enviar los datos al actualizar la página
            header('Location: productos.php');

            //echo "Presionado botón Borrar";
            break;
    }

    // ==============================================================================================================

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

    <!-- ======================================= -->
    <!-- === interfaz para el crud de libros === -->
    <!-- ======================================= -->
    <div class="col-md-5">

        <!-- card -->
        <div class="card shadow">
            <div class="card-header">
                Datos del Libro
            </div>

            <div class="card-body">

                <!-- formulario | para agregar libros -->
                <form method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="txtID">ID:</label>
                        <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">            
                    </div>

                    <div class="form-group">
                        <label for="txtNombre">Nombre:</label>
                        <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre del libro">            
                    </div>

                    <div class="form-group">
                        <label for="txtImagen" class="mb-3">Imagen:</label>

                        <div class="row">
                            <!-- salto de línea -->
                        </div>                        

                        <!-- este dato es el que aparece cuando seleccionamos la imagen -->
                        <?php  // echo $txtImagen; ?>

                        <?php
                            // si existe algo (la imagen) | mostrar la imagen en el formulario 
                            if ($txtImagen != "") {
                        ?>
                            <img src="../../img/<?php echo $txtImagen; ?>" width="50" alt="imagen" class="mb-3">

                        <?php } ?>


                        <input type="file" required class="form-control" name="txtImagen" id="txtImagen">            
                    </div>

                    <div class="btn-group mb-2" role="group" aria-label="">
                        <!-- condiciones para activar/desactivar botones según los datos -->
                        <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : "" ?> value="Agregar" class="btn btn-success mr-3">Agregar</button>
                        <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Modificar" class="btn btn-warning mr-3">Modificar</button>
                        <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : "" ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
            
        </div>       
                
    </div>



    <div class="col-md-7">
        <!-- tabla de libros - mostrar los datos de los libros -->
        <table class="table table-bordered shadow">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- necesitamos que en los <td></td> se vayan incluyendo los registros de la tabla 'libros' -->
                <!--  los datos (id, nombre, imagen) deben coincidir con los nombres de las columnas de la tabla 'libros' -->
                <?php foreach($listaLibros as $libro) { ?> 

                <tr>

                    <td><?php echo $libro['id']; ?></td>
                    <td><?php echo $libro['nombre']; ?></td>
                    <td>
                        <!-- mostrar imagen en la tabla del crud -->
                        <img src="../../img/<?php echo $libro['imagen']; ?>" width="50" alt="imagen">                     
                    </td>

                    <td>                    
                        <form method="POST">

                            <!-- campo oculto "hidden" | capturar el id y tenerlo como referencia para los registros -->
                            <input type="hidden" name="txtID" value="<?php echo $libro['id']; ?>" />
                            
                            <!-- seleccionar registro | enviarlo al formulario para editar -->
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />

                            <!-- eliminar registro -->
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
                        </form>
                    </td>

                </tr>   

                <?php } ?>      

            </tbody>

        </table>
    </div>


<?php
    // === footer ===
    include '../template/footer.php';
?>

