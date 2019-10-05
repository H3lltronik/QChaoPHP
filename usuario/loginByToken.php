<?php
    require('../base.php');
    $token = '';

    $status = 'default';
    $result = 'default';

    if (isset($_POST['token'])) {
        $token = $_POST['token'];
    }

    if ($conn) {
        $qToken = mysqli_query($conn, "SELECT * FROM sesiones WHERE sesionToken = '$token'") or die(mysqli_error($conn));
        if ($qToken) {
            $qResult = mysqli_fetch_assoc($qToken);
            $idUsuario = $qResult['idUsuario'];

            $qUserInfo = mysqli_query($conn, "SELECT * FROM getuserinfo WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
            if ($qUserInfo) {
                $qResult = mysqli_fetch_assoc($qUserInfo);
                $user = $qResult;

                $status = 'OK';
                $result = 'TOKEN EXISTENTE';
            } else {
                $status = 'BAD';
                $result = 'TOKEN NO EXISTENTE';
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'user'=>$user));
?>