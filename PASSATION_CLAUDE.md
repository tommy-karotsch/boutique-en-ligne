****# Passation — Projet boutique-en-ligne (RL.SHOP)

> Fichier de contexte pour reprendre le projet dans une nouvelle session Claude (autre PC).
> À lire en entier au démarrage.

---

## 1. Qui est l'utilisateur & comment travailler avec lui

- **Tommy KAROTSCH**, étudiant Bachelor 1ère année (Cannes), titre **RNCP 37273 — Développeur Web & Web Mobile Fullstack**.
- Ce projet est son **projet d'examen** (support de soutenance). Il **apprend à coder**.
- **RÈGLE ABSOLUE : ne JAMAIS écrire/modifier le code à sa place.** On le **guide**, on explique, et **c'est lui qui écrit**. Il doit maîtriser chaque ligne pour sa soutenance.
- Quand il dit **« corrige »**, ça veut dire **« vérifie ce que je viens de faire et dis-moi ce qui ne va pas »** (lire le fichier, pointer les erreurs avec le snippet corrigé à taper) — **pas** éditer soi-même.
- **Seule exception** : il dit explicitement « fais-le toi-même exceptionnellement » ou demande des ajustements de style/CSS précis (espacements, alignements). Dans ces cas-là on peut éditer directement.
- Pédagogie : expliquer le POURQUOI, ligne par ligne, avec des tableaux. Il pose beaucoup de « pourquoi ça marche comme ça ».
- Langue : **français**.

---

## 2. Stack & environnement

- **PHP 8** POO, architecture **MVC** maison, **MySQL** via **PDO**.
- **XAMPP** sous Windows. Projet dans `c:\xampp\htdocs\boutique-en-ligne`.
  - ⚠️ Le dossier physique s'appelle parfois `boutique-en-ligne` mais l'URL de base reste `/boutique-en-ligne/public/`. **Toutes les redirections et liens commencent par `/boutique-en-ligne/public/`** + `{controller}/{method}`.
- **Base de données MySQL** : `boutique-en-ligne` (via phpMyAdmin, root sans mot de passe — identifiants XAMPP par défaut, locaux).
- **Sass** compilé via l'extension VS Code **Live Sass Compiler** : `public/scss/style.scss` → `public/css/style.css`.
  - ⚠️ **Le watcher ne détecte PAS les modifications faites par Claude** (seulement les sauvegardes faites dans VS Code). Si Claude édite le `.scss`, recompiler avec : `npx --yes sass public/scss/style.scss public/css/style.css`. Sinon demander à Tommy de sauvegarder manuellement (Ctrl+S) dans VS Code.
  - Toujours rappeler **Ctrl+F5** (vide le cache) après une modif CSS.

---

## 3. Architecture du projet

```
app/
├── Router.php                  # transforme l'URL en appel Controller::method (gère les tirets : api-item → ApiItemController)
├── Controllers/                # 11 contrôleurs
│   ├── HomeController, ItemController, CartController, OrderController, UserController, AdminController
│   └── ApiItemController, ApiCategoryController, ApiColorController, ApiRarityController, ApiOrderController
├── Models/                     # 8 modèles
│   ├── Model.php               # classe ABSTRAITE parente (PDO, findAll, findById, findByName, delete, findAllWithItemCount)
│   ├── ItemModel, UserModel, OrderModel, CartModel, CategoryModel, RarityModel, ColorModel
└── Views/
    ├── layout/ (header.php, footer.php)
    ├── home/, item/, cart/, order/, user/, admin/
config/database.php             # classe Database (connexion PDO)
public/index.php                # point d'entrée + session_start
public/.htaccess                # réécriture URL → index.php?url=$1
public/scss/style.scss          # source Sass
public/css/style.css            # CSS compilé
public/js/catalogue.js          # Fetch API (consomme api-item)
```

- **Router** : `$_GET['url']` (ex: `item/index`) → `ItemController::index()`. Amélioré pour gérer les tirets (`api-item` → `ApiItemController`).
- **POO/PDO** : tous les modèles `extends Model`. `Model` contient la connexion PDO + méthodes communes. `{$this->table}` s'adapte par modèle. Requêtes préparées partout (`prepare` + `:param`).

