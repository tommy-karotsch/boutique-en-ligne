# 🏗️ BONNE BASE EN < 1 SEMAINE (3-4 jours intensifs)

**Objectif** : Architecture solide + code maintenable qu'on peut extend après  
**Pas besoin** : Design parfait, admin complet, toutes fonctionnalités


#### 1.2 Models complets [3h]
Copier le pattern gamekeeper, remplir chaque Model :

**ItemModel.php**
```php
<?php namespace App\Models;
class ItemModel extends Model {
  protected string $table = 'items';
  
  public function findAllWithDetails(): array {
    $stmt = $this->db->query(
      "SELECT i.*, c.name as category, r.name as rarity, col.name as color, col.hex_code
       FROM items i
       JOIN categories c ON i.category_id = c.id
       JOIN rarities r ON i.rarity_id = r.id
       JOIN colors col ON i.color_id = col.id"
    );
    return $stmt->fetchAll();
  }
  
  public function findByIdWithDetails(int $id): array|false {
    $stmt = $this->db->prepare(
      "SELECT i.*, c.name as category, r.name as rarity, col.name as color
       FROM items i
       JOIN categories c ON i.category_id = c.id
       JOIN rarities r ON i.rarity_id = r.id
       JOIN colors col ON i.color_id = col.id
       WHERE i.id = :id"
    );
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
  }
  
  public function updateStock(int $id, int $qty): bool {
    $stmt = $this->db->prepare("UPDATE items SET stock = stock + :qty WHERE id = :id");
    return $stmt->execute([':qty' => $qty, ':id' => $id]);
  }
  
  public function create(array $data): bool {
    $stmt = $this->db->prepare(
      "INSERT INTO items (name, description, price, stock, image, category_id, color_id, rarity_id)
       VALUES (:name, :desc, :price, :stock, :image, :cat, :color, :rarity)"
    );
    return $stmt->execute([
      ':name' => $data['name'],
      ':desc' => $data['description'] ?? '',
      ':price' => $data['price'],
      ':stock' => $data['stock'],
      ':image' => $data['image'] ?? 'default.png',
      ':cat' => $data['category_id'],
      ':color' => $data['color_id'],
      ':rarity' => $data['rarity_id']
    ]);
  }
}
```

**CartModel.php** (nouveau)
```php
<?php namespace App\Models;
class CartModel extends Model {
  protected string $table = 'carts';
  
  public function getCart(int $user_id): array {
    $stmt = $this->db->prepare(
      "SELECT i.*, ci.quantity, (i.price * ci.quantity) as subtotal
       FROM cart_items ci
       JOIN items i ON ci.item_id = i.id
       JOIN carts c ON ci.cart_id = c.id
       WHERE c.user_id = :user_id"
    );
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll();
  }
  
  public function addItem(int $user_id, int $item_id, int $qty): bool {
    // Récupérer ou créer le panier
    $cart = $this->db->prepare("SELECT id FROM carts WHERE user_id = :user_id");
    $cart->execute([':user_id' => $user_id]);
    $result = $cart->fetch();
    $cart_id = $result['id'] ?? null;
    
    if (!$cart_id) {
      $stmt = $this->db->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
      $stmt->execute([':user_id' => $user_id]);
      $cart_id = $this->db->lastInsertId();
    }
    
    // Ajouter item (ou incrémenter)
    $stmt = $this->db->prepare(
      "INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (:cart_id, :item_id, :qty)
       ON DUPLICATE KEY UPDATE quantity = quantity + :qty"
    );
    return $stmt->execute([':cart_id' => $cart_id, ':item_id' => $item_id, ':qty' => $qty]);
  }
  
  public function clear(int $user_id): bool {
    $stmt = $this->db->prepare(
      "DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM carts WHERE user_id = :user_id)"
    );
    return $stmt->execute([':user_id' => $user_id]);
  }
}
```

