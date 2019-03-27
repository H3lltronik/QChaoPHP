<?php
    require('../../base.php');
    $idUsuario = '';
    $idShitpost = '';
    $comentario = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if (isset($_POST['idShitpost'])) {
        $idShitpost = $_POST['idShitpost'];
    }

    if (isset($_POST['comentario'])) {
        $comentario = $_POST['comentario'];
    }

    if ($conn) {
        $qComentar = mysqli_query($conn, "INSERT INTO comentario (idUsuario, idShitpost, comentario) 
        VALUES ('$idUsuario', '$idShitpost', '$comentario')") or die(mysqli_error($conn));
        if ($qComentar) {
            $status = 'OK';
            $result = 'SE HA AÑADIDO EL COMENTARIO';
        } else {
            $status = 'BAD';
            $result = 'ERROR AL HACER QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>