<?php
    require('../../base.php');
    $idRemitente = ''; if (isset($_POST['idRemitente'])) { $idRemitente = $_POST['idRemitente']; }
    $chats = [];

    $status = '';
    $result = '';

    if ($conn) {
        $qChatsRemitente = mysqli_query($conn, "SELECT * FROM mensajes WHERE idRemitente = '$idRemitente'") or die(mysqli_error($conn));
        $qChatsDestino = mysqli_query($conn, "SELECT * FROM mensajes WHERE idDestino = '$idRemitente'") or die(mysqli_error($conn));
        
        if ($qChatsRemitente || $qChatsDestino) {
            while($aux = mysqli_fetch_assoc($qChatsRemitente)) {

                $idOtro = $aux['idDestino'];
                $qNombreDestino = mysqli_query($conn, "SELECT nickname FROM personalizacion WHERE idUsuario = '$idOtro'") or die(mysqli_error($conn));
                $nombreDestino = mysqli_fetch_assoc($qNombreDestino);
                $aux['nombreOtro'] = $nombreDestino['nickname'];
                
                $chats[] = $aux;
            }
            while($aux = mysqli_fetch_assoc($qChatsDestino)) {

                $idOtro = $aux['idRemitente'];
                $qNombreDestino = mysqli_query($conn, "SELECT nickname FROM personalizacion WHERE idUsuario = '$idOtro'") or die(mysqli_error($conn));
                $nombreDestino = mysqli_fetch_assoc($qNombreDestino);
                $aux['nombreOtro'] = $nombreDestino['nickname'];

                $chats[] = $aux;
            }
            $status = 'OK';
            $result = 'CHATS ENCONTRADOS';
        } else {
            $status = 'BAD';
            $result = 'ERROR QUERY';
        }
        
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'chats'=>$chats));
?>