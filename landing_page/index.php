<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Electro Wizard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navbar -->
  <header class="bg-[#0b1c35] text-white shadow-md fixed top-0 w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
      <h1 class="text-2xl font-bold text-[#00e0ff]">⚡ Electro Wizard</h1>
      <nav class="space-x-6 text-white font-medium">
 <a href="#" class="hover:text-[#00e0ff]">Inicio</a>
  <a href="#categorias" class="hover:text-[#00e0ff]">Categorías</a>
  <a href="#contacto" class="hover:text-[#00e0ff]">Contacto</a>
  <a href="../login/login.html" class="hover:text-[#00e0ff]">Iniciar Sesión</a>
      </nav>
    </div>
  </header>

  
  <section class="pt-32 pb-12 bg-gradient-to-r from-[#0b1c35] via-black to-[#0b1c35] text-center">
  <h2 class="text-4xl font-bold text-[#00e0ff] mb-4 fade-in">Bienvenido a Electro Wizard</h2>
  <p class="text-white text-lg">Tu tienda de electrodomésticos con la energía de un rayo ⚡</p>
</section>

  <!-- Categorías -->
<section id="categorias" class="py-16 bg-gray-100">
  <div class="max-w-10xl mx-auto px-10">
    <h3 class="text-3xl font-bold text-[#0b1c35] mb-10 text-center">Categorías de Electrodomésticos</h3>

  <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-8 justify-items-center">


      <!-- Televisores -->
      <a href="categorias/televisores.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
        <h4 class="text-xl font-semibold text-[#0b1c35]">Televisores</h4>
        <img src="imagenes/televisores.jpg" alt="Televisores" class="w-50 mx-auto mt-4">
      </a>

      <!-- Refrigeradores -->
      <a href="categorias/refrigeradores.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
        <h4 class="text-xl font-semibold text-[#0b1c35]">Refrigeradores</h4>
        <img src="imagenes/refrigeradores.jpg" alt="Refrigeradores"class="w-50 mx-auto mt-4">
      </a>

      <!-- Lavadoras -->
      <a href="categorias/lavadoras.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
        <h4 class="text-xl font-semibold text-[#0b1c35]">Lavadoras</h4>
        <img src="imagenes/lavadoras.jpg" alt="Lavadoras"class="w-50 mx-auto mt-4">

      <!-- Pequeños Electrodomésticos -->
      <a href="categorias/pequenos-electrodomesticos.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
        <h4 class="text-xl font-semibold text-[#0b1c35]">Pequeños Electrodomésticos</h4>
        <img src="imagenes/pequenos-electrodomesticos.jpg" alt="Pequeños Electrodomésticos" class="w-50 mx-auto mt-4">
      </a>

      <!-- Gamer -->
      <a href="categorias/gamer.php" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105 p-6 block text-center">
        <h4 class="text-xl font-semibold text-[#0b1c35]">Gamer</h4>
        <img src="imagenes/gamer.jpg" alt="Gamer" class="w-50 mx-auto mt-4">
      </a>

    </div>
  </div>
</section>

</section>
<!-- Productos destacados -->
<section id="productos" class="py-16 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <h3 class="text-3xl font-bold text-[#0b1c35] mb-10 text-center">Productos Destacados</h3>
    
    <!-- Grid de productos -->
    <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-8">

      <!-- Producto 1 -->
      <div class="border rounded-xl shadow hover:shadow-lg p-4 text-center fade-in">
        <img src="imagenes/neveraejemplo.jpg" alt="Nevera LG" class="w-32 mx-auto mb-4">
        <h4 class="text-lg font-semibold">Nevera LG 350L</h4>
        <p class="text-[#0b1c35] font-bold">$2.300.000 COP</p>
        <button class="btn-electric mt-2">Agregar al carrito</button>
      </div>

      <!-- Producto 2 -->
      <div class="border rounded-xl shadow hover:shadow-lg p-4 text-center fade-in">
        <img src="imagenes/lavadoraejemplo.jpg" alt="Lavadora Samsung" class="w-32 mx-auto mb-4">
        <h4 class="text-lg font-semibold">Lavadora Samsung 20kg</h4>
        <p class="text-[#0b1c35] font-bold">$1.800.000 COP</p>
        <button class="btn-electric mt-2">Agregar al carrito</button>
      </div>

      <!-- Producto 3 -->
      <div class="border rounded-xl shadow hover:shadow-lg p-4 text-center fade-in">
        <img src="imagenes/airfrierejemplo.jpg" alt="Airfryer Oster" class="w-32 mx-auto mb-4">
        <h4 class="text-lg font-semibold">Airfryer Oster</h4>
        <p class="text-[#0b1c35] font-bold">$350.000 COP</p>
        <button class="btn-electric mt-2">Agregar al carrito</button>
      </div>

      <!-- Producto 4 -->
      <div class="border rounded-xl shadow hover:shadow-lg p-4 text-center fade-in">
        <img src="imagenes/ps5ejemplo.jpg" alt="PlayStation 5" class="w-32 mx-auto mb-4">
        <h4 class="text-lg font-semibold">PlayStation 5</h4>
        <p class="text-[#0b1c35] font-bold">$3.200.000 COP</p>
        <button class="btn-electric mt-2">Agregar al carrito</button>
      </div>

    </div>
  </div>
</section>


  <!-- Contacto -->
  <section id="contacto" class="py-16 bg-[#0b1c35] text-white">
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

  <!-- Footer -->
  <footer class="bg-black text-center py-4 text-gray-400 text-sm">
    © 2025 Electro Wizard. Todos los derechos reservados ⚡
  </footer>

  <script src="script.js"></script>
</body>
</html>
