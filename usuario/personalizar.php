<?php
    require('../base.php');
    $idUsuario = NULL;
    $tags = NULL;
    $descripcion = NULL;
    $imagen = NULL;
    $nickname = NULL;

    $state = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if (isset($_POST['tags'])) {
        $tags = $_POST['tags'];
    }

    if (isset($_POST['nickname'])) {
        $nickname = $_POST['nickname'];
    }

    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
    }

    if (isset($_FILES['file']['name'])) {
        $imagen = $_FILES['file']['name'];
    }

    if ($conn) {
        // Registrar la imagen
        if ($imagen) {
            $ext = pathinfo($imagen, PATHINFO_EXTENSION);
            $nombreFichero = $idUsuario . '.' . $ext;
            $ruta = '../../media/usuarios/' . $nombreFichero;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ruta)) {
                $qUpdateImg = mysqli_query($conn, "UPDATE personalizacion SET rutaImagen = '$nombreFichero' WHERE idUsuario = '$idUsuario'");
                if ($qUpdateImg) {
                    $result .= ' SE REGISTRO LA IMAGEN ';    
                } else {
                    $result .= ' NO SE PUDO REGISTRAR LA IMAGEN ';
                }
                $result .= ' SE SUBIO LA IMAGE ';
            } else {
                $result .= ' NO SE PUDO SUBIR LA IMAGEN ';
            }
        }

        if ($descripcion) {
            $qUpdateDesc = mysqli_query($conn, "UPDATE personalizacion SET descripcion = '$descripcion' WHERE idUsuario = '$idUsuario'");
            if ($qUpdateDesc) {
                $result .= ' SE CAMBIO LA DESCRIPCION ';    
            } else {
                $result .= ' NO SE PUDO CAMBIAR LA DESCRIPCION ';
            }
        }

        if ($nickname) {
            $qUpdateNick = mysqli_query($conn, "UPDATE personalizacion SET nickname = '$nickname' WHERE idUsuario = '$idUsuario'");
            if ($qUpdateNick) {
                $result .= ' SE CAMBIO EL NICKNAME ';    
            } else {
                $result .= ' NO SE PUDO CAMBIAERL EL NICKNAME ';
            }
        }
        $state = 'OK';
        //PENDIENTE TAGS
    } else {
        $state = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$state, 'response'=>$result));
?>