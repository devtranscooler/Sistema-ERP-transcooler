<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/system/connection.php';


class Sidebar
{
    public static function render($pageTitle = "Título por defecto")
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = new MYSQL();

        $id_usuario = intval($_SESSION['ID_USUARIO'] ?? 0);
        $userName = htmlspecialchars($_SESSION['NAME'] ?? 'Invitado');

        $logout_patch = "../system/logout.php";

        // Consulta menú correctamente con usuario real
        $q = "
            SELECT 
                m.id_menu,
                m.id_parent,
                m.nombre,
                m.nivel,
                m.url
            FROM menu_usuarios mu
            INNER JOIN menu m ON mu.id_menu = m.id_menu
            WHERE mu.id_usuario = $id_usuario
              AND m.status = 'activo'
              AND m.tab = 0
            ORDER BY m.orden, m.id_menu
        ";

        $rs = $db->consulta($q);

        $menus = [];
        while ($row = $db->fetch_array($rs)) {
            $menus[] = $row;
        }

        // Agrupar por padre
        $menuTree = [];
        foreach ($menus as $item) {
            $menuTree[$item['id_parent']][] = $item;
        }
?>
        <title><?= htmlspecialchars($pageTitle) ?></title>

        <div class="topbar">
            <div class="d-flex align-items-center">
                <div class="logo-container">
                    <img src="/img/logo1.png" alt="Logo" class="logo">
                </div>
            </div>

            <div class="user-actions d-flex align-items-center gap-3">
                <button onclick="toggleTheme()" class="btn btn-sm btn-outline-secondary" style="border-radius: 20px;">
                    🌙 / ☀️
                </button>
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline"><?= $userName ?></span>
                </div>
            </div>
        </div>

        <div class="sidebar" id="sidebar" onclick="event.stopPropagation()">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <ul>
                <?php self::renderMenu(NULL, $menuTree); ?>
                <li class="has-submenu btn-logout" onclick="location.href='<?= $logout_patch ?>'">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="menu-text">Cerrar Sesión</span>
                </li>
            </ul>
        </div>
<?php
    }

    private static function renderMenu($parentId, $menuTree)
    {
        if (!isset($menuTree[$parentId])) return;

        foreach ($menuTree[$parentId] as $item) {

            if (isset($menuTree[$item['id_menu']])) {
?>
                <li class="has-submenu">
                    <?= htmlspecialchars($item['nombre']) ?>
                    <ul class="submenu">
                        <?php self::renderMenu($item['id_menu'], $menuTree); ?>
                    </ul>
                </li>
<?php
            } else {
?>
                <li onclick="location.href='<?= htmlspecialchars($item['url']) ?>'">
                    <?= htmlspecialchars($item['nombre']) ?>
                </li>
<?php
            }
        }
    }
}
?>