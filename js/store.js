const PRODUCTS = [
  {
    id: 1,
    name: "T-shirt Homme Premium",
    category: "Vêtements homme",
    price: 8500,
    image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80",
    description: "T-shirt respirant, style urbain, parfait pour Douala et Yaoundé.",
    sizes: ["S", "M", "L", "XL"],
    colors: ["Noir", "Blanc", "Bleu"]
  },
  {
    id: 2,
    name: "Robe Femme Casual",
    category: "Vêtements femme",
    price: 14000,
    image: "https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80",
    description: "Robe légère et élégante pour sorties et événements.",
    sizes: ["S", "M", "L"],
    colors: ["Rouge", "Beige", "Noir"]
  },
  {
    id: 3,
    name: "Ensemble Enfant",
    category: "Vêtements enfant",
    price: 9500,
    image: "https://images.unsplash.com/photo-1519238359922-989348752efb?auto=format&fit=crop&w=900&q=80",
    description: "Confortable et résistant pour l'école et les sorties.",
    sizes: ["4 ans", "6 ans", "8 ans", "10 ans"],
    colors: ["Jaune", "Vert", "Bleu"]
  },
  {
    id: 4,
    name: "Sneakers Homme",
    category: "Chaussures homme",
    price: 22000,
    image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80",
    description: "Sneakers légères pour la marche et le style quotidien.",
    sizes: ["40", "41", "42", "43", "44"],
    colors: ["Noir", "Gris"]
  },
  {
    id: 5,
    name: "Sandales Femme",
    category: "Chaussures femme",
    price: 12000,
    image: "https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=900&q=80",
    description: "Sandales modernes, légères, adaptées au climat chaud.",
    sizes: ["37", "38", "39", "40"],
    colors: ["Marron", "Noir", "Doré"]
  },
  {
    id: 6,
    name: "Smartphone Android 128Go",
    category: "Téléphones",
    price: 145000,
    image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80",
    description: "Grand écran, batterie longue durée, idéal pour travail et vidéos.",
    sizes: ["Unique"],
    colors: ["Noir", "Bleu nuit"]
  },
  {
    id: 7,
    name: "Coque iPhone Renforcée",
    category: "Accessoires téléphone",
    price: 6000,
    image: "https://images.unsplash.com/photo-1603313011108-6c48d52f5033?auto=format&fit=crop&w=900&q=80",
    description: "Protection anti-choc + design stylé.",
    sizes: ["iPhone 12", "iPhone 13", "iPhone 14"],
    colors: ["Transparent", "Noir", "Rose"]
  },
  {
    id: 8,
    name: "Polo Unisexe",
    category: "Polo",
    price: 10000,
    image: "https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=80",
    description: "Polo propre et chic pour bureau ou sortie.",
    sizes: ["S", "M", "L", "XL"],
    colors: ["Blanc", "Noir", "Vert"]
  }
];

const CART_KEY = 'bolio_cart';
const PAID_KEY = 'bolio_paid';
const REVIEW_KEY = 'bolio_reviews';

function formatFCFA(amount) {
  return `${amount.toLocaleString('fr-FR')} FCFA`;
}

function getCart() {
  return JSON.parse(localStorage.getItem(CART_KEY) || '[]');
}

function setCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function addToCart(productId, size, color) {
  const cart = getCart();
  cart.push({ productId, size, color, qty: 1 });
  setCart(cart);
  alert('Produit ajouté au panier ✅');
}

function renderProductGrid(containerId, searchInputId) {
  const container = document.getElementById(containerId);
  const searchInput = document.getElementById(searchInputId);

  const draw = () => {
    const term = (searchInput?.value || '').toLowerCase().trim();
    const filtered = PRODUCTS.filter((p) => (`${p.name} ${p.category} ${p.description}`).toLowerCase().includes(term));

    container.innerHTML = filtered.length ? filtered.map((p) => `
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 product-card border-0 shadow-sm">
          <img src="${p.image}" class="card-img-top" alt="${p.name}">
          <div class="card-body d-flex flex-column">
            <small class="text-primary fw-semibold">${p.category}</small>
            <h3 class="h5 mt-2">${p.name}</h3>
            <p class="text-muted small flex-grow-1">${p.description}</p>
            <strong class="d-block mb-3">${formatFCFA(p.price)}</strong>
            <a href="product.php?id=${p.id}" class="btn btn-dark w-100 animated-btn">Voir détail</a>
          </div>
        </div>
      </div>
    `).join('') : '<p>Aucun produit trouvé.</p>';
  };

  draw();
  if (searchInput) searchInput.addEventListener('input', draw);
}

