<?php
    require('../../base.php');

    $status = '';
    $result = '';
    $contenido = [];

    if ($conn) {
        $qContenido = mysqli_query($conn, "SELECT * FROM getshitpostdata;") or die(mysqli_error($conn));
        if ($qContenido) {
            $contContenido = 0;
            while ($aux = mysqli_fetch_assoc($qContenido)) {
                $contenido[$contContenido] = $aux;
                $idShitpost = $aux['idShitpost'];
                //Obtener los likes de cada publicacion
                $qLikes = mysqli_query($conn, "SELECT COUNT(*) FROM Likes WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));
                $cantLikes = mysqli_fetch_assoc($qLikes);
                $cantLikes = $cantLikes['COUNT(*)'];
                // Guardar el resultado
                $contenido[$contContenido]['likes'] = $cantLikes;

                //Obtener los comentarios de cada publicacion
                $comentarios = [];
                $qComentarios = mysqli_query($conn, "SELECT * FROM getComentariosData WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));

                while ($auxComentario = mysqli_fetch_assoc($qComentarios)) {
                    $comentarios [] = $auxComentario;
                }
                // Guardar el resultado
                $contenido[$contContenido]['comentarios'] = $comentarios;

                //Obtener los tags de cada publicacion
                $cont = 0;
                $tags = [];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join shitposttags on tags.idTag = shitposttags.idTag 
                WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));

                while ($auxTags = mysqli_fetch_assoc($qTags)) {
                    $tags [$cont] = $auxTags;
                    $tags[$cont]['id'] = $auxTags['idTag'];
                    $tags[$cont]['name'] = $auxTags['nombreTag'];
                    $cont++;
                }
                // Guardar el resultado
                $contenido[$contContenido]['tags'] = $tags;

                $contContenido++;
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'contenido'=>$contenido));
?>