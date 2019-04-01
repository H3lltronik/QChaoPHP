<?php
    require('../../base.php');
    $idUsuario = '';
    $idEvento = '';
    $calificacion = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }
    if (isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];
    }
    if (isset($_POST['calificacion'])) {
        $calificacion = $_POST['calificacion'];
    }

    if ($conn) {
        $qExiste = mysqli_query($conn, "SELECT * FROM calificacion WHERE 
        idUsuario = '$idUsuario' AND idEvento = '$idEvento'") or die(mysqli_error($conn));
        $auxExiste = mysqli_fetch_assoc($qExiste);

        if ($auxExiste['idEvento']) {
            $qEvento = mysqli_query($conn, "UPDATE calificacion SET calificacion = '$calificacion' WHERE 
            idUsuario = '$idUsuario' AND idEvento = '$idEvento'") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE ACTUALIZO LA CALIFICACION';
        } else {
            $qEvento = mysqli_query($conn, "INSERT INTO calificacion (idUsuario, idEvento, calificacion) VALUES 
            ('$idUsuario', '$idEvento', '$calificacion')") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE REGISTRO LA CALIFICACION';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>