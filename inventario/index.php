<?php require './includes/session_start.php'?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include 'includes/head.php'?>
</head>

<body>
    <?php 
        if (!isset($_GET['vista']) || ($_GET['vista'] == '')) {
            $_GET['vista'] = 'login';
        }

        if (is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {

            # Verificación de sesion #
            if ((!isset($_SESSION['id']) || $_SESSION['id'] == '') || (!isset($_SESSION['user']) || $_SESSION['user'] == '')) {
                include "./vistas/logout.php";
                exit();
            }
            
            include 'includes/navbar.php';

            include './vistas/'.$_GET['vista'].'.php';

            include 'includes/script.php';

        }else {
            if ($_GET['vista'] == "login") {
                include './vistas/login.php';

            }else {
                $_GET['vista'] == "404";
                include './vistas/404.php';
            }

        }

    ?> 

</body>

</html>