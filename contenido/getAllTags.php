<?php
    require('../base.php');
    $tags = [];

    if ($conn) {
        $qTags = mysqli_query($conn, "SELECT * FROM tags") or die(mysqli_error($conn));
        if ($qTags) {
            $cont = 0;
            while ($auxTag = mysqli_fetch_assoc($qTags)) {
                $tags[$cont]['id'] = $auxTag['idTag'];
                $tags[$cont]['name'] = $auxTag['nombreTag'];
                $cont++;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS TAGS';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'tags'=>$tags));
?>