<?php
    require('../base.php');
    $token = '';
    $timestamp = 0;

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

                $qBloqueo = mysqli_query($conn, "SELECT * FROM bloqueos WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
                // Obtener el timestamp del bloqueo para comparar si sigue bloqueado 
                $timestamp = mysqli_fetch_assoc($qBloqueo);
                $timestamp = $timestamp['timestampDesbloqueo'];
                $auxTimestamp = $timestamp;
                $auxTimestamp = intval($auxTimestamp);
                // $auxTimestamp = ($auxTimestamp / 1000); // Porque son milisegundos, quien sabe porque

                $today = new DateTime();
                $expireDate = new DateTime(); // Se crea la fecha del bloqueo sin valor
                $expireDate->setTimestamp($auxTimestamp); // Se le asigan el valor del timestamp del bloqueo

                if($today->format("Y-m-d") < $expireDate->format("Y-m-d")) {
                    $bloqueado = true;
            
                    $status = 'BLOQUEADO';
                    $result = 'USUARIO BLOQUEADO';
                }
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

    echo json_encode(array('status'=>$status, 'response'=>$result, 'user'=>$user, 'timestamp'=>$timestamp));
?>