**OrderModel.php**
```php
<?php namespace App\Models;
class OrderModel extends Model {
  protected string $table = 'orders';
  
  public function create(int $user_id, string $address, float $total): int|false {
    $stmt = $this->db->prepare(
      "INSERT INTO orders (user_id, delivery_address, total) VALUES (:user_id, :addr, :total)"
    );
    $success = $stmt->execute([':user_id' => $user_id, ':addr' => $address, ':total' => $total]);
    return $success ? $this->db->lastInsertId() : false;
  }
  
  public function addItem(int $order_id, int $item_id, int $qty, float $price): bool {
    $stmt = $this->db->prepare(
      "INSERT INTO order_items (order_id, item_id, quantity, unit_price) 
       VALUES (:order_id, :item_id, :qty, :price)"
    );
    return $stmt->execute([
      ':order_id' => $order_id,
      ':item_id' => $item_id,
      ':qty' => $qty,
      ':price' => $price
    ]);
  }
  
  public function getItems(int $order_id): array {
    $stmt = $this->db->prepare(
      "SELECT oi.*, i.name, i.image FROM order_items oi
       JOIN items i ON oi.item_id = i.id
       WHERE oi.order_id = :order_id"
    );
    $stmt->execute([':order_id' => $order_id]);
    return $stmt->fetchAll();
  }
  
  public function updateStatus(int $id, string $status): bool {
    $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
    return $stmt->execute([':status' => $status, ':id' => $id]);
  }
}
```

**CategoryModel, ColorModel, RarityModel** → CRUD simple (copy pattern gamekeeper)

#### 1.3 Tester Models [1h]
```php
// Vérifier connection + requêtes dans un fichier test
$user = new UserModel();
$item = new ItemModel();
var_dump($item->findAllWithDetails()); // Voir si les JOINs marchent
```

✅ **FIN JOUR 1** : DB + Models solides, réutilisables

---

### **JOUR 2 : CONTROLLERS + HOME VIEW** [5-6h]

#### 2.1 UserController [2h]
```php
<?php namespace App\Controllers;
use App\Models\UserModel;

class UserController {
  private UserModel $userModel;
  
  public function __construct() {
    $this->userModel = new UserModel();
  }
  
  public function register(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pseudo = htmlspecialchars($_POST['pseudo'] ?? '');
      $email = htmlspecialchars($_POST['email'] ?? '');
      $password = $_POST['password'] ?? '';
      
      if (strlen($password) < 8) {
        $_SESSION['error'] = 'Mot de passe minimum 8 caractères';
        require __DIR__ . '/../Views/user/register.php';
        return;
      }
      
      if ($this->userModel->create($pseudo, $email, $password)) {
        $_SESSION['success'] = 'Inscription réussie!';
        header('Location: /user/login');
      } else {
        $_SESSION['error'] = 'Email ou pseudo déjà utilisé';
        require __DIR__ . '/../Views/user/register.php';
      }
      return;
    }
    
    require __DIR__ . '/../Views/user/register.php';
  }
  
  public function login(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = htmlspecialchars($_POST['email'] ?? '');
      $password = $_POST['password'] ?? '';
      
      $user = $this->userModel->findByEmail($email);
      if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_pseudo'] = $user['pseudo'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: /');
      } else {
        $_SESSION['error'] = 'Email ou mot de passe incorrect';
        require __DIR__ . '/../Views/user/login.php';
      }
      return;
    }
    
    require __DIR__ . '/../Views/user/login.php';
  }
  
  public function logout(): void {
    session_destroy();
    header('Location: /');
  }
}
```

#### 2.2 HomeController [1h]
```php
<?php namespace App\Controllers;
use App\Models\ItemModel;

class HomeController {
  private ItemModel $itemModel;
  
  public function __construct() {
    $this->itemModel = new ItemModel();
  }
  
  public function index(): void {
    $items = $this->itemModel->findAllWithDetails();
    $bestsellers = array_slice($items, 0, 6);
    require __DIR__ . '/../Views/home/index.php';
  }
}
```

#### 2.3 Vues + CSS [2-3h]

**layout/header.php** - Simple et fonctionnel
```php
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RL Shop</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
  <header class="navbar">
    <div class="container">
      <a href="/" class="logo">RL.SHOP</a>
      <nav>
        <a href="/item">Catalogue</a>
        <a href="/cart">Panier</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="/user/profile"><?= htmlspecialchars($_SESSION['user_pseudo']) ?></a>
          <a href="/user/logout">Déco</a>
        <?php else: ?>
          <a href="/user/login">Connexion</a>
          <a href="/user/register">Inscription</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="container">
```

**home/index.php** - Minimaliste
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<section class="hero">
  <h1>La boutique des collecteurs</h1>
  <p>Items rares · Black Market · Exotiques · Certifiés</p>
  <a href="/item" class="btn">Voir les offres</a>
</section>

<section class="bestsellers">
  <h2>Items les plus populaires</h2>
  <div class="grid">
    <?php foreach ($bestsellers as $item): ?>
      <div class="card">
        <img src="/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p class="rarity"><?= htmlspecialchars($item['rarity']) ?></p>
        <p class="price"><?= number_format($item['price'], 2) ?>€</p>
        <a href="/item/show?id=<?= $item['id'] ?>" class="btn-small">Voir</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

