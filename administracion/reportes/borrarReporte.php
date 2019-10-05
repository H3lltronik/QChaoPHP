<?php
    require('../../base.php');
    $idReporte = ''; if (isset($_POST['idReporte'])) { $idReporte = $_POST['idReporte']; }
    $reporte = [];

    if ($conn) {
        $qReporte = mysqli_query($conn, "DELETE FROM reportes WHERE idReporte = '$idReporte'") or die(mysqli_error($conn));
        if ($qReporte) {
            $status = 'OK';
            $result = 'SE ELIMINO EL REPORTE';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>