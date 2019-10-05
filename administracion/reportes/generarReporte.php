<?php
    require('../../base.php');
    $mensaje = ''; if (isset($_POST['mensaje'])) { $mensaje = $_POST['mensaje']; }
    $contenido = ''; if (isset($_POST['contenido'])) { $contenido = $_POST['contenido']; }
    $idUsuario = ''; if (isset($_POST['idUsuario'])) { $idUsuario = $_POST['idUsuario']; }
    $tipo = ''; if (isset($_POST['tipo'])) { $tipo = $_POST['tipo']; }
    $idReporte = '';

    if ($conn) {
        $qReporte = mysqli_query($conn, "INSERT INTO reportes (idUsuario, mensaje, contenido, tipo) 
        VALUES ('$idUsuario','$mensaje', '$contenido', '$tipo')") or die(mysqli_error($conn));

        if ($qReporte) {
            $idAux = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idAux = mysqli_fetch_assoc($idAux);
            $idAux = $idAux['LAST_INSERT_ID()'];
            $idReporte = $idAux;

            $status = 'OK';
            $result = 'SE GENERO EL REPORTE';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'idReporte'=>$idReporte));
?>