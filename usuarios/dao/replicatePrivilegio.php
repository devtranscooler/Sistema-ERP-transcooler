<?php
require '../../system/connection.php';
require '../../system/constants.php';

$idParent=$_POST['ID_PARENT'];
$idMenu=$_POST['ID_MENU'];
$isChecked=$_POST['CHECKED']=='true' ? true : false;
$idUsuario=$_POST['ID_USUARIO'];

$db = new MySQL();

function checkParent($id){
	
	global $idMenu,$isChecked,$idUsuario,$id_usuario,$db;
	
	$s="select id_parent from menu where id_menu = $id and status = 'ACTIVO'";
	$rs = $db->consulta($s);

    // echo $s;
    // die();
	 
	if($isChecked){
		 while($fields = $db->fetch_array($rs)){
			$q="";
			$q=$q." INSERT INTO menu_usuarios ";
			$q=$q." 	(id_menu ";
			$q=$q." 	,id_usuario ";
			$q=$q." 	,fec_alta ";
			$q=$q." 	,usuario_alta) ";
			$q=$q."	SELECT id_menu ";
			$q=$q."		,$idUsuario ";
			$q=$q."		,NOW() ";
			$q=$q."		,$id_usuario ";
			$q=$q."	FROM menu ";
			$q=$q."	WHERE id_menu = ".$fields['id_parent'];
			$q=$q."	AND NOT EXISTS (SELECT id_menu_usuario ";
			$q=$q."						FROM menu_usuarios ";
			$q=$q."						WHERE id_menu = ".$fields['id_parent'];
			$q=$q."						AND id_usuario = $idUsuario ";
			$q=$q."						limit 0,1)";
			
			$db->consulta($q);
			
			checkParent($fields['id_parent']);
		 }
	 }
	 else
	 {
		 while($fields = $db->fetch_array($rs)){
			 $q="DELETE FROM menu_usuarios WHERE id_menu = ".$fields['id_parent']." AND id_usuario = $idUsuario "; 
			 
			 $db->consulta($q);
			
			checkParent($fields['id_parent']);
		 }
	 }
}


 function checkChild($id){
	 
	 
	 global $isChecked,$db,$idUsuario,$id_usuario;
	 
	 $s="select id_menu from menu where id_parent = $id and status = 'ACTIVO'";
	 $rs = $db->consulta($s);
	 
	 if($isChecked){
		 while($fields = $db->fetch_array($rs)){
			$q="";
			$q=$q." INSERT INTO menu_usuarios ";
			$q=$q." 	(id_menu ";
			$q=$q." 	,id_usuario ";
			$q=$q." 	,fec_alta ";
			$q=$q." 	,usuario_alta) ";
			$q=$q."	SELECT id_menu ";
			$q=$q."		,$idUsuario ";
			$q=$q."		,NOW() ";
			$q=$q."		,$id_usuario ";
			$q=$q."	FROM menu ";
			$q=$q."	WHERE id_menu = ".$fields['id_menu'];
			$q=$q."	AND NOT EXISTS (SELECT id_menu_usuario ";
			$q=$q."						FROM menu_usuarios ";
			$q=$q."						WHERE id_menu = ".$fields['id_menu'];
			$q=$q."						AND id_usuario = $idUsuario ";
			$q=$q."						limit 0,1)";
			
			$db->consulta($q);
			
			checkChild($fields['id_menu']);
		 }
	 }
	 else
	 {
		 while($fields = $db->fetch_array($rs)){
			 $q="DELETE FROM menu_usuarios WHERE id_menu = ".$fields['id_menu']." AND id_usuario = $idUsuario "; 
			 
			 $db->consulta($q);
			
			checkChild($fields['id_menu']);
		 }
	 }
		 
 }

//checkParent();


if ($isChecked){
	$q="INSERT INTO menu_usuarios ";
	$q=$q."(id_menu, ";
	$q=$q."id_usuario, ";
	$q=$q."fec_alta, ";
	$q=$q."usuario_alta) ";
	$q=$q."	SELECT id_menu ";
	$q=$q."		,$idUsuario ";
	$q=$q."		,NOW() ";
	$q=$q."		,$id_usuario ";
	$q=$q."	FROM menu ";
	$q=$q."	WHERE id_menu = $idMenu ";
	$q=$q."	AND NOT EXISTS (SELECT id_menu_usuario ";
	$q=$q."						FROM menu_usuarios ";
	$q=$q."						WHERE id_menu = $idMenu ";
	$q=$q."						AND id_usuario = $idUsuario ";
	$q=$q."						limit 0,1)";
}
else{
	$q="DELETE FROM menu_usuarios WHERE id_menu = $idMenu AND id_usuario = $idUsuario ";
}
$db->consulta($q);

checkChild($idMenu);
checkParent($idMenu);

?>