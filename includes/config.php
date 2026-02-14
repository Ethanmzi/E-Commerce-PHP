<?php
/**
 * Configuration principale de l'application
 * Connexion PDO + Fonctions utilitaires
 */

// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// CONFIGURATION BASE DE DONNÉES
// =====================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// =====================================================
// CONFIGURATION GÉNÉRALE
// =====================================================
define('SITE_NAME', 'MaBoutique');
define('SITE_URL', 'http://localhost/E-Commerce-PHP');
define('UPLOAD_PATH', __DIR__ . '/../assets/img/uploads/');
define('UPLOAD_URL', 'assets/img/uploads/');

// =====================================================
// CONNEXION PDO (Singleton)
// =====================================================
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// =====================================================
// FONCTIONS D'AUTHENTIFICATION
// =====================================================

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur est admin
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Récupère l'ID de l'utilisateur connecté
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Récupère les infos de l'utilisateur connecté
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([getUserId()]);
    return $stmt->fetch();
}

/**
 * Connecte un utilisateur
 */
function loginUser($email, $password) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    
    return false;
}

/**
 * Déconnecte l'utilisateur
 */
function logoutUser() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Inscription d'un nouvel utilisateur
 */
function registerUser($data) {
    $pdo = getDB();
    
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
    }
    
    // Hash du mot de passe
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insertion
    $stmt = $pdo->prepare("
        INSERT INTO users (email, password, firstname, lastname, phone) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    try {
        $stmt->execute([
            $data['email'],
            $hashedPassword,
            $data['firstname'],
            $data['lastname'],
            $data['phone'] ?? null
        ]);
        return ['success' => true, 'message' => 'Inscription réussie !', 'user_id' => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
    }
}

// =====================================================
// FONCTIONS DE SÉCURITÉ
// =====================================================

/**
 * Nettoie une entrée utilisateur
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Protège une page admin
 */
function requireAdmin($redirectUrl = '../connexion.php') {
    if (!isAdmin()) {
        redirect($redirectUrl, 'Accès réservé aux administrateurs.', 'danger');
    }
}

/**
 * Protège une page utilisateur connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('connexion.php', 'Veuillez vous connecter.', 'warning');
    }
}

// =====================================================
// FONCTIONS UTILITAIRES
// =====================================================

/**
 * Redirection avec message flash
 */
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit;
}

/**
 * Affiche et efface le message flash
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($message) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
    return '';
}

/**
 * Génère un slug à partir d'une chaîne
 */
function generateSlug($string) {
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $slug = preg_replace('/[^a-zA-Z0-9\s]/', '', $slug);
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[\s]+/', '-', $slug);
    return $slug;
}

/**
 * Formate un prix
 */
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Génère un numéro de commande unique
 */
function generateOrderNumber() {
    return 'CMD-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

// =====================================================
// FONCTIONS PRODUITS
// =====================================================

/**
 * Récupère tous les produits actifs
 */
function getProducts($limit = null, $featured = false) {
    $pdo = getDB();
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = 1";
    
    if ($featured) {
        $sql .= " AND p.is_featured = 1";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Récupère un produit par ID ou slug
 */
function getProduct($identifier) {
    $pdo = getDB();
    $field = is_numeric($identifier) ? 'id' : 'slug';
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                           FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           WHERE p.$field = ?");
    $stmt->execute([$identifier]);
    return $stmt->fetch();
}

/**
 * Récupère les images d'un produit
 */
function getProductImages($productId) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}

/**
 * Récupère toutes les catégories
 */
function getCategories() {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    return $stmt->fetchAll();
}

// =====================================================
// FONCTIONS PANIER
// =====================================================

/**
 * Ajoute un produit au panier
 */
function addToCart($productId, $quantity = 1) {
    $pdo = getDB();
    $userId = getUserId();
    $sessionId = session_id();
    
    // Vérifier si le produit existe et est en stock
    $product = getProduct($productId);
    if (!$product || $product['stock'] < $quantity) {
        return false;
    }
    
    // Vérifier si déjà dans le panier
    if ($userId) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$sessionId, $productId]);
    }
    
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Mettre à jour la quantité
        $newQty = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQty, $existing['id']]);
    } else {
        // Ajouter au panier
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $sessionId, $productId, $quantity]);
    }
    
    return true;
}

/**
 * Récupère le panier
 */
function getCart() {
    $pdo = getDB();
    $userId = getUserId();
    $sessionId = session_id();
    
    if ($userId) {
        $stmt = $pdo->prepare("
            SELECT c.*, p.name, p.price, p.image, p.stock 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->prepare("
            SELECT c.*, p.name, p.price, p.image, p.stock 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.session_id = ?
        ");
        $stmt->execute([$sessionId]);
    }
    
    return $stmt->fetchAll();
}

/**
 * Compte les articles dans le panier
 */
function getCartCount() {
    $pdo = getDB();
    $userId = getUserId();
    $sessionId = session_id();
    
    if ($userId) {
        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE session_id = ?");
        $stmt->execute([$sessionId]);
    }
    
    return (int)$stmt->fetchColumn();
}

/**
 * Calcule le total du panier
 */
function getCartTotal() {
    $cart = getCart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
