<?php
    require('../../base.php');
    $idHilo = null;
    $texto = '';
    $fichero = '';

    $status = '';
    $result = '';

    if (isset($_POST['idHilo'])) {
        $idHilo = $_POST['idHilo'];
        $idHilo = str_replace('"', '', $idHilo);
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

    
    if ($conn) {
        $qOldRuta = mysqli_query($conn, "SELECT imagen FROM hilos WHERE idHilo = '$idHilo';") or die(showError(mysqli_error($conn)));
        $oldRuta = mysqli_fetch_assoc($qOldRuta)['imagen'];
        $ruta = '../../../' . $oldRuta;

        if ($fichero) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ruta)) {
                $status = 'OK';
                $result = 'FICHERO SUBIDO';
            }
        } 

        $qPost = mysqli_query($conn, "UPDATE hilos SET texto = '$texto' WHERE idHilo = '$idHilo';") 
        or die(showError(mysqli_error($conn)));
        
        if ($qPost) {
            $status = 'OK';
            $result .= 'SE HA EDITADO EL HILO';
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