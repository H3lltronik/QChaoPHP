<?php
    require('../base.php');
    $idUsuario = '';
    $nombre = '';
    $correo = '';
    $password = '';
    $telefono = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
        //$idUsuario = str_replace('"', '', $idUsuario);
    }

    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        //$nombre = str_replace('"', '', $nombre);
    }

    if (isset($_POST['correo'])) {
        $correo = $_POST['correo'];
        //$correo = str_replace('"', '', $correo);
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        //$password = str_replace('"', '', $password);
    }

    if (isset($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
        //$telefono = str_replace('"', '', $telefono);
    }

    if ($conn) {
        $qSaveConf = mysqli_query($conn, "UPDATE usuario SET Nombre = '$nombre', Correo = '$correo',
        Pass = '$password' WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));

        $qSaveTel = mysqli_query($conn, "UPDATE personalizacion SET Telefono = '$telefono' 
        WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        if ($qSaveConf) {
            $result = ' SE GUARDO LA CONFIGURACION ';
        }

        if ($qSaveTel) {
            $result = ' SE GUARDO EL TELEFONO ';
        }
        $status = 'OK';
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>