**public/css/style.css** - Mobile-first basique
```css
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background: #0a0e27;
  color: #fff;
}

.container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }

/* Header */
.navbar {
  background: #000;
  padding: 15px 0;
  border-bottom: 2px solid #ff6b35;
}

.navbar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar nav { display: flex; gap: 20px; flex-wrap: wrap; }
.navbar a { color: #fff; text-decoration: none; font-size: 14px; }

/* Hero */
.hero {
  padding: 60px 20px;
  text-align: center;
  background: linear-gradient(135deg, #1a1a3e 0%, #0a0e27 100%);
}

.hero h1 { font-size: 36px; margin-bottom: 10px; }
.hero p { font-size: 16px; margin-bottom: 20px; color: #00d9ff; }

/* Buttons */
.btn {
  display: inline-block;
  padding: 12px 24px;
  background: #ff6b35;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  font-weight: bold;
  transition: 0.3s;
}

.btn:hover { background: #e55a25; }

.btn-small {
  padding: 8px 16px;
  font-size: 12px;
}

/* Grid */
.grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  margin: 30px 0;
}

@media (min-width: 768px) {
  .grid { grid-template-columns: repeat(2, 1fr); }
  .navbar nav { gap: 30px; }
}

@media (min-width: 1024px) {
  .grid { grid-template-columns: repeat(3, 1fr); }
}

/* Cards */
.card {
  background: #1a1a3e;
  border-radius: 8px;
  padding: 15px;
  text-align: center;
  border: 1px solid #00d9ff;
  transition: transform 0.3s;
}

.card:hover { transform: translateY(-5px); }

.card img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 4px;
  margin-bottom: 10px;
}

.card h3 { font-size: 16px; margin-bottom: 5px; }
.card .rarity { color: #00d9ff; font-size: 12px; margin-bottom: 10px; }
.card .price { font-size: 18px; font-weight: bold; color: #ff6b35; margin-bottom: 10px; }

/* Forms */
input, select, textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #00d9ff;
  background: #1a1a3e;
  color: white;
  border-radius: 4px;
}

input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #ff6b35;
}

label { display: block; margin-bottom: 5px; font-weight: bold; }

/* Footer */
footer {
  background: #000;
  border-top: 2px solid #ff6b35;
  padding: 30px 0;
  margin-top: 60px;
  text-align: center;
  font-size: 12px;
  color: #888;
}
```

✅ **FIN JOUR 2** : Home fonctionnelle, auth de base

---

## 📌 PHASE 2 : CORE FEATURES (J3-J4)

### **JOUR 3 : CATALOGUE + ITEM** [5h]

#### 3.1 ItemController
```php
public function index(): void {
  $items = $this->itemModel->findAllWithDetails();
  
  // Filtres simples
  if (!empty($_GET['category'])) {
    $items = array_filter($items, fn($i) => $i['category_id'] == $_GET['category']);
  }
  if (!empty($_GET['rarity'])) {
    $items = array_filter($items, fn($i) => $i['rarity_id'] == $_GET['rarity']);
  }
  
  $categories = $this->categoryModel->findAll();
  $rarities = $this->rarityModel->findAll();
  
  require __DIR__ . '/../Views/item/index.php';
}

public function show(): void {
  $id = $_GET['id'] ?? null;
  if (!$id) { header('Location: /item'); exit; }
  
  $item = $this->itemModel->findByIdWithDetails($id);
  if (!$item) { http_response_code(404); echo "Produit introuvable"; exit; }
  
  require __DIR__ . '/../Views/item/show.php';
}
```

#### 3.2 Vues Item [2-3h]

**item/index.php** - Catalogue avec filtres
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="catalog">
  <aside class="sidebar">
    <h3>Filtres</h3>
    <form method="get">
      <label>Catégorie</label>
      <select name="category">
        <option value="">Tous</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
      
      <label>Rareté</label>
      <select name="rarity">
        <option value="">Tous</option>
        <?php foreach ($rarities as $r): ?>
          <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
        <?php endforeach; ?>
      </select>
      
      <button type="submit" class="btn">Filtrer</button>
    </form>
  </aside>
  
  <main>
    <h1>Catalogue</h1>
    <div class="grid">
      <?php foreach ($items as $item): ?>
        <div class="card">
          <img src="/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p class="rarity"><?= htmlspecialchars($item['rarity']) ?></p>
          <p class="price"><?= number_format($item['price'], 2) ?>€</p>
          <a href="/item/show?id=<?= $item['id'] ?>" class="btn-small">Voir détails</a>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

