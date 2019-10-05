<?php
    require('../base.php');
    $idUsuario = null; if (isset($_POST['idUsuario'])) { $idUsuario = $_POST['idUsuario']; }

    $status = 'default';
    $result = 'default';
    $sesiones = [];

    if ($conn) {
        $qSesion = mysqli_query($conn, "SELECT * FROM sesiones WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        if ($qSesion) {
            $status = 'OK';
            $result = 'SE RECUPERARON LAS SESION';
            while($aux = mysqli_fetch_assoc($qSesion)) {
                $sesiones[] = $aux;
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR DE QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'ERROR DE CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'sesiones'=>$sesiones));
?>