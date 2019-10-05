<?php
    require('../../base.php');
    $reporte = [];

    $idReporte = ''; if (isset($_POST['idReporte'])) { $idReporte = $_POST['idReporte']; }

    if ($conn) {
        $qReporte = mysqli_query($conn, "SELECT * FROM reportes WHERE idReporte = '$idReporte'") 
        or die(mysqli_error($conn));
        if ($qReporte) {
            while ($auxReporte = mysqli_fetch_assoc($qReporte)) {
                $reporte[] = $auxReporte;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS REPORTES';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'reportes'=>$reporte[0]));
?>