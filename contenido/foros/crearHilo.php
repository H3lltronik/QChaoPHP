<?php
    require('../../base.php');
    $idUsuario = '';
    $idPost = '';
    $texto = '';
    $fichero = '';

    $status = '';
    $result = 'ASD';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
        $idUsuario = str_replace('"', '', $idUsuario);
        // error_log($idUsuario, 3, "idUsuario.log");
    }

    if (isset($_POST['idPost'])) {
        $idPost = $_POST['idPost'];
        $idPost = str_replace('"', '', $idPost);
        // error_log($idUsuario, 3, "idUsuario.log");
    }
    if (isset($_POST['texto'])) {
        $texto = $_POST['texto'];
        $texto = str_replace('"', '', $texto);
        // error_log($nombre, 3, "nombre.log");
    }

    if (isset($_FILES['file']['name'])) {
        $fichero = $_FILES['file']['name'];
    }

    $fecha = date("Y-m-d");
    $ext = pathinfo($fichero, PATHINFO_EXTENSION);
    $nombreFichero = generateId (10) . '.' . $ext;
    $ruta = '../../../media/hilos/' . $nombreFichero;
    
    if ($conn) {
        if ($fichero) {
            move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
            $ruta = 'media/hilos/' . $nombreFichero;
        } else {
            $ruta = ''; //Como no se sube nada si no hay fichero, la ruta la dejamos en blanco para guardar nada en la db
        }

        $status = 'OK';
        $result = 'FICHERO SUBIDO';

        $qPost = mysqli_query($conn, "INSERT INTO hilos (idPost, idUsuario, texto, imagen) VALUES ('$idPost', '$idUsuario', '$texto', '$ruta')") 
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
            $status = 'OK';
            $result .= 'SE HA CREADO EL HILO';
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