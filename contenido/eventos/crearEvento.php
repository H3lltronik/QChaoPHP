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
    $imagenes = $_FILES;

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
        $cantImagenes = sizeof($imagenes);
        $qEvento = mysqli_query($conn, "INSERT INTO eventos 
        (idUsuario, Titulo, Ubicacion, domicilio, Fecha, Descripcion, entrada, cantImagenes) VALUES 
        ('$idUsuario', '$nombre', '$ciudad', '$domicilio', '$fecha', '$descripcion', '$entrada', '$cantImagenes')") 
        or die(mysqli_error($conn));
        if ($qEvento) {
            $idEvento = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idEvento = mysqli_fetch_assoc($idEvento);
            $idEvento = $idEvento['LAST_INSERT_ID()'];

            guardarImagenes ($idEvento);
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

    function guardarImagenes ($idEvento) {
        $cont = 0;
        foreach($_FILES as $aux) {
            $ext = 'jpg';
            $nombreFichero = $cont++  . '.' . $ext;
            $ruta = '../../../media/eventos/'.$idEvento;
            if(!is_dir($ruta))
                mkdir($ruta);
            $ruta .= '/' . $nombreFichero;
            move_uploaded_file($aux['tmp_name'], $ruta);
        }
    }
?>