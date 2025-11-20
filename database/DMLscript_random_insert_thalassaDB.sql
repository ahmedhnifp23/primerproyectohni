USE thalassa_db;

-- Variable Global: SET sql_safe_updates = 0;
-- Variable local de script: SET @nombre = 'Alice';

-- =====================================================
-- RESET EN ORDEN (por FKs)
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE order_line_ingredients;
TRUNCATE TABLE ingredient_allergens;
TRUNCATE TABLE dish_ingredients;
TRUNCATE TABLE order_lines;
TRUNCATE TABLE orders;
TRUNCATE TABLE allergens;
TRUNCATE TABLE ingredients;
TRUNCATE TABLE dishes;
TRUNCATE TABLE discounts;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;


-- -----------------------------------------------------
-- USERS (addresses = JSON ARRAY de objetos)
-- -----------------------------------------------------
INSERT INTO users
(first_name, last_name, email, username, password_hash, phone, addresses, birth_date, is_admin)
VALUES
('Alice','Miller','alice@example.com','alice','hash123','$44 1234 567 889',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Baker St','number','221B','floor',NULL,'city','London','postal_code','NW1 6XE','country','UK','is_default', TRUE),
   JSON_OBJECT('label','Work','street','Fleet St','number','120','floor','3','city','London','postal_code','EC4A 2BH','country','UK','is_default', FALSE)
 ), '1995-06-10', FALSE),

('Bob','Johnson','bob@example.com','bobby','hash123','$44 1245 678 900',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Deansgate','number','45','floor','2','city','Manchester','postal_code','M3 2BW','country','UK','is_default', TRUE)
 ), '1990-04-21', FALSE),

('Charlie','Smith','charlie@example.com','charlie','hash123','$44 1256 789 011',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Park St','number','18','floor',NULL,'city','Bristol','postal_code','BS1 5JG','country','UK','is_default', TRUE)
 ), '1998-11-15', FALSE),

('Diana','Evans','diana@example.com','diana','hash123','$44 1267 890 122',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Bold St','number','77','floor','1','city','Liverpool','postal_code','L1 4HF','country','UK','is_default', TRUE)
 ), '1992-02-05', FALSE),

('Ethan','Green','ethan@example.com','ethan','hash123','$44 1278 901 233',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Briggate','number','10','floor',NULL,'city','Leeds','postal_code','LS1 6ER','country','UK','is_default', TRUE)
 ), '1994-07-22', FALSE),

('Fiona','Turner','fiona@example.com','fiona','hash123','$44 1289 012 344',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','King''s Parade','number','5','floor','4','city','Cambridge','postal_code','CB2 1SJ','country','UK','is_default', TRUE)
 ), '1989-09-30', FALSE),

('George','Harris','george@example.com','george','hash123','$44 1290 123 455',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','High St','number','32','floor',NULL,'city','Oxford','postal_code','OX1 4BH','country','UK','is_default', TRUE)
 ), '1991-03-08', FALSE),

('Hannah','Baker','hannah@example.com','hannah','hash123','$44 1301 234 566',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Micklegate','number','9','floor','2','city','York','postal_code','YO1 6JX','country','UK','is_default', TRUE)
 ), '1997-10-25', FALSE),

('Ian','Clark','ian@example.com','ian','hash123','$44 1312 345 677',
 JSON_ARRAY(
   JSON_OBJECT('label','Home','street','Queen St','number','50','floor',NULL,'city','Cardiff','postal_code','CF10 2AF','country','UK','is_default', TRUE)
 ), '1987-12-01', FALSE),

('Admin','User','admin@thalassa.com','admin','hash_admin','$44 1323 456 788',
 JSON_ARRAY(
   JSON_OBJECT('label','HQ','street','Waterloo Rd','number','1','floor','5','city','London','postal_code','SE1 8SW','country','UK','is_default', TRUE)
 ), '1985-01-01', TRUE);

-- -----------------------------------------------------
-- DISCOUNTS (TINYINT PK; max_uses <= 255)
-- -----------------------------------------------------
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

