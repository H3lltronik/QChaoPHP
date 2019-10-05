<?php
    header('Access-Control-Allow-Origin: *'); 
    $conn = mysqli_connect('localhost', 'root', '', 'qchaoweb');
    
    function generateId ($idLength) {
			$id = md5(time() . mt_rand(1,1000000));
			$id = substr($id, 0, $idLength);
			return $id;
		}
?>