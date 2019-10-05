<?php
    require('../base.php');
    $confirmaciones = [];

    if ($conn) {
        $qTaller = mysqli_query($conn, "SELECT * FROM verificaciones") or die(mysqli_error($conn));
        if ($qTaller) {
            while ($auxTaller = mysqli_fetch_assoc($qTaller)) {
                $confirmaciones[] = $auxTaller;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS ESTABLECIMIENTOS';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'confirmaciones'=>$confirmaciones));
?>