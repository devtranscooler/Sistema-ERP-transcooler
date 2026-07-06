<?php
require '../../system/connection.php';
//require '../../system/constants.php';

$idUsuario=$_POST['ID_USUARIO'];
$password=$_POST['PASSWORD'];

$db = new MySQL();

$q="";
$q=$q." update usuarios ";
$q=$q." set password=aes_encrypt('$password','SKYISTHELIMIT') ";
$q=$q." where id=$idUsuario";

$db->consulta($q);

//echo $q;
//die();

header ('Location: ../index.php');

?>