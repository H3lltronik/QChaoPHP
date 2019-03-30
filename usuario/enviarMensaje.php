<?php
    require('../base.php');
    $idRemitente = '';
    $idDestino = '';
    $mensaje = '';

    $status = '';
    $result = '';

    if (isset($_POST['idRemitente'])) {
        $idRemitente = $_POST['idRemitente'];
    }

    if (isset($_POST['idDestino'])) {
        $idDestino = $_POST['idDestino'];
    }

    if (isset($_POST['mensaje'])) {
        $mensaje = $_POST['mensaje'];
    }

    if ($conn) {
        $qExiste = mysqli_query($conn, "SELECT * FROM mensajes WHERE 
        idRemitente = '$idRemitente' AND idDestino = '$idDestino'") or die(mysqli_error($conn));
        $auxExiste = mysqli_fetch_assoc($qExiste);
        if ($auxExiste['idRemitente']) {
            $qUpdateMen = mysqli_query($conn, "UPDATE mensajes SET mensaje = '$mensaje' WHERE 
            idRemitente = '$idRemitente' AND idDestino = '$idDestino'") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE ACTUALIZO EL MENSAJE';
        } else {
            $qMensaje = mysqli_query($conn, "INSERT INTO mensajes (idRemitente, idDestino, mensaje) VALUES 
            ('$idRemitente','$idDestino', '$mensaje');") or die(mysqli_error($conn));
            if ($qMensaje) {
                $status = 'OK';
                $result = 'SE AGREGO EL MENSAJE';
            } else {
                $status = 'BAD';
                $result = 'ERROR AL GUARDAR EL MENSAJE';
            }
        } 
        
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>