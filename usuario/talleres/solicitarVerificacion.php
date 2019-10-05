<?php
    require('../../base.php');
    $idTaller = ''; if (isset($_POST['idTaller'])) { $idTaller = $_POST['idTaller']; }
    $nombre = ''; if (isset($_POST['nombre'])) { $nombre = $_POST['nombre']; }
    $rol = ''; if (isset($_POST['rol'])) { $rol = $_POST['rol']; }
    $ubicacion = ''; if (isset($_POST['ubicacion'])) { $ubicacion = $_POST['ubicacion']; }
    $domicilio = ''; if (isset($_POST['domicilio'])) { $domicilio = $_POST['domicilio']; }
    $imagenes = $_FILES;
    $auxID = '';

    if ($conn) {
        $idTaller = str_replace('"', '', $idTaller);
        $nombre = str_replace('"', '', $nombre);
        $rol = str_replace('"', '', $rol);
        $ubicacion = str_replace('"', '', $ubicacion);
        $domicilio = str_replace('"', '', $domicilio);

        $cantImagenes = sizeof($imagenes);
        $qTaller = mysqli_query($conn, "INSERT INTO verificaciones (idTaller, nombrePropietario, rolTaller, ubicacionTaller, domicilioTaller, cantImages)
        VALUES ('$idTaller', '$nombre', '$rol', '$ubicacion', '$domicilio', '$cantImagenes')") or die(showError(mysqli_error($conn)));
        if ($qTaller) {
            $idVerificacion = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idVerificacion = mysqli_fetch_assoc($idVerificacion);
            $idVerificacion = $idVerificacion['LAST_INSERT_ID()'];
            $auxID = $idVerificacion;

            guardarImagenes ($idVerificacion);
            $status = 'OK';
            $result = 'SE SOLICITO LA VERIFICACION';
        } else {
            $status = 'BAD';
            $result = 'ERROR EN EL QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'idVerificacion'=>$auxID));

    function showError ($error) {
        error_log($error, 3, "ERROR.log");
    }

    function guardarImagenes ($idVerificacion) {
        $cont = 0;
        foreach($_FILES as $aux) {
            $ext = 'jpg';
            $nombreFichero = $cont++  . '.' . $ext;
            $ruta = '../../../media/verificaciones/'.$idVerificacion;
            if(!is_dir($ruta))
                mkdir($ruta, 0777, true);
            $ruta .= '/' . $nombreFichero;
            move_uploaded_file($aux['tmp_name'], $ruta);
        }
    }
?>