<?php
session_start();

// Verifica si el usuario es cliente
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente'){
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
  <title>Dashboard Cliente - Electro Wizard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-gray-100">

  <!-- Navbar -->
  <header class="bg-[#0b1c35] text-white flex justify-between items-center px-6 py-4 shadow-md">
    <!-- 🔗 Logo con enlace a la landing page -->
    <a href="../landing_page/index.php" class="flex items-center space-x-2 hover:scale-105 transition-transform duration-200">
      <h1 class="text-2xl font-bold text-[#00e0ff] cursor-pointer">⚡ Electro Wizard</h1>
    </a>
    <span>Cliente</span>
  </header>

  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-[#0b1c35] text-white w-64 p-6">
      <nav class="space-y-6">
        <a href="dashboard_cliente.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">🏠</span>
          <span class="font-medium">Inicio</span>
        </a>

        <a href="dashboard_cliente.php?view=carrito" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">🛒</span>
          <span class="font-medium">Mi Carrito</span>
        </a>

        <a href="dashboard_cliente.php?view=pedidos" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">📦</span>
          <span class="font-medium">Mis Pedidos</span>
        </a>

        <a href="dashboard_cliente.php?view=perfil" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-[#12274d] transition">
          <span class="text-xl">👤</span>
          <span class="font-medium">Mi Perfil</span>
        </a>

        <a href="../login/login.html" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-red-600 transition">
          <span class="text-xl">🔓</span>
          <span class="font-medium">Cerrar sesión</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">

      <?php 
      if(!isset($_GET['view'])) { 
        include "vistas/home_cliente.php";
      } else {
        $vista = $_GET['view'];
        $archivo = "vistas/" . $vista . ".php";
        if(file_exists($archivo)){
          include $archivo;
        } else {
          echo "<p class='text-center text-gray-600'>Vista no encontrada.</p>";
        }
      }
      ?>

    </main>
  </div>

</body>
</html>
