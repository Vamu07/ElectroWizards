<?php
session_start();
include __DIR__ . "/../config/conexion.php";

// 🔹 Cerrar sesión directamente desde la misma landing page
if (isset($_GET['logout'])) {
  $_SESSION = [];
  session_destroy();
  echo "<script>alert('👋 Sesión cerrada correctamente. ¡Hasta pronto!'); window.location.href='index.php';</script>";
  exit;
}

// Detectar sesión
$sesion_activa = isset($_SESSION['id_usuario']);
$nombre_usuario = $sesion_activa ? $_SESSION['nombre'] : null;
$rol_usuario = $sesion_activa ? $_SESSION['rol'] : null;
$id_usuario = $sesion_activa ? $_SESSION['id_usuario'] : null;

// 🛒 Agregar al carrito
if (isset($_GET['add_cart']) && $sesion_activa && $rol_usuario === 'cliente') {
  $id_producto = intval($_GET['add_cart']);
  
  // Verificar si ya existe en carrito
  $check = $conexion->prepare("SELECT cantidad FROM carrito WHERE id_usuario=? AND id_producto=?");
  $check->bind_param("ii", $id_usuario, $id_producto);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {
    $conexion->query("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = $id_usuario AND id_producto = $id_producto");
  } else {
    $conexion->query("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES ($id_usuario, $id_producto, 1)");
  }

  header("Location: index.php#productos");
  exit;
}

