<?php
    require('../../base.php');
    $idRemitente = ''; if (isset($_POST['idRemitente'])) { $idRemitente = $_POST['idRemitente']; }
    $idDestino = ''; if (isset($_POST['idDestino'])) { $idDestino = $_POST['idDestino']; }
    $idChat = generateId(10);

    $status = '';
    $result = '';

    if ($conn) {
        $qCreate = mysqli_query($conn, "INSERT INTO mensajes (idRemitente, idDestino, idChat) VALUES 
        ('$idRemitente', '$idDestino', '$idChat')") or die(mysqli_error($conn));
        
        if ($qCreate) {
            $status = 'OK';
            $result = 'CHAT CREADO';
        } else {
            $status = 'BAD';
            $result = 'ERROR QUERY';
        }
        
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'idChat'=>$idChat));
?>