<?php
    require('../../base.php');
    $idUsuario = '';
    $tema = '';
    $urgente = '';
    $tags = '';

    $status = '';
    $result = 'ASD';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
        $idUsuario = str_replace('"', '', $idUsuario);
        // error_log($idUsuario, 3, "idUsuario.log");
    }
    if (isset($_POST['tema'])) {
        $tema = $_POST['tema'];
        $tema = str_replace('"', '', $tema);
        // error_log($nombre, 3, "nombre.log");
    }
    if (isset($_POST['urgente'])) {
        $urgente = $_POST['urgente'];
        $urgente = str_replace('"', '', $urgente);
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
        if ($urgente == 'true') {
            $urgente = 1;
        }
        $qPost = mysqli_query($conn, "INSERT INTO post (idUsuario, tema, urgente) VALUES ('$idUsuario', '$tema', '$urgente')") 
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
            $idPost = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idPost = mysqli_fetch_assoc($idPost);
            $idPost = $idPost['LAST_INSERT_ID()'];

            guardarTags ($tags, $idPost, $conn);

            $status = 'OK';
            $result = 'SE HA CREADO EL POST';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));

    function guardarTags ($tags, $idPost, $conn) {
        foreach($tags as $auxTag) {
            $auxDecoded = json_decode($auxTag, true);
            $auxDecoded = $auxDecoded['name'];
            $qTags = mysqli_query($conn, "INSERT INTO `tags` (`nombreTag`) VALUES ('$auxDecoded') 
            ON DUPLICATE KEY UPDATE idTag=LAST_INSERT_ID(idTag), `nombreTag`='$auxDecoded';") 
            or die(mysqli_error($conn));

            $idTag = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idTag = mysqli_fetch_assoc($idTag);
            $idTag = $idTag['LAST_INSERT_ID()'];

            $qTag = mysqli_query($conn, "INSERT INTO posttags (idPost, idTag) VALUES ('$idPost', '$idTag')") or die(showError(mysqli_error($conn)));
            error_log("xd", 3, "Finally.txt");
        }
    }

    function showError ($error) {
        error_log($error, 3, "php.log");
    }
?>