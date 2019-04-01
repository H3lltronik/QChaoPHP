<?php
    require('../../base.php');
    $talleres = [];

    if ($conn) {
        $qTaller = mysqli_query($conn, "SELECT * FROM gettalleresdata") or die(mysqli_error($conn));
        if ($qTaller) {
            while ($auxTaller = mysqli_fetch_assoc($qTaller)) {
                $talleres[] = $auxTaller;
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

    echo json_encode(array('status'=>$status, 'response'=>$result, 'talleres'=>$talleres));
?>