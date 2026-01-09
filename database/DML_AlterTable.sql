USE thalassa_db;

-- PASO 1: Convertir a VARCHAR para quitar la restricción estricta del ENUM
ALTER TABLE dishes MODIFY COLUMN topic VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- PASO 2: "Planchar" los datos. Sobrescribimos cualquier variante de "Montaña" con la correcta.
-- El LIKE 'Monta%' capturará "Montana", "Montaña" (mal codificado), etc.
UPDATE dishes SET topic = 'Montaña' WHERE topic LIKE 'Monta%';

-- PASO 3: Reconstruir el ENUM con la codificación y valores correctos
ALTER TABLE dishes 
MODIFY COLUMN topic 
ENUM('Mar', 'Montaña', 'Vegetariano', 'Vegano', 'Otros') 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci 
DEFAULT 'Otros';


INSERT INTO discounts
(discount_code, percent, starts_at, ends_at, max_uses, is_active)
VALUES
('WELCOME10', 10.00, NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY), 1, TRUE),
('SPRING20',  20.00, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY),  5, TRUE),
('SUMMER15',  15.00, NOW(), DATE_ADD(NOW(), INTERVAL 90 DAY), 1, FALSE),
('BLACKFRI',  25.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 2, TRUE),
('FREESHIP',   5.00, NOW(), DATE_ADD(NOW(), INTERVAL 100 DAY), 2, FALSE),
('NEWYEAR',   20.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 1, TRUE),
('WEEKEND',   10.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 2, TRUE),
('STUDENT5',   5.00, NOW(), DATE_ADD(NOW(), INTERVAL 365 DAY), 2, TRUE),
('EXTRA30',   30.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 8, TRUE),
('OLD10',     10.00, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 1, FALSE);