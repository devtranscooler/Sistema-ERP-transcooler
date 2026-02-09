<?php
require '../system/connection.php';
require '../system/constants.php';

$nombre=$_FILES['documento']['name'];
$guardado=$_FILES['documento']['tmp_name'];

$db = new MySQL();

// Guardamos Archivo
$aleatorio = date("y.m.d") . mt_rand(100, 999999);

//Si se quiere subir una imagen
//Recogemos el archivo enviado por el formulario
$archivo = $_FILES['documento']['name'];
//Si el archivo contiene algo y es diferente de vacio
if (isset($archivo) && $archivo != "") {
    //Obtenemos algunos datos necesarios sobre el archivo
    $tipo = $_FILES['documento']['type'];
    $tamano = $_FILES['documento']['size'];
    $temp = $_FILES['documento']['tmp_name'];
    
    $ext = pathinfo($archivo, PATHINFO_EXTENSION);
    $ruta = "../uploads/".$aleatorio.".".$ext;
    //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
    if (!((strpos($tipo, "csv")) && ($tamano < 2000000))) {
        echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
        - Se permiten archivos .csv de 200 kb como máximo.</b></div>';
        die();
    }
    else {
        //Si la imagen es correcta en tamaño y tipo
        //Se intenta subir al servidor
        if (move_uploaded_file($temp, $ruta)) {
            //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
            chmod($ruta, 0777);
            //Mostramos el mensaje de que se ha subido co éxito
            echo '<div><b>Se ha subido correctamente el archivo.</b></div>';

            // Guardamos la imagen
                $q="";

                $q=$q." insert into uploads ";
                $q=$q." 	(nombreUsuario ";
                $q=$q." 	,nombreSistema ";
                $q=$q." 	,idUsuarioAlta ";
                $q=$q." 	,fecAlta) ";
                $q=$q." values ";
                $q=$q." 	('$archivo' ";
                $q=$q." 	,'".$aleatorio.".".$ext."'";
                $q=$q." 	,".$id_usuario;
                $q=$q." 	,NOW()) ";

                $db->consulta($q);

                // Leemos el archivo que acabamos guardar.

                $file = fopen($ruta,"r");

                //Inicializamos el insert 
                /*
                $q = "";
                $q=$q." INSERT INTO usuarios"; 
                $q=$q."     (nombre,";
                $q=$q."     apellidoP,";
                $q=$q."     apellidoM,"; 
                $q=$q."     idRol,";
                $q=$q."     email,";
                $q=$q."     fecNac,";
                $q=$q."     noEmpleado,";
                $q=$q."     movil,";
                $q=$q."     foto,";
                $q=$q."     fecContratacion,";
                $q=$q."     diasVacaciones,";
                $q=$q."     diasVacDisfrutados,";
                $q=$q."     estatus,";
                $q=$q."     puesto,";
                $q=$q."     area,";
                $q=$q."     cedis,";
                $q=$q."     telefono,";
                $q=$q."     jefeInmediato)";

                $q=$q." VALUES"; 

                */
                // Validar si existe o no

                // $contar ++
                // Aqui concatenamod la query y la ejecutamos

                while (($csv_row = fgetcsv($file)) !== false) {
                // print the line content
                    foreach ($csv_row as $csv_cell) {
                            echo $csv_cell;
                    }
                    echo "<br/>";
                }

                
                //print_r(fgetcsv($file));
                
                
                


                fclose($file);

        }
        else {
        //Si no se ha podido subir la imagen, mostramos un mensaje de error
        echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
        }
    }
}

/*/ Guardamos info
$q="";

$q=$q." insert into solicitudes ";
$q=$q." 	(idUsuario ";
$q=$q." 	,idCliente ";
$q=$q." 	,nombreCliente ";
$q=$q." 	,email ";
$q=$q." 	,archivo ";
$q=$q." 	,material ";
$q=$q." 	,mm ";
$q=$q." 	,base ";
$q=$q." 	,altura) ";
$q=$q." values ";
$q=$q." 	('$id_usuario' ";
$q=$q." 	, 1 ";
$q=$q." 	,'$nombreCliente' ";
$q=$q." 	,'$email' ";
$q=$q." 	,'".$aleatorio.".".$ext."' ";
$q=$q." 	,'$nombreMaterial' ";
$q=$q." 	,'$mm' ";
$q=$q." 	,'$base' ";
$q=$q." 	,'$altura'); ";

echo $q;

$db->consulta($q);

*/

//header ('Location: ../index.php');

?>