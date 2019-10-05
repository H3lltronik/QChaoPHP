<?php
    require('../../base.php');
    $idUsuario = ''; if (isset($_POST['idUsuario'])) { $idUsuario = $_POST['idUsuario']; }
    $dias = ''; if (isset($_POST['dias'])) { $dias = $_POST['dias']; }
    $timestamp = ''; if (isset($_POST['timestamp'])) { $timestamp = $_POST['timestamp']; } // La hora del desbloqueo
    $finalStamp = '';
    $reporte = [];

    if ($conn) {
        // Checar si existe bloqueo
        $qExiste = mysqli_query($conn, "SELECT * FROM bloqueos WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
        // Si no estaba bloqueado
        if (mysqli_num_rows($qExiste) == 0) {
            $qBloqueo = mysqli_query($conn, "INSERT INTO bloqueos (idUsuario, timestampDesbloqueo, dias) VALUES 
            ('$idUsuario', '$timestamp', '$dias')") or die(mysqli_error($conn));
            if ($qBloqueo) {
                $status = 'OK';
                $result = 'SE BLOQUEO AL USUARIO';
                cerrarSesiones ($idUsuario, $conn);
            } else {
                $status = 'BAD';
                $result = 'ERROR EN EL QUERY';
            }
        } else {
            $status = 'ACTUALIZACION';
            $result = 'EL USUARIO YA ESTA BLOQUEADO';
            // // Si ya tenia un bloqueo TODO ESTO ES DE CUANDO ME PIDIO QUE SE AUMENTARAN LOS DIAS DEL BLOQUEO
            // $auxQuery = mysqli_fetch_assoc($qExiste); // Obtener los dias ya bloqueado para sumarlos con los que se le van a poner
            // $diasExistente = $auxQuery['dias'];
            // $dias = $dias + $diasExistente; // y ps se suman
            // // Lo mismo de sumar se hace con el timestamp
            // $auxTimestamp = $auxQuery['timestampDesbloqueo']; // Conseguimos el timestamp de la base
            // $auxDate = new DateTime();
            // $auxDate->setTimestamp($auxTimestamp); // Creamos una date y le ponemos la fecha del timestamp de la base
            // $stringSum = ' + '.intval($diasExistente + 1).' days'; // No se porque sea asi, se escribe ' + n days' que es lo que se va a sumar
            // $resDate = new DateTime($auxDate->format("Y-m-d"). $stringSum); // Aca se suma con la cadena de atras
            // $resTimestamp = $resDate->getTimestamp(); // Obtenemos el timestamp resultado de la suma rara de arriba
            // $finalStamp = $resTimestamp; // fnialStamp es variable gloabl asi que esa la imprimimos

            // $qBloqueo = mysqli_query($conn, "UPDATE bloqueos SET dias = '$dias', timestampDesbloqueo = '$resTimestamp' WHERE idUsuario = '$idUsuario'") 
            // or die(mysqli_error($conn));
            // if ($qBloqueo) {
            //     $status = 'ACTUALIZACION';
            //     $result = 'SE ACTUALIZO EL BLOQUEO DEL USUARIO';
            //     cerrarSesiones ($idUsuario, $conn);
            // } else {
            //     $status = 'BAD';
            //     $result = 'ERROR EN EL QUERY';
            // }
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'timestamp'=>$finalStamp));

    function cerrarSesiones ($idUsuario, $dbConn) {
        $qCerrar = mysqli_query($dbConn, "DELETE FROM sesiones WHERE idUsuario = '$idUsuario'") or die(mysqli_error($dbConn));
        if ($qCerrar) {
        }
    }
?>