<?php
    require('../base.php');
    $tags = ''; if (isset($_POST['tags'])) { $tags = json_decode($_POST['tags']); }
    $tagsIds = [];

    if ($conn) {
        // Conseguir las IDS de los tags
        foreach ($tags as $auxTag) {
            $qTags = mysqli_query($conn, "SELECT * FROM tags WHERE nombreTag = '$auxTag'")  or die(mysqli_error($conn));
            $tagId = mysqli_fetch_assoc($qTags);
            $tagsIds[] = $tagId['idTag'];
        }
        // Con las IDS ahora si buscar por contenido
        $shitpostContent = [];  $contShitpost = 0;
        $eventosContent = [];  $contEventos = 0;
        $talleresContent = [];  $contTalleres = 0;

        foreach ($tagsIds as $auxTag) {
            // ---------------------- SHITPOST ------------------------------------- //
            $qShitpost = mysqli_query($conn, "SELECT * FROM shitposttags INNER JOIN getshitpostdata ON shitposttags.idShitpost = getshitpostdata.idShitpost WHERE idTag = '$auxTag'") 
            or die(mysqli_error($conn));
            while ($qShitpostRes = mysqli_fetch_assoc($qShitpost)) {
                // Obtener los tags
                $idShitpost = $qShitpostRes['idShitpost'];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join shitposttags on tags.idTag = shitposttags.idTag WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));
                $tags = getTags($qTags);
                //Obtener los likes de cada publicacion
                $qLikes = mysqli_query($conn, "SELECT COUNT(*) FROM Likes WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));
                $cantLikes = mysqli_fetch_assoc($qLikes);
                $cantLikes = $cantLikes['COUNT(*)'];

                //Obtener los comentarios de cada publicacion
                $comentarios = [];
                $qComentarios = mysqli_query($conn, "SELECT * FROM getComentariosData WHERE idShitpost = '$idShitpost';") 
                or die(mysqli_error($conn));

                while ($auxComentario = mysqli_fetch_assoc($qComentarios)) {
                    $comentarios [] = $auxComentario;
                }

                if (empty($shitpostContent)) {
                    $shitpostContent[$contShitpost] = $qShitpostRes;
                    $shitpostContent[$contShitpost]['tags'] = $tags;
                    $shitpostContent[$contShitpost]['likes'] = $cantLikes;
                    $shitpostContent[$contShitpost]['comentarios'] = $comentarios;
                    $contShitpost++;
                } else {
                    $contCoin = 0;
                    foreach ($shitpostContent as $shitpostIt) {
                        if ($shitpostIt['idShitpost'] == $qShitpostRes['idShitpost']) {
                            $contCoin++;
                        }
                    }
                    if ($contCoin <= 0) {
                        $shitpostContent[$contShitpost] = $qShitpostRes;
                        $shitpostContent[$contShitpost]['tags'] = $tags;
                        $shitpostContent[$contShitpost]['likes'] = $cantLikes;
                        $shitpostContent[$contShitpost]['comentarios'] = $comentarios;
                        $contShitpost++;
                    }
                    $contCoin = 0;
                }
            }


            // ---------------------- EVENTOS ------------------------------------- //
            $qEventos = mysqli_query($conn, "SELECT * FROM eventostags INNER JOIN getEventData ON eventostags.idEvento = getEventData.idEvento WHERE idTag = '$auxTag'") 
            or die(mysqli_error($conn));
            while ($qEventosRes = mysqli_fetch_assoc($qEventos)) {
                // Obtener los tags
                $idEvento = $qEventosRes['idEvento'];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join eventostags on tags.idTag = eventostags.idTag WHERE idEvento = '$idEvento';") 
                or die(mysqli_error($conn));
                $tags = getTags($qTags);

                // Obtener calificaciones -------------------------------------------- //
                $calificaciones = [];
                $qCalificaciones = mysqli_query($conn, "SELECT * FROM calificacion WHERE idEvento = '$idEvento'") or die(mysqli_error($conn));
                while ($auxCalificacion = mysqli_fetch_assoc($qCalificaciones)) {
                    $calificaciones[] = $auxCalificacion;
                }

                if (empty($eventosContent)) {
                    $eventosContent[$contEventos] = $qEventosRes;
                    $eventosContent[$contEventos]['tags'] = $tags;
                    $eventosContent[$contEventos]['calificaciones'] = $calificaciones;
                    $contEventos++;
                } else {
                    $contCoin = 0;
                    foreach ($eventosContent as $eventoIt) {
                        if ($eventoIt['idEvento'] == $qEventosRes['idEvento']) {
                            $contCoin++;
                        }
                    }
                    if ($contCoin <= 0) {
                        $eventosContent[$contEventos] = $qEventosRes;
                        $eventosContent[$contEventos]['tags'] = $tags;
                        $eventosContent[$contEventos]['calificaciones'] = $calificaciones;
                        $contEventos++;
                    }
                    $contCoin = 0;
                }
            }

            // ---------------------- ESTABLECIMIENTOS ------------------------------------- //
            $qEstablecimientos = mysqli_query($conn, "SELECT * FROM establecimientostags INNER JOIN gettalleresdata ON establecimientostags.idEstablecimiento = gettalleresdata.idEstablecimiento WHERE idTag = '$auxTag'") 
            or die(mysqli_error($conn));
            while ($qEstablecimientosRes = mysqli_fetch_assoc($qEstablecimientos)) {
                // Obtener los tags
                $idEstablecimiento = $qEstablecimientosRes['idEstablecimiento'];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join establecimientostags on tags.idTag = establecimientostags.idTag WHERE idEstablecimiento = '$idEstablecimiento';") 
                or die(mysqli_error($conn));
                $tags = getTags($qTags);

                if (empty($talleresContent)) {
                    $talleresContent[$contTalleres] = $qEstablecimientosRes;
                    $talleresContent[$contTalleres]['tags'] = $tags;
                    $contTalleres++;
                } else {
                    $contCoin = 0;
                    foreach ($talleresContent as $establecimientoIt) {
                        if ($establecimientoIt['idEstablecimiento'] == $qEstablecimientosRes['idEstablecimiento']) {
                            $contCoin++;
                        }
                    }
                    if ($contCoin <= 0) {
                        $talleresContent[$contTalleres] = $qEstablecimientosRes;
                        $talleresContent[$contTalleres]['tags'] = $tags;
                        $contTalleres++;
                    }
                    $contCoin = 0;
                }
            }
        }

        
        // print('Shitpost: <br>');
        // print_r($shitpostContent);
        // print('Eventos: <br>');
        // print_r($eventosContent);
        // print('Establecimientos: <br>');
        // print_r($talleresContent);

        $status = 'OK';
        $result = 'BUSQUEDA REALIZADA';
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    function getTags ($qTags) {
        //Obtener los tags de cada publicacion
        $cont = 0;
        $tags = [];

        while ($auxTags = mysqli_fetch_assoc($qTags)) {
            $tags [$cont] = $auxTags;
            $tags[$cont]['id'] = $auxTags['idTag'];
            $tags[$cont]['name'] = $auxTags['nombreTag'];
            $cont++;
        }
        return $tags;
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'shitposts'=> $shitpostContent,'eventos'=> $eventosContent,'talleres'=>$talleresContent));
?>