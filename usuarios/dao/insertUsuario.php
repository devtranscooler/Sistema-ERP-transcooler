<?php
require '../../system/connection.php';
$db = new MySQL();
$conn = $db->getConexion();
$mode = $_POST['mode'] ?? 'insert';

if ($mode === 'update') {
    include './updateUsuario.php';
    exit;
}

// Get form data
$nombre = !empty($_POST['nombre']) ? "'".$_POST['nombre']."'" : "NULL";
$apellidoP = !empty($_POST['apellidoP']) ? "'".$_POST['apellidoP']."'" : "NULL";
$apellidoM = !empty($_POST['apellidoM']) ? "'".$_POST['apellidoM']."'" : "NULL";
$idRol = !empty($_POST['idRol']) ? "'".$_POST['idRol']."'" : "NULL";
$email = !empty($_POST['email']) ? "'".$_POST['email']."'" : "NULL";
$fecNac = !empty($_POST['fecNac']) ? "'".$_POST['fecNac']."'" : "NULL";
$employeeNumber = !empty($_POST['employeeNumber']) ? "'".$_POST['employeeNumber']."'" : "NULL";
$movil = !empty($_POST['movil']) ? "'".$_POST['movil']."'" : "NULL";
$fecContratacion = !empty($_POST['fecContratacion']) ? "'".$_POST['fecContratacion']."'" : "NULL";
$diasVacaciones = !empty($_POST['diasVacaciones']) ? "'".$_POST['diasVacaciones']."'" : "NULL";
$diasVacDisfrutados = !empty($_POST['diasVacDisfrutados']) ? "'".$_POST['diasVacDisfrutados']."'" : "NULL";
$estatus = !empty($_POST['estatus']) ? "'".$_POST['estatus']."'" : "NULL";
$puesto = !empty($_POST['puesto']) ? "'".$_POST['puesto']."'" : "NULL";
$area = !empty($_POST['area']) ? "'".$_POST['area']."'" : "NULL";
$cedis = !empty($_POST['cedis']) ? "'".$_POST['cedis']."'" : "NULL";
$telefono = !empty($_POST['telefono']) ? "'".$_POST['telefono']."'" : "NULL";
$jefeInmediato = !empty($_POST['jefeInmediato']) ? "'".$_POST['jefeInmediato']."'" : "NULL";

//Archivo Foto
$uploadDir = '../../img/uploads/';
$foto = $_FILES['foto'];

$archivo = $foto['name'];
$nombreTemporal = $foto['tmp_name'];
if (isset($archivo) && $archivo != "") {
$tamano = $foto['size'];
$tipo = $foto['type'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);


$nombreSistema = date("y.m.d") . mt_rand(100, 999999) . "." . $ext;


if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
    echo '<div><b>Error. Solo se permiten archivos .gif, .jpg, .jpeg, .png menores a 2MB.</b></div>';
    exit;
}


$rutaFinal = $uploadDir . $nombreSistema;

    if (move_uploaded_file($nombreTemporal, $rutaFinal)) {
        chmod($rutaFinal, 0777); 

        echo '<div><b>Se ha subido correctamente la imagen.</b></div>';

        $query = "
            INSERT INTO uploads (
                nombreUsuario,
                nombreSistema,
                fecAlta
            ) VALUES (
                '$archivo',
                '$nombreSistema',
                NOW()
            )
        ";

        $db->consulta($query);

    } else {
        echo '<div><b>Error: No se pudo guardar la imagen en el servidor.</b></div>';
    }
}
$nombreSistema = isset($nombreSistema) ? "'$nombreSistema'" : "NULL";
// Insert into database
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
        $q=$q."($nombre,";
        $q=$q."$apellidoP,";
        $q=$q."$apellidoM,";
        $q=$q."$idRol,";
        $q=$q."$email,";
        $q=$q."$fecNac,";
        $q=$q."$employeeNumber,";
        $q=$q."$movil,";
        $q=$q."$nombreSistema,";
        $q=$q."$fecContratacion,";
        $q=$q."$diasVacaciones,";
        $q=$q."$diasVacDisfrutados,";
        $q=$q."$estatus,";
        $q=$q."$puesto,";
        $q=$q."$area,";
        $q=$q."$cedis,";
        $q=$q."$telefono,";
        $q=$q."$jefeInmediato)";

//echo $q;
//die();

if ($conn->query($q) === TRUE) {
    echo "Record added successfully!";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
header("Location: ../index.php"); 
exit();
?>