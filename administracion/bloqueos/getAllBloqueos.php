<?php
    require('../../base.php');
    $bloqueos = [];

    if ($conn) {
        $qBloqueos = mysqli_query($conn, "SELECT * FROM bloqueos") or die(mysqli_error($conn));
        if ($qBloqueos) {
            while ($auxBloqueo = mysqli_fetch_assoc($qBloqueos)) {
                $bloqueos[] = $auxBloqueo;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS BLOQUEOS';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'bloqueos'=>$bloqueos));
?>