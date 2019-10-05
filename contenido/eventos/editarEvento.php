<?php
    require('../../base.php');
    $idEvento = '';
    $nombre = '';
    $descripcion = '';
    $domicilio = '';
    $fecha = '';
    $entrada = '';
    $ciudad = '';
    $tags = '';
    $imagenes = $_FILES;

    $status = '';
    $result = 'ASD';

    if (isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];
        $idEvento = str_replace('"', '', $idEvento);
    }
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $nombre = str_replace('"', '', $nombre);
        // error_log($nombre, 3, "nombre.log");
    }
    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
        $descripcion = str_replace('"', '', $descripcion);
        // error_log($descripcion, 3, "descripcion.log");
    }
    if (isset($_POST['domicilio'])) {
        $domicilio = $_POST['domicilio'];
        $domicilio = str_replace('"', '', $domicilio);
        // error_log($domicilio, 3, "domicilio.log");
    }
    if (isset($_POST['fecha'])) {
        $fecha = $_POST['fecha'];
        $fecha = str_replace('"', '', $fecha);
        // error_log($fecha, 3, "fecha.log");
    }
    if (isset($_POST['entrada'])) {
        $entrada = $_POST['entrada'];
        $entrada = str_replace('"', '', $entrada);
        // error_log($entrada, 3, "entrada.log");
    }
    if (isset($_POST['ciudad'])) {
        $ciudad = $_POST['ciudad'];
        $ciudad = str_replace('"', '', $ciudad);
        // error_log($ciudad, 3, "ciudad.log");
    }
    if (isset($_POST['tags'])) {
        $tags = $_POST['tags'];
        $primChar = substr($tags, 0, 1);
        if ($primChar == '"') {
            $tags = substr($tags, 1);
            $tags = substr($tags, 0, -1);
            $tags = stripslashes($tags);
        }
        $tags = json_decode($tags);
    }
    
    if ($conn) {
        $cantImagenes = sizeof($imagenes);

        $qEvento = mysqli_query($conn, "UPDATE eventos SET Titulo = '$nombre', Ubicacion = '$ciudad', domicilio = '$domicilio', Fecha = '$fecha', Descripcion = '$descripcion',
        entrada = '$entrada', cantImagenes = '$cantImagenes' WHERE idEvento = '$idEvento'") or die(showError(mysqli_error($conn)));
        
        if ($qEvento) {
            guardarImagenes ($idEvento);
            guardarTags ($tags, $idEvento, $conn);
            $status = 'OK';
            $result = 'SE HA MODIFICADO EL EVENTO';
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

    function guardarTags ($tags, $idEvento, $conn) {
        //error_log("Entrando a guardar tags: ".$tags."\n" , 3, "WOW.log");
        foreach($tags as $auxTag) {
            // error_log("Tag: ".$auxTag."\n" , 3, "WOW.log");
            $auxDecoded = json_decode($auxTag, true);
            $auxDecoded = $auxDecoded['name'];
            $qTags = mysqli_query($conn, "INSERT INTO `tags` (`nombreTag`) VALUES ('$auxDecoded') 
            ON DUPLICATE KEY UPDATE idTag=LAST_INSERT_ID(idTag), `nombreTag`='$auxDecoded';") 
            or die(mysqli_error($conn));

            $idTag = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idTag = mysqli_fetch_assoc($idTag);
            $idTag = $idTag['LAST_INSERT_ID()'];

            $existe = mysqli_query($conn, "SELECT * FROM eventostags WHERE idTag = '$idTag' AND idEvento = '$idEvento'");
            if (mysqli_num_rows($existe)==0) {
                $qTag = mysqli_query($conn, "INSERT INTO eventostags (idEvento, idTag) VALUES ('$idEvento', '$idTag')") or die(mysqli_error($conn));
            }
        }
    }
?>