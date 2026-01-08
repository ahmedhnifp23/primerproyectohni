CREATE TABLE IF NOT EXISTS logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action ENUM('Create', 'Update', 'Delete') NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    done_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cambiamos el delimitador para poder escribir los bloques de los triggers
DELIMITER $$

-- =========================================================
-- TRIGGERS PARA LA TABLA: ORDERS
-- =========================================================

-- 2. Trigger para CREAR (INSERT) en ORDERS
DROP TRIGGER IF EXISTS orders_after_insert$$
CREATE TRIGGER orders_after_insert 
AFTER INSERT ON orders 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Create', 'orders');
END$$

-- 3. Trigger para EDITAR (UPDATE) en ORDERS
DROP TRIGGER IF EXISTS orders_after_update$$
CREATE TRIGGER orders_after_update 
AFTER UPDATE ON orders 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Update', 'orders');
END$$

-- 4. Trigger para ELIMINAR (DELETE) en ORDERS
DROP TRIGGER IF EXISTS orders_after_delete$$
CREATE TRIGGER orders_after_delete 
AFTER DELETE ON orders 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Delete', 'orders');
END$$

-- =========================================================
-- TRIGGERS PARA LA TABLA: DISHES
-- =========================================================

-- 5. Trigger para CREAR (INSERT) en DISHES
DROP TRIGGER IF EXISTS dishes_after_insert$$
CREATE TRIGGER dishes_after_insert 
AFTER INSERT ON dishes 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Create', 'dishes');
END$$

-- 6. Trigger para EDITAR (UPDATE) en DISHES
DROP TRIGGER IF EXISTS dishes_after_update$$
CREATE TRIGGER dishes_after_update 
AFTER UPDATE ON dishes 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Update', 'dishes');
END$$

-- 7. Trigger para ELIMINAR (DELETE) en DISHES
DROP TRIGGER IF EXISTS dishes_after_delete$$
CREATE TRIGGER dishes_after_delete 
AFTER DELETE ON dishes 
FOR EACH ROW 
BEGIN
    INSERT INTO logs (user_id, action, table_name)
    VALUES (@current_user_id, 'Delete', 'dishes');
END$$

-- Restauramos el delimitador normal
DELIMITER ;