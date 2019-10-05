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
        $idUsuario = str_replace('"', '', $idUsuario);
    }
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $nombre = str_replace('"', '', $nombre);
    }
    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
        $descripcion = str_replace('"', '', $descripcion);
    }
    if (isset($_POST['domicilio'])) {
        $domicilio = $_POST['domicilio'];
        $domicilio = str_replace('"', '', $domicilio);
    }
    if (isset($_POST['contacto'])) {
        $contacto = $_POST['contacto'];
        $contacto = str_replace('"', '', $contacto);
    }
    if (isset($_POST['horario'])) {
        $horario = $_POST['horario'];
        $horario = str_replace('"', '', $horario);
    }
    if (isset($_POST['tipo'])) {
        $tipo = $_POST['tipo'];
        $tipo = str_replace('"', '', $tipo);
    }
    if (isset($_POST['ciudad'])) {
        $ciudad = $_POST['ciudad'];
        $ciudad = str_replace('"', '', $ciudad);
    }
    if (isset($_POST['tags'])) {
        $tags = $_POST['tags'];
        $primChar = substr($tags, 0, 1);
        if ($primChar == '"') {
            $tags = substr($tags, 1);
            $tags = substr($tags, 0, -1);
            $tags = stripslashes($tags);
        }
        //error_log("Tags: ".$tags."\n" , 3, "WOW.log");
        $tags = json_decode($tags);
    }


    if ($conn) {
        $cantImagenes = sizeof($imagenes);
        $qEvento = mysqli_query($conn, "INSERT INTO establecimientos 
        (idUsuario, Nombre, Descripcion, Domicilio, ciudad, Contacto, tipoEstablecimiento, horario, cantImagenes) VALUES 
        ('$idUsuario', '$nombre', '$descripcion', '$domicilio', '$ciudad', '$contacto', '$tipo', '$horario', '$cantImagenes')") 
        or die(showError(mysqli_error($conn)));
        if ($qEvento) {
            $idEstablecimiento = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idEstablecimiento = mysqli_fetch_assoc($idEstablecimiento);
            $idEstablecimiento = $idEstablecimiento['LAST_INSERT_ID()'];

            guardarImagenes ($idEstablecimiento);
            guardarTags($tags, $idEstablecimiento, $conn);
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

    function showError ($error) {
        error_log($error, 3, "php.log");
    }

    function guardarImagenes ($idEstablecimiento) {
        $cont = 0;
        foreach($_FILES as $aux) {
            $ext = 'jpg';
            $nombreFichero = $cont++  . '.' . $ext;
            $ruta = '../../../media/establecimientos/'.$idEstablecimiento;
            if(!is_dir($ruta))
                mkdir($ruta, 0777, true);
            $ruta .= '/' . $nombreFichero;
            move_uploaded_file($aux['tmp_name'], $ruta);
        }
    }

    function guardarTags ($tags, $idEstablecimiento, $conn) {
        foreach($tags as $auxTag) {
            $auxDecoded = json_decode($auxTag, true);
            $auxDecoded = $auxDecoded['name'];
            $qTags = mysqli_query($conn, "INSERT INTO `tags` (`nombreTag`) VALUES ('$auxDecoded') 
            ON DUPLICATE KEY UPDATE idTag=LAST_INSERT_ID(idTag), `nombreTag`='$auxDecoded';") 
            or die(showError(mysqli_error($conn)));

            $idTag = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idTag = mysqli_fetch_assoc($idTag);
            $idTag = $idTag['LAST_INSERT_ID()'];

            $qTag = mysqli_query($conn, "INSERT INTO establecimientostags (idEstablecimiento, idTag) VALUES ('$idEstablecimiento', '$idTag')") or die(showError(mysqli_error($conn)));
        }
    }
?>