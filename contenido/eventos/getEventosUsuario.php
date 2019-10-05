<?php
    require('../../base.php');
    $eventos = [];
    $idUsuario = ''; if (isset($_POST['idUsuario'])) { $idUsuario = $_POST['idUsuario']; }

    if ($conn) {
        $qEvento = mysqli_query($conn, "SELECT * FROM getEventData WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        if ($qEvento) {
            $contEventos = 0;
            while ($auxEvento = mysqli_fetch_assoc($qEvento)) {
                $eventos[$contEventos] = $auxEvento;
                $idEvento = $eventos[$contEventos]['idEvento'];
                // Obtener asistentes
                $qAsistencias = mysqli_query($conn, "SELECT COUNT(*) FROM asistire WHERE idEvento = '$idEvento'") or die(mysqli_error($conn));
                $cantAsistentes = mysqli_fetch_assoc($qAsistencias);
                $cantAsistentes = $cantAsistentes['COUNT(*)'];
                $eventos[$contEventos]['asistentes'] = $cantAsistentes;

                // Obtener calificaciones -------------------------------------------- //
                $calificaciones = [];
                $qCalificaciones = mysqli_query($conn, "SELECT * FROM calificacion WHERE idEvento = '$idEvento'") or die(mysqli_error($conn));
                while ($auxCalificacion = mysqli_fetch_assoc($qCalificaciones)) {
                    $calificaciones[] = $auxCalificacion;
                }
                $eventos[$contEventos]['calificaciones'] = $calificaciones;

                //Obtener los tags de cada publicacion
                $cont = 0;
                $tags = [];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join eventostags on tags.idTag = eventostags.idTag WHERE idEvento = '$idEvento';") 
                or die(mysqli_error($conn));

                while ($auxTags = mysqli_fetch_assoc($qTags)) {
                    $tags [$cont] = $auxTags;
                    $tags[$cont]['id'] = $auxTags['idTag'];
                    $tags[$cont]['name'] = $auxTags['nombreTag'];
                    $cont++;
                }
                // Guardar el resultado
                $eventos[$contEventos]['tags'] = $tags;

                $contEventos++;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS EVENTOS';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'eventos'=>$eventos));
?>