function renderProductDetail(containerId) {
  const container = document.getElementById(containerId);
  const params = new URLSearchParams(window.location.search);
  const id = Number(params.get('id'));
  const product = PRODUCTS.find((p) => p.id === id);

  if (!product) {
    container.innerHTML = '<p>Produit introuvable.</p>';
    return;
  }

  const reviews = JSON.parse(localStorage.getItem(REVIEW_KEY) || '{}');
  const productReviews = reviews[id] || [];

  container.innerHTML = `
    <div class="row g-4">
      <div class="col-lg-6"><img class="img-fluid rounded-4 shadow" src="${product.image}" alt="${product.name}"></div>
      <div class="col-lg-6">
        <small class="text-primary fw-semibold">${product.category}</small>
        <h1 class="h2 mt-2">${product.name}</h1>
        <p class="text-muted">${product.description}</p>
        <h2 class="h4 mb-3">${formatFCFA(product.price)}</h2>

        <label class="form-label">Taille</label>
        <select class="form-select mb-3" id="sizeSelect">
          ${product.sizes.map((s) => `<option>${s}</option>`).join('')}
        </select>

        <label class="form-label">Couleur</label>
        <select class="form-select mb-3" id="colorSelect">
          ${product.colors.map((c) => `<option>${c}</option>`).join('')}
        </select>

        <button id="addToCartBtn" class="btn btn-success w-100 animated-btn">Ajouter au panier</button>
      </div>
    </div>

    <section class="mt-5">
      <h3>Avis clients</h3>
      <form id="reviewForm" class="mb-3">
        <div class="row g-2">
          <div class="col-md-4"><input required id="reviewName" class="form-control" placeholder="Ton prénom"></div>
          <div class="col-md-8"><input required id="reviewText" class="form-control" placeholder="Ton avis sur ce produit"></div>
        </div>
        <button class="btn btn-outline-primary mt-2">Publier l'avis</button>
      </form>
      <div id="reviewsList">
        ${productReviews.length ? productReviews.map((r) => `<div class="review-item"><strong>${r.name}</strong><p class="mb-0">${r.text}</p></div>`).join('') : '<p class="text-muted">Pas encore d\'avis.</p>'}
      </div>
    </section>
  `;

  document.getElementById('addToCartBtn').addEventListener('click', () => {
    addToCart(id, document.getElementById('sizeSelect').value, document.getElementById('colorSelect').value);
  });

  document.getElementById('reviewForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const name = document.getElementById('reviewName').value.trim();
    const text = document.getElementById('reviewText').value.trim();
    if (!name || !text) return;

    const all = JSON.parse(localStorage.getItem(REVIEW_KEY) || '{}');
    all[id] = all[id] || [];
    all[id].push({ name, text });
    localStorage.setItem(REVIEW_KEY, JSON.stringify(all));
    renderProductDetail(containerId);
  });
}

function renderCart() {
  const itemsWrap = document.getElementById('cartItems');
  const subTotalEl = document.getElementById('subTotal');
  const grandTotalEl = document.getElementById('grandTotal');
  const payBtn = document.getElementById('payBtn');
  const confirmOrderBtn = document.getElementById('confirmOrderBtn');
  const paymentMethod = document.getElementById('paymentMethod');
  const message = document.getElementById('orderMessage');

  const cart = getCart();
  let subtotal = 0;

  if (!cart.length) {
    itemsWrap.innerHTML = '<p class="text-muted">Ton panier est vide. Ajoute des produits depuis la boutique.</p>';
  } else {
    itemsWrap.innerHTML = cart.map((item, index) => {
      const p = PRODUCTS.find((prod) => prod.id === item.productId);
      subtotal += p.price * item.qty;
      return `
        <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
          <div>
            <strong>${p.name}</strong>
            <div class="small text-muted">Taille: ${item.size} • Couleur: ${item.color}</div>
            <div>${formatFCFA(p.price)}</div>
          </div>
          <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})">Retirer</button>
        </div>
      `;
    }).join('');
  }

  const total = subtotal + 2500;
  subTotalEl.textContent = formatFCFA(subtotal);
  grandTotalEl.textContent = formatFCFA(total);

  const paid = localStorage.getItem(PAID_KEY) === 'yes';
  confirmOrderBtn.disabled = !paid;

  payBtn.addEventListener('click', () => {
    if (!cart.length) {
      message.innerHTML = '<div class="alert alert-warning">Ajoute au moins un article avant de payer.</div>';
      return;
    }
    if (!paymentMethod.value) {
      message.innerHTML = '<div class="alert alert-warning">Choisis un moyen de paiement.</div>';
      return;
    }

    localStorage.setItem(PAID_KEY, 'yes');
    confirmOrderBtn.disabled = false;
    message.innerHTML = '<div class="alert alert-success">Paiement validé ✅ Tu peux maintenant confirmer la commande.</div>';
  });

  confirmOrderBtn.addEventListener('click', () => {
    if (localStorage.getItem(PAID_KEY) !== 'yes') {
      message.innerHTML = '<div class="alert alert-danger">Paiement requis avant confirmation.</div>';
      return;
    }

    localStorage.removeItem(CART_KEY);
    localStorage.removeItem(PAID_KEY);
    message.innerHTML = '<div class="alert alert-success">Commande confirmée 🎉 Merci pour ta confiance.</div>';
    setTimeout(() => window.location.href = 'products.php', 1300);
  });
}

function removeItem(index) {
  const cart = getCart();
  cart.splice(index, 1);
  setCart(cart);
  renderCart();
}