// 🔹 Productos aleatorios
$productos_destacados = [];
$query = $conexion->query("SELECT id_producto, nombre_producto, precio, imagen 
                           FROM productos 
                           ORDER BY RAND() 
                           LIMIT 4");
while($row = $query->fetch_assoc()) {
  $productos_destacados[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Electro Wizard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Animación de aparición desde abajo */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-up {
      opacity: 0;
      transform: translateY(40px);
      animation: fadeUp 1s ease forwards;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navbar -->
  <header class="bg-[#0b1c35] text-white shadow-md fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">

      <h1 class="text-2xl font-bold text-[#00e0ff] hover:scale-105 transition-transform duration-300 cursor-pointer">⚡ Electro Wizard</h1>

      <!-- Menú -->
      <nav class="space-x-6 text-white font-medium flex items-center fade-up">
        <a href="#" class="hover:text-[#00e0ff] transition duration-300 hover:underline">Inicio</a>
        <a href="#categorias" class="hover:text-[#00e0ff] transition duration-300 hover:underline">Categorías</a>
        <a href="#productos" class="hover:text-[#00e0ff] transition duration-300 hover:underline">Productos</a>
        <a href="#contacto" class="hover:text-[#00e0ff] transition duration-300 hover:underline">Contacto</a>

        <?php if (!$sesion_activa): ?>
          <a href="../login/login.html" class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300">Iniciar Sesión</a>
          <a href="../login/registro.html" class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300">Registrar Cuenta</a>
        <?php else: ?>
          <div class="flex items-center space-x-2">
            <a 
              href="<?php echo ($rol_usuario === 'admin') ? '../dashboard_admin/dashboard_admin.php' : '../dashboard_cliente/dashboard_cliente.php'; ?>" 
              class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300 flex items-center space-x-1"
            >
              <span>⚡ Hola de nuevo,</span>
              <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>
            </a>
            <a href="index.php?logout=1" class="text-sm px-3 py-2 border border-red-400 rounded-lg hover:bg-red-500 hover:text-white transition duration-300">Salir</a>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Hero -->
  <section class="pt-32 pb-12 bg-gradient-to-r from-[#0b1c35] via-black to-[#0b1c35] text-center fade-up">
    <h2 class="text-4xl font-bold text-[#00e0ff] mb-4">Bienvenido a Electro Wizard</h2>
    <p class="text-white text-lg">Tu tienda de electrodomésticos con la energía de un rayo ⚡</p>
  </section>

  <!-- Categorías -->
  <section id="categorias" class="py-16 bg-gray-100 fade-up">
    <div class="max-w-10xl mx-auto px-10">
      <h3 class="text-3xl font-bold text-[#0b1c35] mb-10 text-center">Categorías de Electrodomésticos</h3>

      <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-8 justify-items-center">

        <a href="vistas/televisores.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
          <h4 class="text-xl font-semibold text-[#0b1c35]">Televisores</h4>
          <img src="imagenes/televisores.jpg" alt="Televisores" class="w-50 mx-auto mt-4">
        </a>

        <a href="vistas/refrigeradores.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
          <h4 class="text-xl font-semibold text-[#0b1c35]">Refrigeradores</h4>
          <img src="imagenes/refrigeradores.jpg" alt="Refrigeradores" class="w-50 mx-auto mt-4">
        </a>

        <a href="vistas/lavadoras.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
          <h4 class="text-xl font-semibold text-[#0b1c35]">Lavadoras</h4>
          <img src="imagenes/lavadoras.jpg" alt="Lavadoras" class="w-50 mx-auto mt-4">
        </a>

        <a href="vistas/pequenos-electrodomesticos.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
          <h4 class="text-xl font-semibold text-[#0b1c35]">Pequeños Electrodomésticos</h4>
          <img src="imagenes/pequenos-electrodomesticos.jpg" alt="Pequeños Electrodomésticos" class="w-50 mx-auto mt-4">
        </a>

        <a href="vistas/gamer.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
          <h4 class="text-xl font-semibold text-[#0b1c35]">Gamer</h4>
          <img src="imagenes/gamer.jpg" alt="Gamer" class="w-50 mx-auto mt-4">
        </a>
      </div>
    </div>
  </section>

  <!-- Productos destacados -->
  <section id="productos" class="py-16 bg-white fade-up">
    <div class="max-w-7xl mx-auto px-6">
      <h3 class="text-3xl font-bold text-[#0b1c35] mb-10 text-center">Productos Destacados</h3>

      <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-8">
        <?php if (count($productos_destacados) > 0): ?>
          <?php foreach($productos_destacados as $p): ?>
            <div class="border rounded-xl shadow hover:shadow-lg p-4 text-center fade-up">
              <img src="../dashboard_admin/imagenes_productos/<?php echo htmlspecialchars($p['imagen']); ?>" 
                   alt="<?php echo htmlspecialchars($p['nombre_producto']); ?>" 
                   class="w-32 h-32 object-contain mx-auto mb-4">
              <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($p['nombre_producto']); ?></h4>
              <p class="text-[#0b1c35] font-bold">$<?php echo number_format($p['precio'], 0, ',', '.'); ?> COP</p>

              <?php if(!$sesion_activa): ?>
                <button onclick="alert('Por favor, inicia sesión para agregar productos al carrito.')" class="btn-electric mt-2">Agregar al carrito</button>
              <?php elseif($rol_usuario === 'cliente'): ?>
                <a href="index.php?add_cart=<?php echo $p['id_producto']; ?>" class="btn-electric mt-2 inline-block text-center">Agregar al carrito</a>
              <?php else: ?>
                <button onclick="alert('Solo los clientes pueden agregar productos al carrito.')" class="btn-electric mt-2 opacity-70 cursor-not-allowed">Agregar al carrito</button>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-gray-500 col-span-4">No hay productos disponibles aún.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Contacto -->
  <section id="contacto" class="py-16 bg-[#0b1c35] text-white fade-up">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h3 class="text-3xl font-bold mb-6">Contáctanos</h3>
      <p class="mb-8">Para más información sobre productos y cotizaciones, envíanos un mensaje.</p>
      <form class="space-y-6 max-w-md mx-auto" id="contact-form">
        <input type="text" placeholder="Nombre" required class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#00e0ff]">
        <input type="email" placeholder="Correo" required class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#00e0ff]">
        <textarea placeholder="Mensaje" rows="4" required class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-[#00e0ff]"></textarea>
        <button type="submit" class="btn mt-4">Enviar</button>
      </form>
    </div>
  </section>

  <footer class="bg-black text-center py-4 text-gray-400 text-sm fade-up">
    © 2025 Electro Wizard. Todos los derechos reservados ⚡
  </footer>

</body>
</html>
