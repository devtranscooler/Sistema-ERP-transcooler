<?php
require '../../system/connection.php';

// Get form data
$nombrecompleto = $_POST['nombrecompleto'] ?? '';
$email_contacto = $_POST['email_contacto'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$celular = $_POST['celular'] ?? '';
$id_cliente = $_POST['id_cliente'] ?? '';

$db = new MySQL();
$conn = $db->getConexion();
// Insert into database
        $q = "";
        $q=$q." INSERT INTO contactos"; 
        $q=$q."     (nombrecompleto,";
        $q=$q."     email_contacto,";
        $q=$q."     telefono,";
        $q=$q."     celular,";
        $q=$q."     id_cliente)";

        $q=$q." VALUES"; 
        $q=$q."('$nombrecompleto',";
        $q=$q."'$email_contacto',";
        $q=$q."'$telefono',";
        $q=$q."'$celular',";
        $q=$q."'$id_cliente')";

if ($conn->query($q) === TRUE) {
    echo "Contacto agregado con éxito.";
} else {
    echo "Error: " . $q . "<br>" . $conn->error;
}

$conn->close();
?>