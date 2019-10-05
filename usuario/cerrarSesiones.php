<?php
    require('../base.php');
    $sesionToken = ''; if (isset($_POST['sesionToken'])) { $sesionToken = $_POST['sesionToken']; }

    if ($conn) {
        $qCerrar = mysqli_query($conn, "DELETE FROM sesiones WHERE sesionToken = '$sesionToken'") or die(mysqli_error($conn));
        if ($qCerrar) {
            $status = 'OK';
            $result = 'SE CERRARON LAS SESIONES';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>