**item/show.php** - Fiche produit
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<article class="product-detail">
  <div class="product-image">
    <img src="/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
  </div>
  
  <div class="product-info">
    <h1><?= htmlspecialchars($item['name']) ?></h1>
    
    <div class="specs">
      <p><strong>Type:</strong> <?= htmlspecialchars($item['category']) ?></p>
      <p><strong>Rareté:</strong> <?= htmlspecialchars($item['rarity']) ?></p>
      <p><strong>Couleur:</strong> <?= htmlspecialchars($item['color']) ?></p>
    </div>
    
    <p class="description"><?= htmlspecialchars($item['description']) ?></p>
    
    <div class="pricing">
      <p class="price"><?= number_format($item['price'], 2) ?>€</p>
      <p class="stock">Stock: <?= $item['stock'] > 0 ? $item['stock'] : 'Rupture' ?></p>
    </div>
    
    <?php if ($item['stock'] > 0): ?>
      <form action="/cart/add" method="post" class="add-cart-form">
        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
        <label>Quantité:</label>
        <input type="number" name="quantity" min="1" max="<?= $item['stock'] ?>" value="1">
        <button type="submit" class="btn">Ajouter au panier</button>
      </form>
    <?php endif; ?>
    
    <a href="/item" class="btn-secondary">← Retour catalogue</a>
  </div>
</article>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

✅ **FIN JOUR 3** : Catalog + Product details works

---

### **JOUR 4 : CART + ORDER** [6h]

#### 4.1 CartController
```php
<?php namespace App\Controllers;
use App\Models\CartModel;
use App\Models\ItemModel;

class CartController {
  private CartModel $cartModel;
  private ItemModel $itemModel;
  
  public function __construct() {
    $this->cartModel = new CartModel();
    $this->itemModel = new ItemModel();
  }
  
  public function index(): void {
    $cart = [];
    $total = 0;
    
    if (isset($_SESSION['user_id'])) {
      $cart = $this->cartModel->getCart($_SESSION['user_id']);
    } else {
      if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item_id => $qty) {
          $item = $this->itemModel->findById($item_id);
          if ($item) {
            $item['quantity'] = $qty;
            $cart[] = $item;
          }
        }
      }
    }
    
    foreach ($cart as $item) {
      $total += $item['price'] * $item['quantity'];
    }
    
    require __DIR__ . '/../Views/cart/index.php';
  }
  
  public function add(): void {
    $item_id = $_POST['item_id'] ?? null;
    $qty = (int)($_POST['quantity'] ?? 1);
    
    if (!$item_id) { header('Location: /item'); exit; }
    
    if (isset($_SESSION['user_id'])) {
      $this->cartModel->addItem($_SESSION['user_id'], $item_id, $qty);
    } else {
      $_SESSION['cart'][$item_id] = ($_SESSION['cart'][$item_id] ?? 0) + $qty;
    }
    
    header('Location: /cart');
  }
}
```

#### 4.2 OrderController
```php
<?php namespace App\Controllers;
use App\Models\OrderModel;
use App\Models\CartModel;
use App\Models\ItemModel;

class OrderController {
  private OrderModel $orderModel;
  private CartModel $cartModel;
  private ItemModel $itemModel;
  
  public function __construct() {
    $this->orderModel = new OrderModel();
    $this->cartModel = new CartModel();
    $this->itemModel = new ItemModel();
  }
  
  public function checkout(): void {
    if (!isset($_SESSION['user_id'])) {
      header('Location: /user/login');
      exit;
    }
    
    $cart = $this->cartModel->getCart($_SESSION['user_id']);
    if (empty($cart)) {
      header('Location: /cart');
      exit;
    }
    
    $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $address = htmlspecialchars($_POST['address'] ?? '');
      
      // Créer commande
      $order_id = $this->orderModel->create($_SESSION['user_id'], $address, $total);
      
      // Ajouter items
      foreach ($cart as $item) {
        $this->orderModel->addItem($order_id, $item['id'], $item['quantity'], $item['price']);
        $this->itemModel->updateStock($item['id'], -$item['quantity']);
      }
      
      // Vider panier
      $this->cartModel->clear($_SESSION['user_id']);
      
      header('Location: /order/confirmation?id=' . $order_id);
      exit;
    }
    
    require __DIR__ . '/../Views/order/checkout.php';
  }
  
  public function confirmation(): void {
    $order_id = $_GET['id'] ?? null;
    if (!$order_id) { header('Location: /'); exit; }
    
    $order = $this->orderModel->findById($order_id);
    $items = $this->orderModel->getItems($order_id);
    
    require __DIR__ . '/../Views/order/confirmation.php';
  }
}
```

