<?php
    // Este php es mas que nada para borrar la sesion de la tabla de sesiones
    require('../base.php');
    $token = null; if (isset($_POST['token'])) { $token = $_POST['token']; }

    $status = 'default';
    $result = 'default';

    if ($conn) {
        $token = str_replace('"', '', $token);
        $qDel = mysqli_query($conn, "DELETE FROM sesiones WHERE sesionToken = '$token'") or die(mysqli_error($conn));
        if ($qDel) {
            $status = 'OK';
            $result = 'SE BORRO LA SESION';
        } else {
            $status = 'BAD';
            $result = 'ERROR DE QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'ERROR DE CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>