-- -----------------------------------------------------
-- DISHES (images = ARRAY de objetos {path, alt, mime})
-- -----------------------------------------------------
INSERT INTO dishes
(dish_name, dish_description, base_price, images, available)
VALUES
('Grilled Salmon','Fresh Atlantic salmon with lemon butter sauce.',14.99,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/salmon1.jpg','alt','Grilled salmon fillet','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/salmon2.jpg','alt','Salmon with lemon','mime','image/jpeg')
 ), TRUE),

('Chicken Caesar Salad','Crisp lettuce, grilled chicken, Caesar dressing.',10.50,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/salad1.jpg','alt','Chicken Caesar salad','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/salad2.jpg','alt','Salad close-up','mime','image/jpeg')
 ), TRUE),

('Margherita Pizza','Classic tomato, mozzarella and basil.',9.99,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/pizza1.jpg','alt','Margherita pizza','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/pizza2.png','alt','Pizza slice','mime','image/png')
 ), TRUE),

('Spaghetti Carbonara','Creamy pasta with pancetta and parmesan.',12.99,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/carbonara1.jpg','alt','Spaghetti carbonara','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/carbonara2.jpg','alt','Carbonara plated','mime','image/jpeg')
 ), TRUE),

('Beef Burger','Juicy grilled beef burger with fries.',11.50,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/burger1.jpg','alt','Beef burger with fries','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/burger2.jpg','alt','Burger close-up','mime','image/jpeg')
 ), TRUE),

('Seafood Paella','Traditional Spanish rice with seafood mix.',16.50,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/paella1.jpg','alt','Seafood paella pan','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/paella2.jpg','alt','Paella serving','mime','image/jpeg')
 ), TRUE),

('Veggie Wrap','Tortilla wrap filled with grilled vegetables.',8.99,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/wrap1.jpg','alt','Veggie wrap','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/wrap2.jpg','alt','Wrap halves','mime','image/jpeg')
 ), TRUE),

('Tuna Tartare','Fresh tuna with avocado and soy dressing.',13.75,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/tuna1.jpg','alt','Tuna tartare','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/tuna2.jpg','alt','Tartare close-up','mime','image/jpeg')
 ), TRUE),

('Lobster Bisque','Rich lobster soup with cream and brandy.',15.25,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/bisque1.jpg','alt','Lobster bisque','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/bisque2.jpg','alt','Bisque with bread','mime','image/jpeg')
 ), TRUE),

('Chocolate Lava Cake','Warm chocolate cake with molten center.',6.50,
 JSON_ARRAY(
   JSON_OBJECT('path','/img/dishes/lava1.jpg','alt','Chocolate lava cake','mime','image/jpeg'),
   JSON_OBJECT('path','/img/dishes/lava2.png','alt','Lava cake cut open','mime','image/png')
 ), TRUE);

-- -----------------------------------------------------
-- INGREDIENTS
-- -----------------------------------------------------
INSERT INTO ingredients (ingredient_name) VALUES
('Salmon'),('Lemon'),('Chicken'),('Lettuce'),
('Tomato'),('Mozzarella'),('Basil'),('Pasta'),
('Parmesan'),('Beef');

-- -----------------------------------------------------
-- ALLERGENS (icon = ARRAY)
-- -----------------------------------------------------
INSERT INTO allergens (allergen_name, icon) VALUES
('Gluten',     JSON_ARRAY('icon_gluten.svg')),
('Eggs',       JSON_ARRAY('icon_eggs.svg')),
('Fish',       JSON_ARRAY('icon_fish.svg')),
('Milk',       JSON_ARRAY('icon_milk.svg')),
('Peanuts',    JSON_ARRAY('icon_peanuts.svg')),
('Soy',        JSON_ARRAY('icon_soy.svg')),
('Shellfish',  JSON_ARRAY('icon_shellfish.svg')),
('Sesame',     JSON_ARRAY('icon_sesame.svg')),
('Mustard',    JSON_ARRAY('icon_mustard.svg')),
('Tree nuts',  JSON_ARRAY('icon_treenuts.svg'));

