-- Base de données pour Galerie Oselo
CREATE DATABASE IF NOT EXISTS galerie_oselo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE galerie_oselo;

-- Table des entrepôts
CREATE TABLE IF NOT EXISTS warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des œuvres
CREATE TABLE IF NOT EXISTS artworks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    artist VARCHAR(255) NOT NULL,
    width INT NOT NULL,
    height INT NOT NULL,
    warehouse_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE SET NULL
);

-- Données de test
INSERT INTO warehouses (name, address) VALUES
('Main Storage', '15 Rue de la Paix, Paris'),
('Left Bank Depot', '45 Boulevard Saint-Germain, Paris'),
('Secure Storage', '8 Avenue Montaigne, Paris');

INSERT INTO artworks (title, year, artist, width, height, warehouse_id) VALUES
('Mona Lisa (Reproduction)', 2018, 'Louvre Workshop', 77, 53, 1),
('Starry Night', 2019, 'Vincent Dupont', 120, 90, 1),
('Abstract #5', 2020, 'Sophie Martin', 100, 100, 2),
('Autumn Landscape', 2017, 'Jean Dubois', 80, 60, 2),
('Woman Portrait', 2021, 'Marie Lambert', 50, 70, 3),
('Still Life with Fruits', 2016, 'Pierre Moreau', 60, 40, 3),
('Geometric Composition', 2022, 'Lucie Bernard', 90, 90, NULL),
('Sunset', 2020, 'Thomas Petit', 100, 70, NULL);

