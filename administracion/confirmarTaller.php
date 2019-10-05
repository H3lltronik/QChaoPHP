<?php
    require('../base.php');
    $idTaller = ''; if (isset($_POST['idTaller'])) { $idTaller = $_POST['idTaller']; }

    if ($conn) {
        $qTaller = mysqli_query($conn, "UPDATE establecimientos SET Verificado = 1 WHERE idEstablecimiento = '$idTaller'") 
        or die(mysqli_error($conn));
        if ($qTaller) {
            // Borrar de verificaciones
            $qBorrar = mysqli_query($conn, "DELETE FROM verificaciones WHERE idTaller = '$idTaller'")
            or die(mysqli_error($conn));

            if ($qBorrar) {
                $status = 'OK';
                $result = 'SE VERIFICO EL ESTABLECIMIENTO';
            } else {
                $status = 'BAD';
                $result = 'NO SE PUDO BORRAR DE VERIFICACIONES';
            }
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