<?php
session_start();
require '../../system/connection.php';

// Get form data
$nombreProducto = $_POST['nombreProducto'] ?? '';
$userId = $_SESSION['ID_USUARIO'] ?? null;
$date = date('Y-m-d');


$db = new MySQL();
$conn = $db->getConexion();
// Insert into database
        $q = "";
        $q=$q." INSERT INTO productos"; 
        $q=$q."     (descProducto,";
        $q=$q."     idusuarios,";
        $q=$q."     fecha)";

        $q=$q." VALUES"; 
        $q=$q."('$nombreProducto',";
        $q=$q."'$userId',";
        $q=$q."'$date')";

if ($conn->query($q) === TRUE) {
    echo "Tu Producto ha sido agregado de Forma exitosa!";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>