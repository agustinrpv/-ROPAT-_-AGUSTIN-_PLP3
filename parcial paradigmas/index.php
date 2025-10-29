<?php /* AR - FoodExpress */ ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AR FoodExpress</title>
  <link rel="stylesheet" href="css/AR_estilos.css" />
</head>
<body>
<header class="ar-header">
  <h1 class="ar-logo">AR FoodExpress</h1>
  <nav class="ar-nav">
    <button data-cat="all">Todos</button>
    <button data-cat="Pizzas">Pizzas</button>
    <button data-cat="Burgers">Burgers</button>
    <button data-cat="Bebidas">Bebidas</button>
  </nav>
  <div class="ar-cart-badge" id="arCartBadge">0</div>
</header>

<main class="ar-grid">
  <section>
    <h2>Menú</h2>
    <div id="arProducts" class="ar-products"></div>
  </section>
  <aside>
    <h2>Carrito</h2>
    <div id="arCart"></div>
    <div class="ar-totals">Subtotal: $<span id="arSubtotal">0</span></div>
    <form id="arCheckout">
      <input required name="name" placeholder="Nombre" />
      <input required name="phone" placeholder="Teléfono" />
      <input required name="address" placeholder="Dirección" />
      <button type="submit">Confirmar Pedido</button>
    </form>
    <div id="arMsg"></div>
  </aside>
</main>

<script src="js/AR_script.js"></script>
</body>
</html>
