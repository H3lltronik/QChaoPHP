<?php
    require('../base.php');
    $idUsuario = '';
    $usuario = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if ($conn) {
        $qUsuario = mysqli_query($conn, "SELECT * FROM getuserinfo WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        if ($qUsuario) {
            // $qTags = mysqli_query($conn, "SELECT * FROM usuariotags WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
            // // Traer tags
            $usuario = mysqli_fetch_assoc($qUsuario);
            $status = 'OK';
            $result = 'SE ENCONTRO EL USUARIO ' . $idUsuario;            
        } else {
            $status = 'BAD';
            $result = 'ERROR NO SE ENCONTRO AL USUARIO';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'usuario'=>$usuario));
?>