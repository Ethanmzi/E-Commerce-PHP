<?php
/**
 * Script d'installation de la base de données
 * Accède à http://localhost/E-Commerce-PHP/install.php
 */

echo "<h1>Installation de la base de données</h1>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:auto;} 
.success{color:green;background:#e8f5e9;padding:10px;border-radius:5px;margin:10px 0;} 
.error{color:red;background:#ffebee;padding:10px;border-radius:5px;margin:10px 0;}
.info{color:#1565c0;background:#e3f2fd;padding:10px;border-radius:5px;margin:10px 0;}
pre{background:#f5f5f5;padding:15px;overflow:auto;border-radius:5px;}</style>";

// Connexion MySQL sans base de données spécifique
try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<p class='success'>✓ Connexion MySQL réussie</p>";
} catch (PDOException $e) {
    die("<p class='error'>✗ Erreur MySQL: " . $e->getMessage() . "</p><p>Vérifie que MySQL est démarré dans XAMPP</p>");
}

// Créer la base de données si elle n'existe pas
$pdo->exec("CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "<p class='success'>✓ Base de données 'ecommerce_db' créée/vérifiée</p>";

// Utiliser cette base
$pdo->exec("USE ecommerce_db");

// Lire le fichier SQL
$sqlFile = __DIR__ . '/database.sql';
if (!file_exists($sqlFile)) {
    die("<p class='error'>✗ Fichier database.sql introuvable</p>");
}

$sql = file_get_contents($sqlFile);
echo "<p class='info'>Fichier SQL chargé (" . strlen($sql) . " caractères)</p>";

// Exécuter les requêtes SQL
try {
    // Séparer les requêtes et les exécuter une par une
    $pdo->exec($sql);
    echo "<p class='success'>✓ Tables créées avec succès!</p>";
} catch (PDOException $e) {
    // Si erreur, essayer requête par requête
    echo "<p class='info'>Exécution requête par requête...</p>";
    
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    $success = 0;
    $errors = [];
    
    foreach ($queries as $query) {
        if (empty($query) || strpos($query, '--') === 0) continue;
        try {
            $pdo->exec($query);
            $success++;
        } catch (PDOException $e2) {
            $errors[] = substr($query, 0, 50) . "... : " . $e2->getMessage();
        }
    }
    
    echo "<p class='success'>✓ $success requêtes exécutées</p>";
    if (!empty($errors)) {
        echo "<p class='error'>Quelques erreurs (normal si tables existaient déjà):</p>";
        echo "<pre>" . implode("\n", array_slice($errors, 0, 5)) . "</pre>";
    }
}

// Vérifier les tables
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "<h2>Tables créées (" . count($tables) . ")</h2>";
echo "<ul>";
foreach ($tables as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    echo "<li><strong>$table</strong> - $count enregistrements</li>";
}
echo "</ul>";

// Vérifier l'admin
$admin = $pdo->query("SELECT email, firstname, lastname FROM users WHERE role = 'admin' LIMIT 1")->fetch();
if ($admin) {
    echo "<h2>Compte Admin</h2>";
    echo "<p class='success'>Email: <strong>{$admin['email']}</strong><br>Mot de passe: <strong>password</strong></p>";
}

echo "<hr>";
echo "<h2>Installation terminée!</h2>";
echo "<p><a href='index.php' style='padding:10px 20px;background:#1976d2;color:white;text-decoration:none;border-radius:5px;'>Aller sur le site</a> ";
echo "<a href='admin/login.php' style='padding:10px 20px;background:#388e3c;color:white;text-decoration:none;border-radius:5px;margin-left:10px;'>Admin Login</a></p>";

echo "<p class='info'><strong>Tu peux supprimer ce fichier install.php après l'installation.</strong></p>";