-- -----------------------------------------------------
-- DISH_INGREDIENTS (receta base; ingredient_price NOT NULL)
-- -----------------------------------------------------
INSERT INTO dish_ingredients
(dish_id, ingredient_id, amount, is_default, ingredient_price)
VALUES
(1,1,200, TRUE, 2.80),  -- Salmon
(1,2,  5, TRUE, 0.05),  -- Lemon
(2,3,150, TRUE, 1.80),  -- Chicken
(2,4, 50, TRUE, 0.40),  -- Lettuce
(3,5, 80, TRUE, 0.30),  -- Tomato
(3,6, 60, TRUE, 0.90),  -- Mozzarella
(3,7,  3, TRUE, 0.05),  -- Basil
(4,8,120, TRUE, 0.70),  -- Pasta
(4,9, 20, TRUE, 0.60),  -- Parmesan
(5,10,180, TRUE, 2.20); -- Beef

-- -----------------------------------------------------
-- INGREDIENT_ALLERGENS (N:M)
-- -----------------------------------------------------
INSERT INTO ingredient_allergens (ingredient_id, allergen_id) VALUES
(1, 3),  -- Salmon -> Fish
(2, 9),  -- Lemon -> Mustard (p.ej. trazas en aderezos)
(3, 2),  -- Chicken -> Eggs (rebozados/marinados)
(4, 1),  -- Lettuce -> Gluten (trazas cruzadas)
(5, 1),
(6, 4),  -- Mozzarella -> Milk
(7, 1),
(8, 1),
(9, 4),  -- Parmesan -> Milk
(10,1);

-- -----------------------------------------------------
-- ORDERS (order_status: 1=paid, 2=paid and delivered)
-- -----------------------------------------------------
INSERT INTO orders
(user_id, discount_id, delivery_addr, ordered_at, total_amount, order_status, notes)
VALUES
(1, 1,  'London, UK',    NOW(), 29.99, 1, 'Deliver after 6pm'),
(2, 2,  'Manchester, UK',NOW(), 18.49, 1, NULL),
(3, NULL,'Bristol, UK',  NOW(), 21.00, 1, 'No onions please'),
(4, 3,  'Liverpool, UK', NOW(), 32.00, 2, NULL),
(5, 4,  'Leeds, UK',     NOW(), 19.50, 2, 'Leave at the door'),
(6, NULL,'Cambridge, UK',NOW(), 27.25, 1, NULL),
(7, 5,  'Oxford, UK',    NOW(), 33.10, 2, 'Extra napkins'),
(8, NULL,'York, UK',     NOW(), 16.70, 1, 'Low salt please'),
(9, 7,  'Cardiff, UK',   NOW(), 40.00, 2, NULL),
(10,NULL,'London, UK',   NOW(), 15.25, 1, NULL);

-- -----------------------------------------------------
-- ORDER_LINES (sin cantidad en tu DDL; usamos unit_price histórico)
-- -----------------------------------------------------
INSERT INTO order_lines (order_id, dish_id, unit_price, notes) VALUES
(1, 1, 14.99, 'no sides'),
(1, 3,  9.99, NULL),
(2, 4, 12.99, 'extra crispy'),
(3, 2, 10.50, 'no croutons'),
(4, 6, 16.50, NULL),
(5, 5, 11.50, 'well done'),
(6, 8, 13.75, NULL),
(7, 9, 15.25, 'two bowls'),
(8,10,  6.50, 'share'),
(9, 7,  8.99, 'extra lettuce');

-- -----------------------------------------------------
-- ORDER_LINE_INGREDIENTS (personalizaciones por línea)
-- -----------------------------------------------------
INSERT INTO order_line_ingredients
(order_line_id, ingredient_id, extra_action, extra_amount, extra_price)
VALUES
(1, 2, 'add',    10, 0.50),  -- add Lemon on line 1
(2, 7, 'remove', NULL, NULL),
(3, 9, 'add',     5, 0.75),
(4, 4, 'remove', NULL, NULL),
(5,10, 'add',    20, 1.25),
(6, 1, 'remove', NULL, NULL),
(7, 8, 'add',    10, 1.00),
(8, 6, 'add',    15, 0.80),
(9, 5, 'add',    10, 0.50),
(10,2, 'remove', NULL, NULL);