<?php
    require('../../base.php');
    $idPost = '';
    $tema = '';
    $posts = [];
    $cantHilos = 0;
    $nickname = '';

    $imagenes = $_FILES;

    $status = '';
    $result = 'ASD';

    if (isset($_POST['idPost'])) {
        $idPost = $_POST['idPost'];
        $idPost = str_replace('"', '', $idPost);
        // error_log($idUsuario, 3, "idUsuario.log");
    }
    if (isset($_POST['tema'])) {
        $tema = $_POST['tema'];
        $tema = str_replace('"', '', $tema);
        // error_log($nombre, 3, "nombre.log");
    }
    
    if ($conn) {
        $qPost = mysqli_query($conn, "SELECT * FROM post WHERE idPost = '$idPost'") or die(showError(mysqli_error($conn)));
        
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

                $qHilos = mysqli_query($conn, "SELECT * FROM hilos WHERE idPost = '$auxIdPost'") or die(showError(mysqli_error($conn)));
                $hilos = []; $contHilos = 0;
                while ($aux = mysqli_fetch_assoc($qHilos)) {
                    $hilos[$contHilos] = $aux;
                    $auxIdUsuario = $aux['idUsuario'];

                    $qCreador = mysqli_query($conn, "SELECT * FROM personalizacion WHERE idUsuario = '$auxIdUsuario'") or die(showError(mysqli_error($conn)));
                    $auxNick = mysqli_fetch_assoc($qCreador);
                    $nickname = $auxNick['Nickname'];

                    $hilos[$contHilos]['nickname'] = $nickname;

                    $contHilos++;
                }

                $posts[$cont]['hilos'] = $hilos;

                //Obtener los tags de cada publicacion
                $tags = [];
                $contTags = 0;
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join posttags on tags.idTag = posttags.idTag 
                WHERE idPost = '$idPost';") 
                or die(mysqli_error($conn));

                while ($auxTags = mysqli_fetch_assoc($qTags)) {
                    $tags [$contTags] = $auxTags;
                    $tags[$contTags]['id'] = $auxTags['idTag'];
                    $tags[$contTags]['name'] = $auxTags['nombreTag'];
                    $contTags++;
                }

                $posts[$cont]['tags'] = $tags;

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

    echo json_encode(array('status'=>$status, 'response'=>$result, 'post' => $posts[0]));

    function showError ($error) {
        error_log($error, 3, "php.log");
    }
?>