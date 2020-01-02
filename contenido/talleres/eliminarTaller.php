<?php
    require('../../base.php');
    $idEstablecimiento = '';

    if (isset($_POST['idEstablecimiento'])) {
        $idEstablecimiento = $_POST['idEstablecimiento'];
        $idEstablecimiento = str_replace('"', '', $idEstablecimiento);
    }

    if ($conn) {
        $qEvento = mysqli_query($conn, "DELETE FROM establecimientos WHERE idEstablecimiento = '$idEstablecimiento'") 
        or die(showError(mysqli_error($conn)));
        
        if ($qEvento) {
            $status = 'OK';
            $result = 'SE HA ELIMINADO EL TALLER';
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