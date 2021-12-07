<?php
    // ===============================
    // === cerrar sesión y ajustes ===
    // ===============================

    // iniciar sesión para poder trabajar con sesiones
    session_start();

    // destruye todas las sesiones abiertas y las variables en memoria
    session_destroy();

    // redireccionar
    header('Location: ../index.php');

?>