<?php
require '../../system/connection.php';
$db = new MySQL();
$conn = $db->getConexion();

$id_sucursal = $_GET['id_sucursal'] ?? null;
//Archivo Foto
$uploadDir = '../../img/uploads/';
$documento = $_FILES['documento'];

$archivo = $documento['name'];
$nombreTemporal = $documento['tmp_name'];
if (isset($archivo) && $archivo != "") {
$tamano = $documento['size'];
$tipo = $documento['type'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);

$nombreSistema = date("y.m.d") . mt_rand(100, 999999) . "." . $ext;

if (!((strpos($tipo, "pdf") || strpos($tipo, "docx")) && ($tamano < 2000000))) {
    echo '<div><b>Error. Solo se permiten archivos .pdf, .docx menores a 2MB.</b></div>';
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

$conn->close();
if ($id_sucursal) {
    header("Location: ../detail_unidad.php?id_sucursal=" . intval($id_sucursal));
    exit;
} else {
    // fallback redirect
    header("Location: ../index.php");
    exit;
}

?>