#### 4.3 Vues Cart + Order [3h]

**cart/index.php**
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<section class="cart-page">
  <h1>Mon Panier</h1>
  
  <?php if (empty($cart)): ?>
    <p>Votre panier est vide</p>
    <a href="/item" class="btn">Continuer vos achats</a>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Produit</th>
          <th>Prix</th>
          <th>Quantité</th>
          <th>Sous-total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?>€</td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?>€</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    
    <div class="cart-summary">
      <p class="total">Total: <strong><?= number_format($total, 2) ?>€</strong></p>
      <a href="/order/checkout" class="btn">Passer la commande</a>
      <a href="/item" class="btn-secondary">Continuer</a>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

**order/checkout.php** - Simple form
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<section class="checkout">
  <h1>Passage de commande</h1>
  
  <div class="checkout-content">
    <aside class="order-summary">
      <h3>Résumé</h3>
      <table>
        <tr><td colspan="2"><strong>Articles</strong></td></tr>
        <?php foreach ($cart as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2) ?>€</td>
          </tr>
        <?php endforeach; ?>
        <tr><td colspan="2"><strong>Total: <?= number_format($total, 2) ?>€</strong></td></tr>
      </table>
    </aside>
    
    <form method="post" class="checkout-form">
      <h3>Adresse de livraison</h3>
      <label>Rue</label>
      <input type="text" name="street" required>
      
      <label>Code postal</label>
      <input type="text" name="postal" required>
      
      <label>Ville</label>
      <input type="text" name="city" required>
      
      <input type="hidden" name="address" id="address" value="">
      <button type="submit" class="btn">Confirmer la commande</button>
    </form>
  </div>
</section>

<script>
  document.querySelector('form').addEventListener('submit', function(e) {
    const street = document.querySelector('input[name="street"]').value;
    const postal = document.querySelector('input[name="postal"]').value;
    const city = document.querySelector('input[name="city"]').value;
    document.querySelector('#address').value = street + ', ' + postal + ' ' + city;
  });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

**order/confirmation.php**
```php
<?php require __DIR__ . '/../layout/header.php'; ?>

<section class="confirmation">
  <h1>✓ Commande confirmée!</h1>
  
  <p>Numéro de commande: <strong><?= $order['id'] ?></strong></p>
  
  <table>
    <thead><tr><th>Article</th><th>Quantité</th><th>Prix</th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><?= number_format($item['unit_price'] * $item['quantity'], 2) ?>€</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
  <p class="total">Total: <?= number_format($order['total'], 2) ?>€</p>
  
  <a href="/" class="btn">Continuer les achats</a>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
```

✅ **FIN JOUR 4** : Cart + Orders complete

---

## 🎯 RÉSULTAT FINAL = "BONNE BASE"

### ✅ Ce que tu as après 4 jours
- DB complète et propre
- 7 Models bien structurés et réutilisables
- 6 Controllers fonctionnels (User, Home, Item, Cart, Order)
- 10+ Vues professionnelles
- CSS responsive mobile-first
- Auth sécurisée (password_hash, htmlspecialchars)
- Panier persistant
- Commandes fonctionnelles

### ✅ Code de qualité
- Pattern MVC strict (comme gamekeeper)
- Requêtes préparées partout (sécurité)
- Typage PHP 8
- Facile à maintenir + étendre

### ❌ Ce qui manque (tu pourras faire après)
- Admin panel complet
- JavaScript AJAX
- Accessibilité poussée
- Tests unitaires
- Performance optimization
- Design polish

---

## 📋 CHECKLIST 4 JOURS

```
J1 - Database + Models
  ✓ DB complète (users, items, carts, orders, etc.)
  ✓ 30-50 items de test
  ✓ UserModel complet
  ✓ ItemModel avec findAllWithDetails
  ✓ CartModel, OrderModel, CategoryModel, etc.

J2 - Auth + Home
  ✓ UserController (register, login, logout)
  ✓ HomeController
  ✓ Header + Footer layouts
  ✓ Home view avec bestsellers
  ✓ CSS basique

J3 - Catalog
  ✓ ItemController (index, show)
  ✓ item/index.php avec filtres
  ✓ item/show.php avec fiche produit

J4 - Cart + Order
  ✓ CartController
  ✓ OrderController
  ✓ cart/index.php
  ✓ order/checkout.php
  ✓ order/confirmation.php
```

**C'est ça une "bonne base" - stable, sécurisée, extensible.** 💪

Prêt à commencer J1?
