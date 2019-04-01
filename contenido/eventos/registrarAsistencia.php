<?php
    require('../../base.php');
    $idUsuario = '';
    $idEvento = '';
    $type = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }
    if (isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];
    }
    if ($conn) {
        $qExiste = mysqli_query($conn, "SELECT * FROM asistire WHERE 
        idUsuario = '$idUsuario' AND idEvento = '$idEvento'") or die(mysqli_error($conn));
        $auxExiste = mysqli_fetch_assoc($qExiste);

        if ($auxExiste['idEvento']) {
            $qEvento = mysqli_query($conn, "DELETE FROM asistire WHERE 
            idUsuario = '$idUsuario' AND idEvento = '$idEvento'") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE ELIMINO LA ASISTENCIA';
            $type = 'remove';
        } else {
            $qEvento = mysqli_query($conn, "INSERT INTO asistire (idUsuario, idEvento) VALUES 
            ('$idUsuario', '$idEvento')") or die(mysqli_error($conn));
            $status = 'OK';
            $result = 'SE REGISTRO LA ASISTENCIA';
            $type = 'add';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'type'=>$type));
?>