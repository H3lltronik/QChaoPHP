<?php
    require('../../base.php');
    $idUsuario = '';
    $tema = '';
    $urgente = '';

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
    
    if ($conn) {
        if ($urgente == 'true') {
            $urgente = 1;
        }
        $qPost = mysqli_query($conn, "INSERT INTO post (idUsuario, tema, urgente) VALUES ('$idUsuario', '$tema', '$urgente')") 
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
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

    function showError ($error) {
        error_log($error, 3, "php.log");
    }
?>