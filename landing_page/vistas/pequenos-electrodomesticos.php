<?php
session_start();
include __DIR__ . "/../../config/conexion.php";

// 🔹 Detectar sesión
$sesion_activa = isset($_SESSION['id_usuario']);
$nombre_usuario = $sesion_activa ? $_SESSION['nombre'] : null;
$rol_usuario = $sesion_activa ? $_SESSION['rol'] : null;
$id_usuario = $sesion_activa ? $_SESSION['id_usuario'] : null;

// 🛒 Agregar al carrito
if (isset($_GET['add_cart']) && $sesion_activa && $rol_usuario === 'cliente') {
  $id_producto = intval($_GET['add_cart']);
  
  // Verificar si ya está en el carrito
  $check = $conexion->prepare("SELECT cantidad FROM carrito WHERE id_usuario=? AND id_producto=?");
  $check->bind_param("ii", $id_usuario, $id_producto);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {
    $conexion->query("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = $id_usuario AND id_producto = $id_producto");
  } else {
    $conexion->query("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES ($id_usuario, $id_producto, 1)");
  }

  echo "<script>alert('✅ Producto agregado al carrito'); window.location.href='pequenos-electrodomesticos.php';</script>";
  exit;
}

// 🔹 Traer productos de categoría 'Pequeños Electrodomésticos'
$query = $conexion->query("
  SELECT p.id_producto, p.nombre_producto, p.precio, p.descripcion, p.imagen, p.stock 
  FROM productos p
  INNER JOIN categorias c ON p.id_categoria = c.id_categoria
  WHERE c.nombre_categoria = 'Pequeños Electrodomésticos'
");
$productos = $query->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pequeños Electrodomésticos - Electro Wizard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style.css" />
  <style>
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

  <!-- 🔷 Navbar -->
  <header class="bg-[#0b1c35] text-white shadow-md fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
      
      <!-- Logo -->
      <a href="../index.php" class="flex items-center space-x-2 hover:scale-105 transition-transform duration-200">
        <h1 class="text-2xl font-bold text-[#00e0ff] cursor-pointer">⚡ Electro Wizard</h1>
      </a>

      <!-- Menú -->
      <nav class="flex items-center space-x-4 text-white font-medium fade-up">
        <span class="text-sm md:text-base">Aquí verás nuestros <strong class="text-[#00e0ff]">Pequeños Electrodomésticos</strong> 🍳</span>

        <?php if (!$sesion_activa): ?>
          <a href="../../login/login.html" class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300">Iniciar Sesión</a>
          <a href="../../login/registro.html" class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300">Registrar Cuenta</a>
        <?php else: ?>
          <div class="flex items-center space-x-3">
            <a 
              href="<?php echo ($rol_usuario === 'admin') ? '../../dashboard_admin/dashboard_admin.php' : '../../dashboard_cliente/dashboard_cliente.php'; ?>" 
              class="px-4 py-2 border border-[#00e0ff] rounded-lg hover:bg-[#00e0ff] hover:text-[#0b1c35] transition duration-300"
            >
              ⚡ Hola, <?php echo htmlspecialchars($nombre_usuario); ?>
            </a>
            <a href="../../login/logout.php" class="text-sm px-3 py-2 border border-red-400 rounded-lg hover:bg-red-500 hover:text-white transition duration-300">Salir</a>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- 🍳 Sección principal -->
  <main class="pt-32 pb-16 max-w-7xl mx-auto px-6 fade-up">
    <h2 class="text-3xl font-bold text-[#0b1c35] mb-8 text-center">Pequeños Electrodomésticos Disponibles</h2>

    <?php if (count($productos) > 0): ?>
      <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-8">
        <?php foreach ($productos as $p): ?>
          <div class="border rounded-xl shadow hover:shadow-xl transition hover:-translate-y-1 bg-white p-5 text-center">
            <img src="../../dashboard_admin/imagenes_productos/<?php echo htmlspecialchars($p['imagen']); ?>" 
                 alt="<?php echo htmlspecialchars($p['nombre_producto']); ?>" 
                 class="w-40 h-40 object-contain mx-auto mb-4">
            
            <h3 class="text-lg font-semibold text-[#0b1c35] mb-2">
              <?php echo htmlspecialchars($p['nombre_producto']); ?>
            </h3>
            <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($p['descripcion']); ?></p>
            <p class="text-[#0b1c35] font-bold mb-2">$<?php echo number_format($p['precio'], 0, ',', '.'); ?> COP</p>
            <p class="text-sm text-gray-500 mb-3">Stock disponible: <?php echo intval($p['stock']); ?></p>

            <?php if(!$sesion_activa): ?>
              <button onclick="alert('Por favor, inicia sesión para agregar productos al carrito.')" class="btn-electric mt-2">Agregar al carrito</button>
            <?php elseif($rol_usuario === 'cliente'): ?>
              <a href="pequenos-electrodomesticos.php?add_cart=<?php echo $p['id_producto']; ?>" class="btn-electric mt-2 inline-block text-center">Agregar al carrito</a>
            <?php else: ?>
              <button onclick="alert('Solo los clientes pueden agregar productos al carrito.')" class="btn-electric mt-2 opacity-70 cursor-not-allowed">Agregar al carrito</button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500 mt-10">No hay productos disponibles en esta categoría.</p>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer class="bg-black text-center py-4 text-gray-400 text-sm fade-up">
    © 2025 Electro Wizard. Todos los derechos reservados ⚡
  </footer>

</body>
</html>
