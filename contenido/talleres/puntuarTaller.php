<?php
    require('../../base.php');
    $idUsuario = '';
    $idEstablecimiento = '';
    $calificacion = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }
    if (isset($_POST['idEstablecimiento'])) {
        $idEstablecimiento = $_POST['idEstablecimiento'];
    }
    if (isset($_POST['calificacion'])) {
        $calificacion = $_POST['calificacion'];
    }

    if ($conn) {
        $qExiste = mysqli_query($conn, "SELECT * FROM calificacionestablecimiento WHERE 
        idUsuario = '$idUsuario' AND idEstablecimiento = '$idEstablecimiento'") or die(mysqli_error($conn));
        $auxExiste = mysqli_fetch_assoc($qExiste);

        if ($auxExiste['idEstablecimiento']) {
            $qEvento = mysqli_query($conn, "UPDATE calificacionestablecimiento SET Calificacion = '$calificacion' WHERE 
            idUsuario = '$idUsuario' AND idEstablecimiento = '$idEstablecimiento'") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE ACTUALIZO LA CALIFICACION';
        } else {
            $qEvento = mysqli_query($conn, "INSERT INTO calificacionestablecimiento (idUsuario, idEstablecimiento, Calificacion) VALUES 
            ('$idUsuario', '$idEstablecimiento', '$calificacion')") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE REGISTRO LA CALIFICACION';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>