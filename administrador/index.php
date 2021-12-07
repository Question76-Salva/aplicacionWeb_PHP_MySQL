<?php

    // ===============================================================
    // === recibir los datos del formulario | de esta misma página ===
    // ===============================================================
    
    // por el formulario de abajo

    // ======================================================================================================================== 

    // ========================
    // === login de usuario ===
    // ========================

    session_start();
    
    // si se envia algo por post...
    if ($_POST) {
        
        // preguntar si se ha enviado algo | validar campo usuario | se puede cambiar por validación en la bbdd
        // y los campos coinciden con el usuario y contraseña | validar usuario y contraseña
        if (($_POST['usuario'] == 'salva1976') && ($_POST['contrasenia'] == 'question76')) {
            
            // crear variables que les van a decir al 'header.php' del administrador que ya nos logueamos 
            // (usuario | nombreUsuario) | para que nos deje entrar
            $_SESSION['usuario'] = 'ok';
            $_SESSION['nombreUsuario'] = 'salva1976';

            // redireccionar
            header('Location: inicio.php');
        } else {
            $mensaje = 'Error: el usuario y la contraseña son incorrectos';
        }
        
    }

?>


<!doctype html>
<html lang="en">
  <head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      
    <div class="container">
        <div class="row justify-content-center align-items-center">            

            <div class="col-md-4 mt-5">

                <div class="card mt-5 shadow">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">

                        <?php if (isset($mensaje)) { ?>
                            <!-- si hay algo en el mensaje -->
                            <!-- mensaje error del login | no se ha podido loguear -->
                            <div class="alert alert-danger" role="alert">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                        
                        <form method="POST">

                            <div class = "form-group">
                                <label>Usuario</label>
                                <input type="text" class="form-control" name="usuario" placeholder="Introduce usuario">                                
                            </div>

                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" class="form-control" name="contrasenia" placeholder="Introduce contraseña">
                            </div>                                                        

                            <button type="submit" class="btn btn-primary">Entrar al administrador</button>
                        </form>                                               
                        
                    </div>                    
                    
                </div> 
                
                <div class="mt-5">
                    <button type="submit" class="btn btn-warning shadow">
                        <a class="nav-link" href="../index.php">Volver</a>
                    </button>
                </div>
                
                
            </div>

            
            
        </div>
    </div>

  </body>
</html>