<?php
    require('../base.php');
    $idUsuario = '';
    $telefono = '';
    $ubicacion = '';
    $nickname = '';
    $descripcion = '';
    $imagen = '';

    $state = '';
    $result = '';

    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];
    }

    if (isset($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
    }

    if (isset($_POST['ubicacion'])) {
        $ubicacion = $_POST['ubicacion'];
    }

    if (isset($_POST['nickname'])) {
        $nickname = $_POST['nickname'];
    }

    if (isset($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
    }

    if (isset($_POST['imagen'])) {
        $imagen = $_POST['imagen'];
    }

    if ($conn) {
        // Crear carpeta de usuarios
        $ruta = '../../Profiles/';
        if(!is_dir($ruta)){
            $response .= 'Carpeta Profiles creada';
            mkdir($ruta);
        }
        // Crear carpeta de usuario
        if(!is_dir($ruta + $idUsuario)){
            $response .= 'Carpeta Usuario creada';
            mkdir($ruta + $idUsuario);
        }
        $qCreate = mysqli_query($conn, "INSERT INTO personalizacion (Telefono, Ubicacion, Nickname, Descripcion, rutaImagen)
        VALUES ('$telefono', '$ubicacion', '$nickname', '$descripcion', '$rutaImagen')");
    } else {
        $state = 'BAD';
        $result = 'SIN CONEXION';
    }
?>