<?php 
require '../../system/connection.php';

$id_producto = $_POST['id'] ?? '';

if (!$id_producto) {
    echo "ID inválido.";
    exit;
}

$db = new MySQL();
$conn = $db->getConexion();

$q = "";
$q .= "UPDATE productos SET ";
$q .= "Status = 'eliminado' ";
$q .= "WHERE id = '$id_producto'";

if ($conn->query($q) === TRUE) {
    echo "El producto ha sido eliminado.";
} else {
    echo "Error al eliminar producto: " . $conn->error;
}

$conn->close();
?>