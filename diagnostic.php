<?php
/**
 * Script de test et diagnostic
 * Accède à cette page pour vérifier la configuration
 */

echo "<h1>Diagnostic E-Commerce</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// 1. Test PHP
echo "<h2>1. PHP</h2>";
echo "<p class='ok'>✓ PHP " . phpversion() . " fonctionne</p>";

// 2. Test Session
echo "<h2>2. Sessions</h2>";
session_start();
$_SESSION['test'] = 'ok';
if (isset($_SESSION['test']) && $_SESSION['test'] === 'ok') {
    echo "<p class='ok'>✓ Sessions fonctionnent</p>";
    echo "<p>Session ID: " . session_id() . "</p>";
} else {
    echo "<p class='error'>✗ Problème avec les sessions</p>";
}

// 3. Test fichier config
echo "<h2>3. Fichier config.php</h2>";
$configPath = __DIR__ . '/includes/config.php';
if (file_exists($configPath)) {
    echo "<p class='ok'>✓ config.php existe</p>";
} else {
    echo "<p class='error'>✗ config.php introuvable à: $configPath</p>";
}

// 4. Test connexion base de données
echo "<h2>4. Base de données</h2>";
try {
    $dsn = "mysql:host=localhost;charset=utf8mb4";
    $pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "<p class='ok'>✓ Connexion MySQL OK</p>";
    
    // Vérifier si la base ecommerce_db existe
    $result = $pdo->query("SHOW DATABASES LIKE 'ecommerce_db'")->fetch();
    if ($result) {
        echo "<p class='ok'>✓ Base 'ecommerce_db' existe</p>";
        
        // Vérifier les tables
        $pdo->exec("USE ecommerce_db");
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>Tables trouvées: " . implode(', ', $tables) . "</p>";
        
        if (count($tables) >= 6) {
            echo "<p class='ok'>✓ Tables créées (" . count($tables) . " tables)</p>";
            
            // Vérifier l'admin
            $admin = $pdo->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1")->fetch();
            if ($admin) {
                echo "<p class='ok'>✓ Compte admin existe: " . $admin['email'] . "</p>";
            } else {
                echo "<p class='warning'>⚠ Aucun compte admin trouvé</p>";
            }
            
            // Vérifier les produits
            $prodCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
            echo "<p>Produits en base: $prodCount</p>";
            
        } else {
            echo "<p class='error'>✗ Tables manquantes. Importe database.sql</p>";
        }
    } else {
        echo "<p class='error'>✗ Base 'ecommerce_db' n'existe pas!</p>";
        echo "<h3>Pour créer la base :</h3>";
        echo "<ol>";
        echo "<li>Ouvre phpMyAdmin (http://localhost/phpmyadmin)</li>";
        echo "<li>Clique sur 'Nouvelle base de données'</li>";
        echo "<li>Nom: <strong>ecommerce_db</strong></li>";
        echo "<li>Clique 'Créer'</li>";
        echo "<li>Sélectionne la base ecommerce_db</li>";
        echo "<li>Va dans l'onglet 'Importer'</li>";
        echo "<li>Choisis le fichier <strong>database.sql</strong></li>";
        echo "<li>Clique 'Exécuter'</li>";
        echo "</ol>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>✗ Erreur MySQL: " . $e->getMessage() . "</p>";
    echo "<p class='warning'>Vérifie que XAMPP/WAMP MySQL est démarré</p>";
}

// 5. Test du dossier uploads
echo "<h2>5. Dossier uploads</h2>";
$uploadPath = __DIR__ . '/assets/img/uploads/';
if (is_dir($uploadPath)) {
    echo "<p class='ok'>✓ Dossier uploads existe</p>";
    if (is_writable($uploadPath)) {
        echo "<p class='ok'>✓ Dossier uploads inscriptible</p>";
    } else {
        echo "<p class='error'>✗ Dossier uploads non inscriptible</p>";
    }
} else {
    echo "<p class='error'>✗ Dossier uploads n'existe pas</p>";
}

// 6. Résumé
echo "<hr><h2>Résumé</h2>";
echo "<p><strong>Si la base n'existe pas</strong>, crée-la dans phpMyAdmin et importe database.sql</p>";
echo "<p><a href='index.php'>Retour au site</a> | <a href='admin/login.php'>Admin Login</a></p>";
