<?php
    
    // ==========================
    // === conexión a la bbdd ===
    // ==========================
    // variables con datos para la conexion
    $host = "localhost";                            // dirección de mi servidor
    $bd = "sitioweb";                               // nombre de la bbdd de phpmyadmin
    $usuario = "root";                              // nombre del usuario (en phpmyadmin es root por defecto)
    $contrasenia = "";                              // en phpmyadmin por defecto no hay contraseña para el usuario root

    try {
        
        // PDO -> crea una conexión
        $conexion = new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);

        // comprobar la conexión | muestra mensaje de que nos hemos conectado
        // una vez comprobado comentar, ya que no es bueno que el usuario vea el mensaje
        
        // if ($conexion) {
        //     echo "Conectado... al sistema";
        // }

    } catch ( Exception $ex ) {
        
        // Exception $ex -> captura el error en caso de fallo | y muestra el mensaje de error que lo provocó
        echo $ex->getMessage();
    }

    // ==============================================================================================================
?>