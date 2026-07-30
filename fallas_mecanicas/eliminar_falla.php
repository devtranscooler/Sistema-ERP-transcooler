<?php

require '../system/connection.php';

$db=
new MySQL();

$id=
intval(
$_GET['id']
);

$db->consulta("

UPDATE fallas

SET activo=0

WHERE id=$id

");

header(
'Location: reporte_fallas.php'
);

exit;