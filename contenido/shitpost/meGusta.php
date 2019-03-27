<?php
    require('../../base.php');
    $idUsuario = '';
    $idShitpost = '';
    // add va a controlar si se añadio (1) el like o se quito (0)
    $add = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if (isset($_POST['idShitpost'])) {
        $idShitpost = $_POST['idShitpost'];
    }

    if ($conn) {
        // Verificar si el usuario ya le dio like a la publicacion entonces se quita el like
        $qLike = mysqli_query($conn, "SELECT * FROM likes WHERE idShitpost = '$idShitpost' AND 
        idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        if ($qLike) {
            $resLike = mysqli_fetch_assoc($qLike);
            if ($resLike) {
                $qRemoveLike = mysqli_query($conn, "DELETE FROM likes WHERE idShitpost = '$idShitpost' AND 
                idUsuario = '$idUsuario'") or die(mysqli_error($conn));
                if ($qRemoveLike) {
                    $status = 'OK';
                    $result = 'SE REMOVIO EL LIKE';
                    $add = false;
                } else {
                    $status = 'BAD';
                    $result = 'NO SE PUDO REMOVER EL LIKE';
                }
            } else {
                $qSetLike = mysqli_query($conn, "INSERT INTO likes (idShitpost, idUsuario) VALUES 
                ('$idShitpost', '$idUsuario')") or die(mysqli_error($conn));
                if ($qSetLike) {
                    $status = 'OK';
                    $result = 'SE AÑADIO EL LIKE';
                    $add = true;
                } else {
                    $status = 'BAD';
                    $result = 'NO SE PUDO AÑADIR EL LIKE';
                }
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR AL HACER QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'add'=>$add));
?>