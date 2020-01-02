<?php
    require('../base.php');
    $password = '';
    $nombre = '';
    $user = '';
    $browser = '';
    $token = '';
    $timestamp = 0;
    $bloqueado = false;

    $status = 'default';
    $result = 'default';

    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }

    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
    }

    if (isset($_POST["token"])) {
        $token = $_POST["token"];
    }

    if (isset($_POST["browser"])) {
        $browser = $_POST["browser"];
    }

    if ($conn) {
        $qBuscar = mysqli_query($conn, "SELECT * FROM getuserinfo WHERE Nombre = '$nombre' AND pass = '$password';") or die(mysqli_error($conn));
        if ($qBuscar) {
            $qResult = mysqli_fetch_assoc($qBuscar);
            $user = $qResult;
            if ($qResult['nombre'] === $nombre) {
                $idUsuario = $qResult['idUsuario'];
                // Checar primero que no este bloqueado este parse
                $qBloqueo = mysqli_query($conn, "SELECT * FROM bloqueos WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
                // Obtener el timestamp del bloqueo para comparar si sigue bloqueado 
                $timestamp = mysqli_fetch_assoc($qBloqueo);
                $timestamp = $timestamp['timestampDesbloqueo'];
                $auxTimestamp = $timestamp;
                $auxTimestamp = intval($auxTimestamp);
                // $auxTimestamp = ($auxTimestamp / 1000); // Porque son milisegundos, quien sabe porque

                $today = new DateTime();
                $expireDate = new DateTime(); // Se crea la fecha del bloqueo sin valor
                $expireDate->setTimestamp($auxTimestamp); // Se le asigan el valor del timestamp del bloqueo
                // Si ya paso el bloqueo
                    
                    // Si se genero el login entonces crear una sesion
                    $qSesion = mysqli_query($conn, "INSERT INTO sesiones (idUsuario, sesionToken, sesionInfo)
                    VALUES ('$idUsuario', '$token', '$browser')") 
                    or die(mysqli_error($conn));
                    if ($qSesion) {
                        $status = 'OK';
                        $result = 'SI EXISTE USUARIO';

                        if($today->format("Y-m-d") < $expireDate->format("Y-m-d")) {
                            $bloqueado = true;
                    
                            $status = 'BLOQUEADO';
                            $result = 'USUARIO BLOQUEADO';
                        } else {
                            // Como ya no esta bloqueado ps se borra el bloqueo de la tabla
                            $qDelBloqueo = mysqli_query($conn, "DELETE FROM bloqueos WHERE idUsuario = '$idUsuario'") or die(mysqli_error($conn));
                        }

                    } else {
                        $status = 'BAD';
                        $result = 'NO SE PUDO CREAR LA SESION';
                    }

                    // SI sigue bloqueado
                // else {
                //     $user = NULL;
                //     $bloqueado = true;
                    
                //     $status = 'BLOQUEADO';
                //     $result = 'USUARIO BLOQUEADO';
                // }

                
            } else {
                $status = 'BAD';
                $result = 'NO EXISTE USUARIO';
            }
        } else {
            $status = 'BAD';
            $result = 'ERROR AL HACER QUERY';
        }
    } else {
        $status = 'BAD';
        $result = 'SIN CONEXION';
    }

    echo json_encode(array('status'=>$status, 'response'=>$result, 'user'=>$user, 'timestamp'=>$timestamp, 'bloqueado'=>$bloqueado));
?>