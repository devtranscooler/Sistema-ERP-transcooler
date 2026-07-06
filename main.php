<?php
require './system/connection.php';
require './system/constants.php';
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/utilities/head.php'); ?>

    <title>Transcooler</title>

</head>
<body onclick="closeMenu(event)">
    <?php
   //Cambiar Ruta;
    require_once './utilities/sidebar.php'; 
    Sidebar::render();
    ?>

    <div class="container-fluid">
        <h1>Contenido Principal</h1>
    </div>

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
</body>
</html>
