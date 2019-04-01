<?php
    require('../../base.php');
    $eventos = [];

    if ($conn) {
        $qEvento = mysqli_query($conn, "SELECT * FROM getEventData") or die(mysqli_error($conn));
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