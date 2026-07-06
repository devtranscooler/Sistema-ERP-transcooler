<?php
require 'system/connection.php';

$name=$_REQUEST['USERNAME'];
$pass=$_REQUEST['PASSWORD'];

if (empty($name) || empty($pass)) {
    // Set the HTTP Location header to the target page
    header('Location: https://test.transcoolermexico.com/index.php');
    
    // Crucial: Stop the script from running any further
    exit(); 
}

$db = new MySQL();

$q="";
$q=$q." SELECT id, ";
$q=$q."         CONCAT(nombre,' ',apellidoP,' ',apellidoM) as name "; 
//$q=$q."         idTipoUsuario ";
//$q=$q."         ,id_sucursal ";
$q=$q."         FROM usuarios ";
$q=$q." WHERE email='$name'";
//$q=$q." and estatus = 'ACTIVO'";

//echo $q;
//die();

$rs = $db->consulta($q);


if($db->num_rows($rs)==0){
    header ("Location: index.php?error=El usuario no existe.");
}else{

    $q2="call p_check_user('$name','$pass')";

    $rs2 = $db->consulta($q2);
    $fields2 = $db->fetch_array($rs2);

    if($fields2['exist']==1){
            session_start();

            $fields = $db->fetch_array($rs);

            //Asignamos las variables de sesion en el archivo de constantes.
            $_SESSION['ID_USUARIO']=$fields['id'];
            $_SESSION['NAME']=$fields['name'];
            $_SESSION['ID_TIPO_USUARIO']=$fields['idTipoUsuario'];
            $_SESSION['ID_SUCURSAL']=$fields['id_sucursal'];

            header ('Location: main.php');
    }else{
            header ("Location: index.php?error=La contraseña es incorrecta. Asegurate de usar la contraseña correcta.");
    }
}

?>
