<?php
    require('../base.php');
    $idUsuario = NULL;
    $nombre = NULL;
    $correo = NULL;
    $password = NULL;
    $telefono = NULL;

    $state = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    }

    if (isset($_POST['correo'])) {
        $correo = $_POST['correo'];
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    
    if (isset($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
    }

    if ($conn) {
        $qCambiarLog = mysqli_query($conn, "UPDATE usuario SET 
        Nombre = '$nombre', Correo = '$correo', Pass = '$password' WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        $qCambiarTel = mysqli_query($conn, "UPDATE personalizacion SET 
        Telefono = '$telefono' WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));

        if ($qCambiarLog) {
            $result .= ' SE CAMBIO EL USUARIO ';
        } else {
            $result .= ' NO SE CAMBIO EL USUARIO ';
        }

        if ($qCambiarTel) {
            $result .= ' SE CAMBIO EL TELFONO ';
        } else {
            $result .= ' NO SE CAMBIO EL TELEFONO ';
        }

        $state = 'OK';
    } else {
        $state = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$state, 'response'=>$result));
?> 