/* AR_script.js
   - Lista productos por categoría (fetch a /api/products_list.php)
   - Carrito en memoria con add/remove/update y subtotal automático
   - Badge con cantidad total
   - Checkout: POST JSON a /api/order_create.php y muestra confirmación
*/
const $ = (q)=>document.querySelector(q);
const $$ = (q)=>document.querySelectorAll(q);

const state = { products: [], cart: [] };

function renderProducts() {
  const c = $("#ttProducts");
  c.innerHTML = state.products.map(p => `
    <article class="tt-card">
      <h3>${p.name}</h3>
      <small>${p.category}</small>
      <div>$${p.price.toFixed(2)}</div>
      <button data-id="${p.id}" data-price="${p.price}" data-name="${p.name}">Agregar</button>
    </article>
  `).join("");
  c.onclick = (e)=>{
    const b = e.target.closest("button[data-id]");
    if (!b) return;
    addToCart({ id:+b.dataset.id, name:b.dataset.name, price:+b.dataset.price, qty:1 });
  };
}

function addToCart(item) {
  const i = state.cart.findIndex(x=>x.id===item.id);
  if (i>=0) state.cart[i].qty++;
  else state.cart.push(item);
  renderCart();
}

function removeFromCart(id) {
  state.cart = state.cart.filter(x=>x.id!==id);
  renderCart();
}

function updateQty(id, qty) {
  qty = Math.max(1, qty|0);
  const it = state.cart.find(x=>x.id===id);
  if (it) it.qty = qty;
  renderCart();
}

function renderCart() {
  const c = $("#ttCart");
  if (!state.cart.length) { c.innerHTML = "<em>Vacío</em>"; }
  else {
    c.innerHTML = state.cart.map(it => `
      <div class="tt-row">
        <span>${it.name}</span>
        <input type="number" min="1" value="${it.qty}" data-id="${it.id}" />
        <span>$${(it.qty*it.price).toFixed(2)}</span>
        <button class="tt-remove" data-id="${it.id}">✕</button>
      </div>
    `).join("");
  }
  // Eventos
  c.oninput = (e)=>{
    const inp = e.target.closest('input[type="number"]');
    if (inp) updateQty(+inp.dataset.id, +inp.value);
  };
  c.onclick = (e)=>{
    const btn = e.target.closest('.tt-remove');
    if (btn) removeFromCart(+btn.dataset.id);
  };

  // Subtotal y badge
  const subtotal = state.cart.reduce((s,it)=>s+it.qty*it.price,0);
  $("#ttSubtotal").textContent = subtotal.toFixed(2);
  $("#ttCartBadge").textContent = state.cart.reduce((s,it)=>s+it.qty,0);
}

async function loadProducts(category='all') {
  const res = await fetch(`api/products_list.php?category=${encodeURIComponent(category)}`);
  state.products = await res.json();
  renderProducts();
}

async function checkout(e) {
  e.preventDefault();
  if (!state.cart.length) return $("#ttMsg").textContent = "Agrega productos al carrito";
  const fd = new FormData(e.target);
  const customer = Object.fromEntries(fd.entries());
  const body = JSON.stringify({ items: state.cart, customer });
  const res = await fetch('api/order_create.php',{ method:'POST', headers:{'Content-Type':'application/json'}, body });
  const data = await res.json();
  if (data.ok) {
    $("#ttMsg").textContent = `Pedido #${data.order_id} creado. Total $${data.total.toFixed(2)}`;
    state.cart = [];
    renderCart();
    e.target.reset();
  } else {
    $("#ttMsg").textContent = "Error creando el pedido";
  }
}

window.addEventListener('DOMContentLoaded', ()=>{
  $$('#\\.tt-nav button, .tt-nav button').forEach(b=>b.addEventListener('click',()=>loadProducts(b.dataset.cat)));
  $("#ttCheckout").addEventListener('submit', checkout);
  loadProducts('all');
});
