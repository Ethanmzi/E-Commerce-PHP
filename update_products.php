<?php
/**
 * Script de mise à jour des produits en sérums de beauté
 * Exécutez ce fichier une seule fois puis supprimez-le
 */

require_once 'includes/config.php';

$pdo = getDB();

// Nouveaux produits - Sérums de transformation
$serums = [
    [
        'name' => 'GlowUp 30 - Transformation Express',
        'description' => 'Votre glow up commence ici. Sérum concentré en acide hyaluronique et vitamine C pour une transformation visible en seulement 30 jours. Teint lumineux, pores resserrés, peau éclatante. Le premier pas vers votre nouvelle version.',
        'price' => 49.99,
        'is_featured' => 1
    ],
    [
        'name' => 'MorphoSkin 90 - Restructuration Totale',
        'description' => 'Programme intensif 3 mois. Formule avancée au rétinol et peptides qui restructure votre peau en profondeur. Réduction des rides de 60%, ovale redessiné, texture affinée. Le sérum qui change tout.',
        'price' => 129.99,
        'is_featured' => 1
    ],
    [
        'name' => 'UltraGlow 180 - Métamorphose Ultime',
        'description' => 'La transformation radicale en 6 mois. Notre formule la plus puissante avec 12 actifs premium pour une régénération cellulaire complète. Effet lifting naturel, rajeunissement visible de 10 ans. Devenez méconnaissable.',
        'price' => 249.99,
        'is_featured' => 1
    ],
    [
        'name' => 'FlashGlow - Éclat Immédiat',
        'description' => 'Le glow instantané. Sérum flash aux perles micronisées pour un effet "j\'ai dormi 12 heures" immédiat. Parfait avant une soirée ou un shooting. Votre peau brille, vous brillez.',
        'price' => 39.99,
        'is_featured' => 0
    ],
    [
        'name' => 'NightShift - Transformation Nocturne',
        'description' => 'Changez pendant votre sommeil. Formule nocturne aux cellules souches végétales qui travaille pendant que vous dormez. Réveillez-vous chaque jour un peu plus proche de votre meilleure version.',
        'price' => 79.99,
        'is_featured' => 0
    ],
    [
        'name' => 'FaceSculpt - Contour Redéfini',
        'description' => 'Sculptez votre visage naturellement. Sérum tenseur effet lifting qui redessine les contours et affine les traits. Pommettes plus hautes, mâchoire plus définie. Votre glow up facial commence maintenant.',
        'price' => 89.99,
        'is_featured' => 1
    ]
];

try {
    // Supprimer tous les anciens produits
    $pdo->exec("DELETE FROM products");
    $pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");
    
    // Insérer les nouveaux sérums
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, is_featured, is_active, created_at) 
                           VALUES (?, ?, ?, 100, ?, 1, NOW())");
    
    foreach ($serums as $serum) {
        $stmt->execute([
            $serum['name'],
            $serum['description'],
            $serum['price'],
            $serum['is_featured']
        ]);
    }
    
    echo "<h1 style='color: #30d158; font-family: Inter, sans-serif;'>✅ Produits mis à jour avec succès !</h1>";
    echo "<p style='font-family: Inter, sans-serif;'>" . count($serums) . " sérums ont été ajoutés.</p>";
    echo "<p style='font-family: Inter, sans-serif;'><a href='articles.php'>Voir le catalogue</a></p>";
    echo "<p style='color: #ff453a; font-family: Inter, sans-serif;'><strong>⚠️ Supprimez ce fichier après utilisation !</strong></p>";
    
} catch (PDOException $e) {
    echo "<h1 style='color: #ff453a;'>❌ Erreur</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
