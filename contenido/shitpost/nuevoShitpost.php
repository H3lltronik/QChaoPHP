<?php
    require('../../base.php');
    $idUsuario = '';
    $descripcion = '';
    $fichero = '';
    $tags = '';
    $fecha = '';
    $idShitpost = '';
    $tipo = '';

    $status = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
        $idUsuario = str_replace('"', '', $idUsuario);
    }

    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
        $descripcion = str_replace('"', '', $descripcion);
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

    if (isset($_FILES['file']['name'])) {
        $fichero = $_FILES['file']['name'];
    }

    $fecha = date("Y-m-d");
    $ext = pathinfo($fichero, PATHINFO_EXTENSION);
    $nombreFichero = generateId (10) . '.' . $ext;
    $ruta = '../../../media/shitpost/' . $nombreFichero;
    if (!is_dir('../../../media/shitpost/'))
        mkdir('../../../media/shitpost/', 0777, true);

    if ($conn) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $ruta)) {
            $status = 'OK';
            $result = 'FICHERO SUBIDO';

            $mime = mime_content_type($ruta);
            if(strstr($mime, "video/")){
                $tipo = 'video';
            }else if(strstr($mime, "image/")){
                $tipo = 'image';
            }

            $qShitpost = mysqli_query($conn, "INSERT INTO shitpost (idUsuario, descripcion, imagen, fecha, tipo) 
            VALUES ('$idUsuario', '$descripcion', '$nombreFichero', '$fecha', '$tipo')")  or die(showError(mysqli_error($conn)));
            if ($qShitpost) {

                $idShitpost = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
                $idShitpost = mysqli_fetch_assoc($idShitpost);
                $idShitpost = $idShitpost['LAST_INSERT_ID()'];

                guardarTags ($tags, $idShitpost, $conn);

                $status = 'OK';
                $result .= 'INFORMACION GUARDADA';
            } else {
                $status = 'BAD';
                $result = 'ERROR EN EL QUERY';
            }
        } else {
            $status = 'BAD';
            $result = 'NO SE PUDO SUBIR EL CONTENIDO';    
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'fecha'=>$fecha, 'idShitpost'=>$idShitpost, 'fichero'=>$nombreFichero, 'tipo'=>$tipo));

    function guardarTags ($tags, $idShitpost, $conn) {
        foreach($tags as $auxTag) {
            $auxDecoded = json_decode($auxTag, true);
            $auxDecoded = $auxDecoded['name'];
            $qTags = mysqli_query($conn, "INSERT INTO `tags` (`nombreTag`) VALUES ('$auxDecoded') 
            ON DUPLICATE KEY UPDATE idTag=LAST_INSERT_ID(idTag), `nombreTag`='$auxDecoded';") 
            or die(mysqli_error($conn));

            $idTag = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idTag = mysqli_fetch_assoc($idTag);
            $idTag = $idTag['LAST_INSERT_ID()'];

            $qTag = mysqli_query($conn, "INSERT INTO shitposttags (idShitpost, idTag) VALUES ('$idShitpost', '$idTag')") or die(mysqli_error($conn));
        }
    }

    function showError ($error) {
        error_log($error, 3, "php.log");
    }
?>