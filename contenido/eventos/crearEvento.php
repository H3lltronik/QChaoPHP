<?php
    require('../../base.php');
    $idUsuario = '';
    $nombre = '';
    $descripcion = '';
    $domicilio = '';
    $fecha = '';
    $entrada = '';
    $ciudad = '';
    $tags = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    }
    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
    }
    if (isset($_POST['domicilio'])) {
        $domicilio = $_POST['domicilio'];
    }
    if (isset($_POST['fecha'])) {
        $fecha = $_POST['fecha'];
    }
    if (isset($_POST['entrada'])) {
        $entrada = $_POST['entrada'];
    }
    if (isset($_POST['ciudad'])) {
        $ciudad = $_POST['ciudad'];
    }

    if ($conn) {
        $qEvento = mysqli_query($conn, "INSERT INTO eventos (idUsuario, Titulo, Ubicacion, domicilio, 
        Fecha, Descripcion) VALUES ('$idUsuario', '$nombre', '$ciudad', '$domicilio', '$fecha', '$descripcion')") 
        or die(mysqli_error($conn));
        if ($qEvento) {
            $status = 'OK';
            $result = 'SE HA CREADO EL EVENTO';
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