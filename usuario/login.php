<?php
    require('../base.php');
    $password = '';
    $nombre = '';
    $user = '';

    $status = '';
    $result = '';

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }

    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    }

    if ($conn) {
        $qBuscar = mysqli_query($conn, "SELECT * FROM getuserinfo WHERE Nombre = '$nombre' AND pass = '$password';") or die(mysqli_error($conn));
        if ($qBuscar) {
            $qResult = mysqli_fetch_assoc($qBuscar);
            $user = $qResult;
            if ($qResult['nombre'] === $nombre) {
                $status = 'OK';
                $result = 'SI EXISTE USUARIO';
            } else {
                $status = 'OK';
                $result = 'NO EXISTE USUARIO';
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR AL HACER QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'user'=>$user));
?>