<?php
// Get form data
$id = $_POST['id'] ?? null;
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

   
$foto = isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK ? "'".$_FILES['foto']['name']."'" : "NULL";
$contrato = isset($_FILES['contrato']) && $_FILES['contrato']['error'] == UPLOAD_ERR_OK ? "'".$_FILES['contrato']['name']."'" : "NULL";


$db = new MySQL();
$conn = $db->getConexion();

// Insert into database
$q = "";
$q  = "UPDATE usuarios SET ";
$q .= "nombre = $nombre, ";
$q .= "apellidoP = $apellidoP, ";
$q .= "apellidoM = $apellidoM, ";
$q .= "idRol = $idRol, ";
$q .= "email = $email, ";
$q .= "fecNac = $fecNac, ";
$q .= "noEmpleado = $employeeNumber, ";
$q .= "movil = $movil, ";
$q .= "foto = $foto, ";
$q .= "fecContratacion = $fecContratacion, ";
$q .= "diasVacaciones = $diasVacaciones, ";
$q .= "diasVacDisfrutados = $diasVacDisfrutados, ";
$q .= "estatus = $estatus, ";
$q .= "puesto = $puesto, ";
$q .= "area = $area, ";
$q .= "cedis = $cedis, ";
$q .= "contrato = $contrato, ";
$q .= "telefono = $telefono, ";
$q .= "jefeInmediato = $jefeInmediato ";
$q .= "WHERE id = $id";


if ($conn->query($q) === TRUE) {
    echo "Usuario Actualizado!";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}
$conn->close();
header("Location: ../index.php"); 
exit();
?>