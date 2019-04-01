<?php
    require('../../base.php');
    $idUsuario = '';
    $nombre = '';
    $descripcion = '';
    $domicilio = '';
    $contacto = '';
    $horario = '';
    $tags = '';
    $tipo = '';
    $ciudad = '';
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
    if (isset($_POST['contacto'])) {
        $contacto = $_POST['contacto'];
    }
    if (isset($_POST['horario'])) {
        $horario = $_POST['horario'];
    }
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
    }
    if (isset($_POST['ciudad'])) {
        $ciudad = $_POST['ciudad'];
    }


    if ($conn) {
        $cantImagenes = sizeof($imagenes);
        $qEvento = mysqli_query($conn, "INSERT INTO establecimientos 
        (idUsuario, Nombre, Descripcion, Domicilio, ciudad, Contacto, tipoEstablecimiento, horario, cantImagenes) VALUES 
        ('$idUsuario', '$nombre', '$descripcion', '$domicilio', '$ciudad', '$contacto', '$tipo', '$horario', '$cantImagenes')") 
        or die(mysqli_error($conn));
        if ($qEvento) {
            $idEstablecimiento = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idEstablecimiento = mysqli_fetch_assoc($idEstablecimiento);
            $idEstablecimiento = $idEstablecimiento['LAST_INSERT_ID()'];

            guardarImagenes ($idEstablecimiento);
            $status = 'OK';
            $result = 'SE HA CREADO EL TALLER';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));

    function guardarImagenes ($idEstablecimiento) {
        $cont = 0;
        foreach($_FILES as $aux) {
            $ext = 'jpg';
            $nombreFichero = $cont++  . '.' . $ext;
            $ruta = '../../../media/establecimientos/'.$idEstablecimiento;
            if(!is_dir($ruta))
                mkdir($ruta);
            $ruta .= '/' . $nombreFichero;
            move_uploaded_file($aux['tmp_name'], $ruta);
        }
    }
?>