<?php
    require('../../base.php');
    $idPost = '';
    $tema = '';
    $urgente = '';

    $status = '';
    $result = 'ASD';

    if (isset($_POST['idPost'])) {
        $idPost = $_POST['idPost'];
        $idPost = str_replace('"', '', $idPost);
        // error_log($idPost, 3, "idPost.log");
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
        $qPost = mysqli_query($conn, "UPDATE post SET tema = '$tema', urgente = '$urgente' WHERE idPost = '$idPost'") 
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
            $status = 'OK';
            $result = 'SE HA EDITADO EL POST';
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