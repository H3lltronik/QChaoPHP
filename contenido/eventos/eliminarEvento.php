<?php
    require('../../base.php');
    $idEvento = '';

    if (isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];
        $idEvento = str_replace('"', '', $idEvento);
    }
    
    if ($conn) {
        $qEvento = mysqli_query($conn, "DELETE FROM eventos WHERE idEvento = '$idEvento'") or die(showError(mysqli_error($conn)));
        
        if ($qEvento) {
            $status = 'OK';
            $result = 'SE HA ELIMINADO EL EVENTO';
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