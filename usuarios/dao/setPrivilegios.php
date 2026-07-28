<?php
require '../../system/connection.php';
require '../../system/constants.php';


$idUsuario=$_GET['idUsuario'];
$name=$_GET['name'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="../../css/styles.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="../../js/prototype.js"></script>
<script>

function checkAll(_isChecked,_idMenu,_idParent){
	doCheckAll(_isChecked,_idMenu,_idParent);
	// Si el hijo es clikeado, tambien el papa.
	if(_isChecked)
		doCheckParent(_isChecked,_idMenu,_idParent);
	// Ajax que guarda los privilegios.
	new Ajax.Request("./replicatePrivilegio.php", {
		method: 'post',
		parameters:'ID_PARENT='+_idParent+'&ID_MENU='+_idMenu+'&CHECKED='+_isChecked+'&ID_USUARIO='+<?php echo $idUsuario?>,
		onCreate: function(){
			$('privilegios').disable();
		},
		onSuccess: function(t){
			$('privilegios').enable();
			//alert(t.responseText);
		},
		onFailure: function(t){alert('Ocurrio un error:\n'+t.responseText);}
	});
}

function doCheckAll(_isChecked,_idMenu,_idParent){
	var chks=document.getElementsByTagName("input");
	for (var i=0; i < chks.length; i++){
		if( $(chks[i]).readAttribute("parent") == _idMenu){
			chks[i].checked=_isChecked;
			doCheckAll(_isChecked,$(chks[i]).readAttribute("self"));
		}
	}
}

function doCheckParent(_isChecked,_idMenu,_idParent){
	var parent=$("ID_MENU_"+_idParent);
	if ( parent != null ){
		parent.checked=_isChecked;
		doCheckParent(_isChecked,parent.readAttribute("self"),parent.readAttribute("parent"));
	} 
}
</script>
</head>

<body>
<form name="privilegios" id="privilegios" >
	<div style=" background-color: #80ced6;">M&oacute;dulos asignados a:&nbsp;<?php echo $name?>
	</div>
<table width="98%" border=0 align="center" cellspacing=2>
  <caption>
	
  </caption>
  <thead>
    <th>&nbsp;</th>
  </thead>
<?php
$db = new MySQL();

$q="";
$q=$q." select id_menu ";
$q=$q." 	,id_parent ";
$q=$q." 	,nombre ";
$q=$q." 	,case when id_menu in (select id_menu from menu_usuarios where id_usuario = ".$idUsuario.") ";
$q=$q." 	        then ";
$q=$q." 	            'checked' ";
$q=$q." 	        else ";
$q=$q." 	            '' ";
$q=$q." 	end as CHECKED ";
$q=$q." from menu ";
$q=$q." where nivel = 1 ";
$q=$q." and status='ACTIVO' ";
$q=$q." order by orden ";

$rs = $db->consulta($q);

if($db->num_rows($rs)>0){
	while($fields = $db->fetch_array($rs)){
?>
		<tr>
            <td class="tdLeft" >
            <input type="checkbox" id="ID_MENU_<?php echo $fields['id_menu']?>" name="ID_MENU" self="<?php echo $fields['id_menu']?>" parent="<?php echo $fields['id_parent']?>" onClick="checkAll(this.checked,$(this).readAttribute('self'),$(this).readAttribute('parent'));" <?php echo $fields['CHECKED']?> />
				<?php echo $fields['nombre']?>
            </td>
        </tr>
            	
<?php
		$q="";
		$q=$q." select id_menu ";
		$q=$q." 	,id_parent ";
		$q=$q." 	,nombre ";
		$q=$q." 	,case when id_menu in (select id_menu from menu_usuarios where id_usuario = ".$idUsuario.") ";
		$q=$q." 	        then ";
		$q=$q." 	            'checked' ";
		$q=$q." 	        else ";
		$q=$q." 	            '' ";
		$q=$q." 	end as CHECKED ";
		$q=$q." from menu ";
		$q=$q." where nivel = 2 ";
		$q=$q." and status='ACTIVO' ";
		$q=$q." and id_parent=".$fields['id_menu'];
		$q=$q." order by orden ";
		
		$rs2 = $db->consulta($q);
	
		if($db->num_rows($rs2)>0){
			while($fields2 = $db->fetch_array($rs2)){
?>
				<tr>
					<td class="Left" style="padding-left:27px;">
					<input type="checkbox" id="ID_MENU_<?php echo $fields2['id_menu']?>" name="ID_MENU" self="<?php echo $fields2['id_menu']?>" parent="<?php echo $fields2['id_parent']?>" onClick="checkAll(this.checked,$(this).readAttribute('self'),$(this).readAttribute('parent'));" <?php echo $fields2['CHECKED']?>/>
					<?php echo $fields2['nombre']?>
					</td>
				</tr>
<?php
				$q="";
				$q=$q." select id_menu ";
				$q=$q." 	,id_parent ";
				$q=$q." 	,nombre ";
				$q=$q." 	,case when id_menu in (select id_menu from menu_usuarios where id_usuario = ".$idUsuario.") ";
				$q=$q." 	        then ";
				$q=$q." 	            'checked' ";
				$q=$q." 	        else ";
				$q=$q." 	            '' ";
				$q=$q." 	end as CHECKED ";
				$q=$q." from menu ";
				$q=$q." where nivel = 3 ";
				$q=$q." and status='ACTIVO' ";
				$q=$q." and id_parent=".$fields2['id_menu'];
				$q=$q." order by orden ";
				
				$rs3 = $db->consulta($q);
			
				if($db->num_rows($rs3)>0){
					while($fields3 = $db->fetch_array($rs3)){
?>
                        <tr>
                            <td class="Left" style="padding-left:54px;">
                            <input type="checkbox" id="ID_MENU_<?php echo $fields3['id_menu']?>" name="ID_MENU" self="<?php echo $fields3['id_menu']?>" parent="<?php echo $fields3['id_parent']?>" onClick="checkAll(this.checked,$(this).readAttribute('self'),$(this).readAttribute('parent'));" <?php echo $fields3['CHECKED']?>/>
							<?php echo $fields3['nombre']?>
                            </td>
                        </tr>
<?php
					}
				}
			}
		}
	}
}
?>
  
</table>
</form>
</body>
</html>