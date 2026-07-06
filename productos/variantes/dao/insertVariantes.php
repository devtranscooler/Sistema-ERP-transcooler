<?php
require '../../../system/connection.php';

// Get form data
$nombrev = $_POST['nombrev'] ?? '';
$idproducto = $_POST['idproducto'] ?? '';
$minutos = $_POST['minutos'] ?? '';
$tolerancia = $_POST['tolerancia'] ?? '';
$recurrencia = $_POST['recurrencia'] ?? '';


$db = new MySQL();
$conn = $db->getConexion();
// Insert into database
        $q = "";
        $q=$q." INSERT INTO variantes"; 
        $q=$q."     (nombre,";
        $q=$q."     id_productos,";
        $q=$q."     minutos,";
        $q=$q."     tolerancia,";
        $q=$q."     recurrencia)";

        $q=$q." VALUES"; 
        $q=$q."('$nombrev',";
        $q=$q."'$idproducto',";
        $q=$q."'$minutos',";
        $q=$q."'$tolerancia',";
        $q=$q."'$recurrencia')";

if ($conn->query($q) === TRUE) {
    echo "Variante agregada con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>