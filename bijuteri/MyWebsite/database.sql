CREATE DATABASE IF NOT EXISTS bijouterie_db;
USE bijouterie_db;

CREATE TABLE IF NOT EXISTS earrings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    material VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bracelets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    material VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data for earrings
INSERT INTO earrings (name, price, image, category, material, description) VALUES
('Pırlantalı Küpe', 8999.00, 'images/diamond-earring.jpg', 'diamond', 'gold', 'Altın pırlantalı küpe'),
('İnci Küpe', 6499.00, 'images/pearl-earring.jpg', 'pearl', 'silver', 'Gümüş inci küpe'),
('Moda Küpe', 2999.00, 'images/fashion-earring.jpg', 'fashion', 'silver', 'Gümüş moda küpe'),
('Altın Pırlantalı Küpe', 12999.00, 'images/gold-diamond-earring.jpg', 'diamond', 'gold', 'Altın pırlantalı küpe'),
('Gümüş Moda Küpe', 1999.00, 'images/silver-fashion-earring.jpg', 'fashion', 'silver', 'Gümüş moda küpe'),
('Platin İnci Küpe', 15999.00, 'images/platinum-pearl-earring.jpg', 'pearl', 'platinum', 'Platin inci küpe');

-- Sample data for bracelets
INSERT INTO bracelets (name, price, image, category, material, description) VALUES
('Pırlantalı Bileklik', 14999.00, 'images/diamond-bracelet.jpg', 'diamond', 'gold', 'Altın pırlantalı bileklik'),
('Charm Bileklik', 9999.00, 'images/charm-bracelet.jpg', 'charm', 'silver', 'Gümüş charm bileklik'),
('Moda Bileklik', 3999.00, 'images/fashion-bracelet.jpg', 'fashion', 'silver', 'Gümüş moda bileklik'),
('Altın Pırlantalı Bileklik', 19999.00, 'images/gold-diamond-bracelet.jpg', 'diamond', 'gold', 'Altın pırlantalı bileklik'),
('Gümüş Moda Bileklik', 2999.00, 'images/silver-fashion-bracelet.jpg', 'fashion', 'silver', 'Gümüş moda bileklik'),
('Platin Charm Bileklik', 24999.00, 'images/platinum-charm-bracelet.jpg', 'charm', 'platinum', 'Platin charm bileklik'); 