<?php
require '../system/connection.php';
require '../system/constants.php';

$db = new MySQL();

//* ========== PASO 1: Obtenemos el parámetro 'page' de la URL ==========
$currentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;

//* ========== PASO 2: Obtenemos el ID del usuario actual ==========
$id_usuario = intval($_SESSION['ID_USUARIO'] ?? 0);

// Si no hay usuario mostrar error
if ($id_usuario === 0) {
    die("Error: Usuario no autenticado");
}

//* ========== PASO 3: obtener los tabs que el usuario tiene permiso ==========
$queryTabs = "SELECT m.id_menu, m.nombre, m.url, m.status, m.tab, m.orden
    FROM menu_usuarios mu
        INNER JOIN menu m ON mu.id_menu = m.id_menu
            WHERE mu.id_usuario = $id_usuario
                AND m.status = 'activo'
                AND m.tab = 1
                AND m.id_parent = 5
    ORDER BY m.orden
";

$resultTabs = $db->consulta($queryTabs);

//* ========== PASO 4: Verificamos si la query fue exitosa ==========
if (!$resultTabs) {
    die("Error en la consulta");
}

//? tabs que el usuario puede ver
$tabs = [];
while ($row = $resultTabs->fetch_assoc()) {
    $tabs[] = $row;
}

// Si el usuario no tiene tabs asignados
if (count($tabs) === 0) {
    die("No tienes permisos para acceder a esta sección. Contacta al administrador.");
}

//* ========== PASO 5: Si no hay 'page' en la URL, asignamos el primer tab por defecto ==========
if ($currentPage === null && count($tabs) > 0) {
    $currentPage = $tabs[0]['nombre'];
}
?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>
    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function closeMenu(event) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('open') && !sidebar.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        }
    </script>
</head>

<body onclick="closeMenu(event)">

    <?php require_once '../utilities/sidebar.php'; Sidebar::render("Workflow"); ?>

    <div class="content p-3">
        <h1>Workflow</h1>

        <ul class="nav nav-pills nav-fill rounded-2" style="overflow: hidden;" >
            <?php
            foreach ($tabs as $tab):
                $isActive = ($currentPage === $tab['nombre']) ? 'active' : 'bg-dark-subtle bg-opacity-75 text-dark-emphasis';
            ?>
                <!-- SOLO se muestran los tabs que el usuario tiene asignados en menu_usuarios -->
                <li class="nav-item">
                    <a class="nav-link pb-2 rounded-0 <?= $isActive ?>"
                        href="?page=<?= htmlspecialchars($tab['nombre']) ?>">
                        <?= htmlspecialchars($tab['nombre']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- ========== CONTENEDOR DINÁMICO  ========== -->
        <?php
            foreach ($tabs as $tab) {
                if ($currentPage === $tab['nombre']) {
                    require $_SERVER['DOCUMENT_ROOT'] . $tab['url'];
                    break;
                }
            }
        ?>
    </div>

</body>

</html>