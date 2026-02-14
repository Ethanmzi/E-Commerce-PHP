-- =====================================================
-- BASE DE DONNÉES E-COMMERCE PHP
-- Script de création des tables et données de test
-- =====================================================

-- Suppression des tables existantes (dans l'ordre des dépendances)
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- =====================================================
-- TABLE: users (utilisateurs et administrateurs)
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    postal_code VARCHAR(10) DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: categories (catégories de produits)
-- =====================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: products (produits)
-- =====================================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: product_images (images multiples par produit)
-- =====================================================
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cart (panier utilisateur)
-- =====================================================
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    session_id VARCHAR(255) DEFAULT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: orders (commandes)
-- =====================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_postal_code VARCHAR(10) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'card',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: order_items (détails des commandes / facture)
-- =====================================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INDEX pour optimiser les performances
-- =====================================================
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_featured ON products(is_featured);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_cart_session ON cart(session_id);

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- Utilisateur admin (mot de passe: admin123)
INSERT INTO users (email, password, firstname, lastname, role) VALUES
('admin@boutique.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Boutique', 'admin');

-- Utilisateurs de test (mot de passe: password123)
INSERT INTO users (email, password, firstname, lastname, phone, address, city, postal_code, role) VALUES
('jean.dupont@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', '0612345678', '12 Rue de la Paix', 'Paris', '75001', 'user'),
('marie.martin@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marie', 'Martin', '0698765432', '5 Avenue des Champs', 'Lyon', '69001', 'user'),
('lucas.bernard@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lucas', 'Bernard', '0654321098', '28 Boulevard Haussmann', 'Marseille', '13001', 'user');

-- Catégories
INSERT INTO categories (name, slug, description) VALUES
('Vêtements', 'vetements', 'T-shirts, pantalons, vestes et plus'),
('Chaussures', 'chaussures', 'Sneakers, boots, sandales'),
('Accessoires', 'accessoires', 'Sacs, ceintures, bijoux'),
('High-Tech', 'high-tech', 'Gadgets et électronique'),
('Maison', 'maison', 'Décoration et mobilier');

-- Produits
INSERT INTO products (category_id, name, slug, description, price, stock, image, is_featured) VALUES
(1, 'T-shirt Premium Coton Bio', 't-shirt-premium-coton-bio', 'T-shirt en coton biologique, coupe ajustée, disponible en plusieurs couleurs. Idéal pour un look décontracté et éco-responsable.', 29.99, 150, 'img_principal.png', 1),
(1, 'Jean Slim Bleu Délavé', 'jean-slim-bleu-delave', 'Jean slim stretch très confortable, coupe moderne. Parfait pour toutes les occasions.', 59.99, 75, 'img1.png', 1),
(2, 'Sneakers Urban Style', 'sneakers-urban-style', 'Baskets urbaines avec semelle confort. Design moderne et tendance.', 89.99, 50, 'img3.png', 1),
(3, 'Sac à Dos Voyage', 'sac-dos-voyage', 'Sac à dos spacieux avec compartiment laptop 15 pouces. Parfait pour le quotidien ou les voyages.', 49.99, 100, 'img4.png', 0),
(4, 'Écouteurs Bluetooth Pro', 'ecouteurs-bluetooth-pro', 'Écouteurs sans fil avec réduction de bruit active. Autonomie 24h avec le boîtier.', 79.99, 200, 'img1.png', 1),
(5, 'Lampe Design Scandinave', 'lampe-design-scandinave', 'Lampe de bureau design nordique, lumière LED ajustable. Parfaite pour votre espace de travail.', 39.99, 80, 'img3.png', 0),
(1, 'Veste Bomber Classic', 'veste-bomber-classic', 'Veste bomber légère, style aviateur. Doublure satin, finitions premium.', 89.99, 40, 'img4.png', 0),
(2, 'Boots Cuir Marron', 'boots-cuir-marron', 'Bottines en cuir véritable, semelle antidérapante. Élégance et confort au quotidien.', 129.99, 35, 'img_principal.png', 0);

-- Images multiples pour le premier produit (exemple)
INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES
(1, 'img_principal.png', 1, 1),
(1, 'img1.png', 0, 2),
(1, 'img3.png', 0, 3),
(1, 'img4.png', 0, 4);

-- Commande de test
INSERT INTO orders (user_id, order_number, total_amount, status, shipping_address, shipping_city, shipping_postal_code, payment_status) VALUES
(2, 'CMD-2026-00001', 139.97, 'delivered', '12 Rue de la Paix', 'Paris', '75001', 'paid'),
(3, 'CMD-2026-00002', 89.99, 'shipped', '5 Avenue des Champs', 'Lyon', '69001', 'paid');

-- Détails des commandes
INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal) VALUES
(1, 1, 'T-shirt Premium Coton Bio', 29.99, 2, 59.98),
(1, 3, 'Sneakers Urban Style', 79.99, 1, 79.99),
(2, 3, 'Sneakers Urban Style', 89.99, 1, 89.99);

-- Panier de test
INSERT INTO cart (user_id, product_id, quantity) VALUES
(4, 2, 1),
(4, 5, 2);
