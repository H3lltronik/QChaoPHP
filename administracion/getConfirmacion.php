<?php
    require('../base.php');
    $confirmacion = "";
    $idVerificacion = "";

    if (isset($_POST['idVerificacion'])) {
        $idVerificacion = $_POST['idVerificacion'];
    }

    if ($conn) {
        $qTaller = mysqli_query($conn, "SELECT * FROM verificaciones WHERE idVerificacion = '$idVerificacion'") or die(mysqli_error($conn));
        if ($qTaller) {
            $confirmacion = mysqli_fetch_assoc($qTaller);
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

    echo json_encode(array('status'=>$status, 'response'=>$result, 'confirmacion'=>$confirmacion));
?>