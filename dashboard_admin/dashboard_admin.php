<?php
session_start();

// Verificación de acceso
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin'){
    header("Location: ../login/login.html");
    exit;
}

$nombre = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Administrador - Electro Wizard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-gray-100">

  <!-- 🔷 Navbar -->
  <header class="bg-[#0b1c35] text-white flex justify-between items-center px-6 py-4 shadow-md">
    <a href="../landing_page/index.php" class="flex items-center space-x-2 hover:scale-105 transition-transform duration-200">
      <h1 class="text-2xl font-bold text-[#00e0ff] cursor-pointer">⚡ Electro Wizard</h1>
    </a>
    <span>Administrador</span>
  </header>

  <!-- Contenedor principal -->
  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-[#0b1c35] text-white w-64 p-6">
      <nav class="space-y-6">
        <a href="dashboard_admin.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">🏠</span>
          <span class="font-medium">Inicio</span>
        </a>

        <a href="dashboard_admin.php?view=agregar_producto" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">➕</span>
          <span class="font-medium">Agregar Productos</span>
        </a>

        <a href="dashboard_admin.php?view=agregar_admin" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">👑</span>
          <span class="font-medium">Agregar Administrador</span>
        </a>

        <a href="dashboard_admin.php?view=ver_usuarios" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">👥</span>
          <span class="font-medium">Lista de Usuarios</span>
        </a>

        <a href="dashboard_admin.php?view=ver_productos_admin" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">📦</span>
          <span class="font-medium">Productos Publicados</span>
        </a>

        <a href="../login/login.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-red-600 transition">
          <span class="text-xl">🔓</span>
          <span class="font-medium">Cerrar sesión</span>
        </a>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 p-10">

      <?php 
      if(!isset($_GET['view'])) { 
      ?>
      <!-- 🟢 Pantalla de inicio por defecto -->
      <h2 class="text-3xl font-bold text-[#0b1c35] mb-3">Bienvenido, <?php echo htmlspecialchars($nombre); ?> 👋</h2>
      <p class="text-lg text-gray-700 mb-8">Desde este panel puedes administrar todo el sistema de Electro Wizard.</p>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Tarjeta: Agregar Producto -->
        <a href="dashboard_admin.php?view=agregar_producto" 
           class="bg-white border rounded-xl p-6 shadow hover:shadow-xl transition hover:-translate-y-1">
          <div class="text-4xl mb-3">➕</div>
          <h3 class="text-xl font-semibold text-[#0b1c35] mb-2">Agregar Productos</h3>
          <p class="text-gray-600">Añade nuevos productos al catálogo y gestiona su información.</p>
        </a>

        <!-- Tarjeta: Ver Productos -->
        <a href="dashboard_admin.php?view=ver_productos_admin" 
           class="bg-white border rounded-xl p-6 shadow hover:shadow-xl transition hover:-translate-y-1">
          <div class="text-4xl mb-3">📦</div>
          <h3 class="text-xl font-semibold text-[#0b1c35] mb-2">Ver Productos Publicados</h3>
          <p class="text-gray-600">Consulta, edita o elimina los productos existentes en la tienda.</p>
        </a>

        <!-- Tarjeta: Agregar Administrador -->
        <a href="dashboard_admin.php?view=agregar_admin" 
           class="bg-white border rounded-xl p-6 shadow hover:shadow-xl transition hover:-translate-y-1">
          <div class="text-4xl mb-3">👑</div>
          <h3 class="text-xl font-semibold text-[#0b1c35] mb-2">Agregar Administrador</h3>
          <p class="text-gray-600">Crea nuevas cuentas con permisos de administración.</p>
        </a>

        <!-- Tarjeta: Ver Usuarios -->
        <a href="dashboard_admin.php?view=ver_usuarios" 
           class="bg-white border rounded-xl p-6 shadow hover:shadow-xl transition hover:-translate-y-1">
          <div class="text-4xl mb-3">👥</div>
          <h3 class="text-xl font-semibold text-[#0b1c35] mb-2">Lista de Usuarios</h3>
          <p class="text-gray-600">Revisa todos los usuarios registrados y su información.</p>
        </a>
      </div>

      <?php 
      } else {
          $vista = $_GET['view'];
          $path = __DIR__ . "/vistas/".$vista.".php";

          if(file_exists($path)){
              include $path;
          } else {
              echo "<h2 class='text-2xl font-bold text-red-600'>Vista no encontrada</h2>";
          }
      }
      ?>

    </main>
  </div>

</body>
</html>
