<?php

$db = new MySQL();

$id_usuario = intval($id_usuario ?? 0);

// Obtener cada opción de menú con id_parent para construir la o las jerarquías
$mysqli_conn = $db->getConexion(); // Obtener la conexión mysqli
$q = "
  SELECT 
    m.id_menu,
    m.id_parent,
    m.nombre,
    m.nivel,
    m.url
  FROM menu_usuarios mu
  INNER JOIN menu m ON mu.id_menu = m.id_menu
  WHERE mu.id_usuario = ?
    AND m.status = 'activo'
    AND m.tab = 0
  ORDER BY m.orden, m.id_menu
";
$stmt = $mysqli_conn->prepare($q);
if ($stmt === false) {
    die('MySQL prepare error: ' . $mysqli_conn->error);
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$rs = $stmt->get_result(); // Obtener el resultado para usar fetch_array

// Construir lista plana
$menus = [];
while ($row = $db->fetch_array($rs)) {
  $menus[] = $row;
}

// Agrupar por padre
$menuTree = [];
foreach ($menus as $item) {
  $menuTree[$item['id_parent']][] = $item;
}

class Sidebar
{
  public static function render($pageTitle = "Título por defecto")
  {

    $logout_patch = "../system/logout.php";

    global $menuTree;

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $userName = isset($_SESSION['NAME']) ? htmlspecialchars($_SESSION['NAME']) : 'Invitado';
    $id_usuario = intval(isset($_SESSION['ID_USUARIO']) ?? 0);
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
        <?php
        // Iniciar renderizado desde los nodos raíz (id_parent NULL)
        Sidebar::renderMenu(NULL, $menuTree);
        ?>
        <li class="has-submenu btn-logout" onclick="location.href='<?= $logout_patch ?>'">
          <i class="bi bi-box-arrow-right"></i>
          <span class="menu-text">Cerrar Sesión</span>
      </ul>

    </div>
    <?php
  }
  // Función recursiva para imprimir el menú
  public static function renderMenu($parentId, $menuTree)
  {
    if (!isset($menuTree[$parentId])) return;

    foreach ($menuTree[$parentId] as $item) {
      if (isset($menuTree[$item['id_menu']])) {
        // Es un padre con hijos
    ?>
        <li class="has-submenu">
          <?= $item['nombre'] ?>

          <ul class="submenu">
            <?php self::renderMenu($item['id_menu'], $menuTree); ?>
          </ul>
        </li>
      <?php
      } else {
        // Es un hijo o elemento sin hijos
      ?>
        <li class="has-submenu" onclick="location.href='<?= $item['url'] ?>'">
          <?= $item['nombre'] ?>
        </li>
<?php
      }
    }
  }
}
?>