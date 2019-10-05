<?php
header('Access-Control-Allow-Origin: *');

$status = '';
$response = '';

$ruta = '../recursos/imagenes/prueba/';
$ext = '';
$fileName = '';
$file = '';

if(isset($_POST['ruta'])){
    $ruta = $_POST['ruta'];
}
if(isset($_POST['fileName'])){
    $fileName = $_POST['fileName'];
}
if(isset($_FILES['file']['name'])){
    $file = $_FILES['file']['name'];
}

$ext = pathinfo($file, PATHINFO_EXTENSION);

// if(!is_dir($ruta)){
//     mkdir($ruta);
// }

$ruta = $ruta . "prueba.jpg";
$error = move_uploaded_file($_FILES['file']['tmp_name'], "xd.jpg");
if ($error) {
    $status = 'OK';
    $response = 'IMAGEN SUBIDA';
}else{
    $status = 'BAD';
    $response = 'IMAGEN NO SUBIDA';
}

?>