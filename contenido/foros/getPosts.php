<?php
    require('../../base.php');
    $idUsuario = '';
    $tema = '';
    $posts = [];
    $cantHilos = 0;
    $nickname = '';

    $imagenes = $_FILES;

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
    
    if ($conn) {
        $qPost = mysqli_query($conn, "SELECT * FROM post") or die(showError(mysqli_error($conn)));
        
        $cont = 0;
        if ($qPost) {
            while ($aux = mysqli_fetch_assoc($qPost)) {
                $posts[$cont] = $aux;
                $auxIdPost = $aux['idPost'];
                $auxIdUsuario = $aux['idUsuario'];

                $qCreador = mysqli_query($conn, "SELECT * FROM personalizacion WHERE idUsuario = '$auxIdUsuario'") or die(showError(mysqli_error($conn)));
                $auxNick = mysqli_fetch_assoc($qCreador);
                $nickname = $auxNick['Nickname'];

                $posts[$cont]['nickname'] = $nickname;

                $qHilos = mysqli_query($conn, "SELECT count(*) as total FROM hilos WHERE idPost = '$auxIdPost'") or die(showError(mysqli_error($conn)));
                $data= mysqli_fetch_assoc($qHilos);
                $cantHilos = $data['total'];

                $posts[$cont]['participantes'] = $cantHilos;

                $cont++;
            }

            $status = 'OK';
            $result = 'SE HAN OBTENIDO LOS POST';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'posts' => $posts));

    function showError ($error) {
        error_log($error, 3, "php.log");
    }
?>