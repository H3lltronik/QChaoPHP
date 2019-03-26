<?php
    require('../base.php');
    $username = '';
    $password = '';
    $email = '';
    $nickname = '';
    $ciudad = '';
    $tipoDeCuenta = '';

    $status = '';
    $result = '';

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }

    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }

    if (isset($_POST['nickname'])) {
        $nickname = $_POST['nickname'];
    }

    if (isset($_POST['ciudad'])) {
        $ciudad = $_POST['ciudad'];
    }

    if (isset($_POST['tipoDeCuenta'])) {
        $tipoDeCuenta = $_POST['tipoDeCuenta'];
    }

    // print ($username . $password . $email . $tipoDeCuenta);

    if ($conn) {
        $qCreate = mysqli_query($conn, "INSERT INTO usuario (Nombre, Correo, Pass, tipoDeCuenta) VALUES ('$username','$email', '$password', '$tipoDeCuenta');") or die(mysqli_error($conn));
        if ($qCreate) {
            $idUsuario = mysqli_query($conn, "SELECT LAST_INSERT_ID();");
            $idUsuario = mysqli_fetch_assoc($idUsuario);
            $idUsuario = $idUsuario['LAST_INSERT_ID()'];
            $qCreateP = mysqli_query($conn, "INSERT INTO personalizacion (idUsuario, Nickname) VALUES ('$idUsuario', '$nickname')");
            if ($qCreateP) {
                $status = 'OK';
                $result = 'SE HA CREADO CORRECTAMENTE';
            } else {
                $status = 'BAD';
                $result = 'ERROR AL CREAR PERSONALIZACION';
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR AL CREAR USUARIO';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result));
?>