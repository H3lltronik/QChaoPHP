<?php
    require('../../base.php');
    $idHilo = null;
    $status = '';
    $result = '';

    if (isset($_POST['idHilo'])) {
        $idHilo = $_POST['idHilo'];
        $idHilo = str_replace('"', '', $idHilo);
        // error_log($idUsuario, 3, "idUsuario.log");
    }
    
    if ($conn) {
        $qPost = mysqli_query($conn, "DELETE FROM hilos WHERE idHilo = '$idHilo'")
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
            $status = 'OK';
            $result .= 'SE HA BORRADO EL HILO';
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