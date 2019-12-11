<?php
    require('../../base.php');
    $talleres = [];

    if ($conn) {
        $qTaller = mysqli_query($conn, "SELECT * FROM ( (SELECT * FROM gettalleresdata ORDER BY idEstablecimiento DESC LIMIT 5) AS SUB ) ORDER BY idEstablecimiento ASC") or die(mysqli_error($conn));
        if ($qTaller) {
            $contTalleres = 0;
            while ($auxTaller = mysqli_fetch_assoc($qTaller)) {
                $talleres[$contTalleres] = $auxTaller;
                $idEstablecimiento = $talleres[$contTalleres]['idEstablecimiento'];

                //Obtener los tags de cada publicacion
                $cont = 0;
                $tags = [];
                $qTags = mysqli_query($conn, "SELECT * FROM tags inner join establecimientostags on tags.idTag = establecimientostags.idTag WHERE idEstablecimiento = '$idEstablecimiento';") 
                or die(mysqli_error($conn));

                while ($auxTags = mysqli_fetch_assoc($qTags)) {
                    $tags [$cont] = $auxTags;
                    $tags[$cont]['id'] = $auxTags['idTag'];
                    $tags[$cont]['name'] = $auxTags['nombreTag'];
                    $cont++;
                }
                // Guardar el resultado
                $talleres[$contTalleres]['tags'] = $tags;

                $contTalleres++;
            }
            $status = 'OK';
            $result = 'SE OBTUVIERON LOS ESTABLECIMIENTOS';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'talleres'=>$talleres));
?>