---

## 4. Base de données (tables réelles vérifiées)

- `users` : id, username, email, password (bcrypt), role ('user'/'admin'), address, created_at
- `items` : id, name, description, price, stock, image, category_id (FK), rarity_id (FK), color_id (FK)
- `categories` : id, name
- `rarities` : id, name, **color_code** (varchar 7)  ← PAS `badge_color` (le CDC dit badge_color mais la vraie colonne est color_code)
- `colors` : id, name, **hex_code** (varchar 7)
- `orders` : id, user_id (FK), status (ENUM pending/shipped/delivered/cancelled), total, delivery_address, ordered_at, created_at
- `order_items` : id, order_id (FK), item_id (FK), quantity, unit_price
- `carts` : id, user_id (FK), created_at (DEFAULT CURRENT_TIMESTAMP)
- `cart_items` : id, cart_id (FK), item_id (FK), quantity

⚠️ **Contraintes de clés étrangères actives** : on ne peut pas supprimer une catégorie/rareté/couleur utilisée par un item, ni un item déjà commandé. C'est géré par try/catch + message flash (voir §5).

---

## 5. État du projet — CE QUI EST FAIT ✅

### Fonctionnel (back-end & logique)
- MVC, POO/PDO, Router (+ tirets), .htaccess
- Sécurité : bcrypt (password_hash/verify), requêtes préparées (anti-injection SQL), htmlspecialchars (anti-XSS), contrôle d'accès admin
- **Compte utilisateur** : inscription (validation + confirmation mot de passe), connexion, déconnexion, profil (modif pseudo/email/adresse/mot de passe + confirmation), **suppression compte RGPD** (`deleteAccount` en transaction), historique commandes
- **Catalogue** : filtres cumulables (catégorie/rareté/couleur) + tri (prix/rareté) via `ItemModel::filter()` (requête dynamique), fiche produit
- **Panier** : session + **persistant en BDD pour les connectés** (CartModel : getOrCreateCartId, getQuantities, save, clear ; merge session↔BDD au login ; persistCart() après chaque modif), contrôle de stock, modification quantités
- **Commande** : checkout + **récapitulatif détaillé** ($cartPreview) + confirmation ; vidage panier BDD après commande
- **Admin** : CRUD items (create/edit/delete), **suivi commandes** (filtre statut + changement statut), **tableau de bord** (nb commandes + nb items + produits récents), **gestion catégories/raretés/couleurs** (page /admin/catalog : ajout, suppression, édition inline du nom ET de la couleur via onchange auto-submit, compteur d'items par élément via `findAllWithItemCount`)
- **Gestion d'erreurs FK** : try/catch sur les suppressions bloquées → message flash via `$_SESSION['catalog_error']` / `$_SESSION['admin_error']` (lu puis unset dans le contrôleur, affiché dans la vue)
- **API REST complète** (JSON, GET/POST/PUT/DELETE) : 5 contrôleurs (items, categories, rarities, colors, orders). ApiItemController et ApiOrderController **sécurisés** (POST/PUT/DELETE réservés admin → 403 ; ApiOrder gère 401/403/405)
- **Fetch API** : `public/js/catalogue.js` (async/await, try/catch, génère les cartes) consomme `api-item` sur la page d'accueil

### Style Sass (mobile-first, BEM, charte RL)
- Variables : `$fond-sombre #1A1A1A`, `$fond-carte #242424`, `$bleu-electrique #44C8FF`, `$orange #F39C12`, `$blanc`, `$gris-texte #B0B0B0`, `$radius 8px`, `$espacement 16px`, `$tablette 768px`, `$desktop 1024px`
- `@mixin media-up($breakpoint)` (min-width, mobile-first) — réutilisé partout
- `color.adjust()` au lieu de `darken()` (déprécié) — `@use "sass:color"` en haut
- Composants stylés : header/nav, hero (dégradé, titre responsive 2rem→3.5rem), `.container` (max-width 1200px centré), cartes `.item-card` (badge rareté, image, prix, bouton +Panier), `.btn` (+ `--primary`, `--danger`, `--small`), formulaires (`.form`, `__group`, `__label`, `__input`, `__error`, `__success`), tableaux `.table`, footer 4 colonnes, fiche produit `.product`, dashboard, gestion catalogue (`.catalog-admin`)
- Accessibilité : labels `for`/`id` sur les formulaires, `alt` sur images

---

## 6. CE QU'IL RESTE À FAIRE ⏳

> Mis à jour le 2026-06-30. Méthode : on traite UNE tâche à la fois, Tommy code, Claude vérifie.

### ✅ Déjà fait dans cette session
- Hero accueil : **2 colonnes + stats dynamiques** (HomeController compte items/catégories/couleurs/raretés via `findAll()`, vue affiche les 4 `<span>`). `.hero` en grille responsive mobile-first.
- Header **responsive avec menu burger** : bouton `.nav-toggle` (aria-label + aria-expanded), `.nav` cachée mobile / `&--open` toggle, `public/js/header.js` (classList.toggle + sync aria), script chargé dans footer.php. `.nav-link` agrandi (cibles tactiles ~44px).
- **Lot prix → Crédits** : colonne `items.price` passée en `INT` (repart de zéro). Inputs prix `min=100 max=2500 step=1` (edit + create). Méthode **`ItemModel::update()` ajoutée** (manquait → fatal error corrigée). Affichage `€ → Crédits` partout (dernier `€` retiré de item/index.php ; panier en `CR`).

### ✅ Lot complet « refonte UX/design » — 11/11 TERMINÉ (session 2026-06-30)
Toute la liste fournie par Tommy est faite et vérifiée dans le code. Migrations BDD appliquées (`items.price` INT ; `orders.game_id` au lieu de `delivery_address`).

| # | Tâche | Détail technique |
|---|---|---|
| 1 | Catalogue : sidebar filtres à gauche | `.catalog` 2 colonnes (240px sidebar + 1fr), form dans `<aside class="catalog__filters">` (class `filters`, id `filters`), wrapper `.catalog__products` |
| 2 | Catalogue : filtrage en direct | `public/js/filters.js` (change → form.submit), bouton « Filtrer » retiré, « Réinitialiser » gardé. Script chargé dans item/index.php |
| 3 | Header : réordonner nav | Accueil · Catalogue · Bonjour [spacer] Admin · Mon profil · Se déconnecter · Panier (à droite). Spacer dupliqué if/else. « Tous les Items » → « Catalogue » |
| 4 | Accueil : vrai catalogue | Tri par rareté décroissante. `ItemModel::findTopByRarity(4)` + API `?top=1` (elseif dans ApiItemController GET) + `catalogue.js` URL `api-item?top=1` + section « Items rares » (#catalogue). NB : option « + commandés » (COUNT order_items) possible plus tard |
| 5 | Profil en 2 pages | `profile()` = résumé (pseudo, email, nbOrders via `count(OrderModel::findByUser)`) → profile.php. `editProfile()` = formulaire POST (infos+mdp, suppression compte) → edit-profile.php. `use OrderModel` ajouté |
| 6 | Panier : refonte design | État vide `.cart-empty` (+ bouton catalogue), état rempli `.cart` (miniatures, nom cliquable, qty soignée, `.cart__summary`). Unité « Crédits ». style= inline retiré |
| 7 | Livraison numérique | Colonne `delivery_address` → `game_id` (VARCHAR 100). Champ « Identifiant Rocket League (Epic) » au checkout, affiché dans confirm. OrderModel/OrderController màj |
| 8 | Admin : boutons Modifier/Supprimer | `.admin-row-actions` (flex alignés), btn--small |
| 9 | Admin : formulaire ajout item | BUGS corrigés : champ Prix (Crédits) AJOUTÉ (manquait !), Stock en double retiré, `$item['stock']` fautif supprimé, footer ajouté, labels for/id, wrapper .form |
| 10 | Admin : filtres suivi commande | `.order-filter` stylé, `.status-badge--{statut}` colorés, form statut aligné, Total en Crédits |

**Bugs préexistants corrigés au passage** : prix saisissable (input non borné + `step`), `ItemModel::update()` manquante (fatal error edit), formulaire create cassé (cf. #9).

**Bonus UX** : ajout panier sans quitter la page (`CartController::add` → `HTTP_REFERER`) ; fiche produit cliquable sur toutes les cartes (lien `item/show?id=` sur image+nom, item/index.php ET catalogue.js) ; image fiche bornée (`.product__image` max-width 400px, aspect-ratio 1/1).

**Checkout nettoyé** : `style=` inline retirés, `.checkout` (hint, errors, actions, total).

> ⚠️ Piège encodage rencontré : NE JAMAIS réécrire un .php via PowerShell `Set-Content -Encoding UTF8` → ça crée du double-encodage (`é`→`Ã©`). Toujours éditer via l'éditeur/outil Edit. Si corrompu : `git checkout` du fichier puis réappliquer les modifs.

### ⏳ Reste du CDC (non encore traité)
| Tâche | CDC | Note |
|---|---|---|
| **RGPD** : politique de confidentialité + bandeau cookies | 8.3 | liens footer (#) prêts à brancher |
| **Accessibilité RGAA** : finitions (alt, sémantique, clavier) | 4.4 | |
| **Simulation de paiement** (tunnel commande) | 2.1 | rapide |
| `darken()` déprécié ligne ~640 du .scss | — | remplacer par `color.adjust(... $lightness: -20%)` pour cohérence |

---

## 7. Préparation SOUTENANCE (slides Canva en cours)

Tommy prépare un diaporama Canva. On a établi que les captures de son CDC sont des **exemples génériques** → il faut les remplacer par SON vrai code. Slides déjà préparées (lui donner SON code + comment l'expliquer à l'oral) :
- **Responsive Sass** : mixin media-up + grille catalogue 1→2→4 colonnes + titre hero 2rem→3.5rem
- **Sass** : variables, mixin, imbrication `&:hover`/`&--primary`, opérations `$espacement * 1.5`, `color.adjust` (mentionner que c'est plus moderne que `darken`)
- **Fetch API** : son `catalogue.js` (async/await, fetch, try/catch, response.json, DOM)
- **API REST** : son `ApiItemController` (header JSON, switch REQUEST_METHOD, json_encode, php://input, + sécurité admin 403)
- **MVC** : son arborescence réelle (11 contrôleurs / 8 modèles / 7 dossiers vues) + flux Controller→Model→View + Router
- **POO/PDO** : héritage `Model`→`ItemModel` (`extends`, `abstract`, `{$this->table}`) + requêtes préparées (sécurité anti-injection)

Argument fort récurrent : **montrer SON code, pas l'exemple générique du CDC**. Points forts à mettre en avant : sécurité API (403 admin), cycle complet front↔back, héritage DRY, requêtes préparées.

---

## 8. Git

- Remote : `https://github.com/tommy-karotsch/boutique-en-ligne.git`, branche `main`.
- Commits seulement quand Tommy le demande. Messages en français, finir par `Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>`.
- `.gitignore` exclut : `claude-setup/`, `.claude`, fichiers de plan. **Ne pas committer `claude-setup/`**.
- `.vscode/settings.json` EST versionné (config Live Sass).
- Données de test : Tommy a nettoyé sa BDD (DELETE FROM order_items/cart_items/orders/carts/items dans l'ordre enfants→parents). Il recrée des items via INSERT multiple ou l'admin.

---

## 9. Premières actions recommandées pour la nouvelle session

1. Lire ce fichier + le CDC (PDF `karotsch-tommy-fullstack.pdf` si fourni).
2. Relire `public/scss/style.scss`, `app/Views/item/index.php`, `public/js/catalogue.js` pour l'état réel du design en cours.
3. Demander à Tommy où il veut reprendre (design accueil ? RGPD ? soutenance ?).
4. **Rester en mode GUIDE** : il code, on vérifie. « corrige » = « vérifie ».
