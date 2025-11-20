

CREATE DATABASE IF NOT EXISTS thalassa_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci; -- Cuando la cree en mysql 8.0 usar "utf8mb4_0900_ai_ci;"
USE thalassa_db;

SET NAMES utf8mb4;

-- FALTA CAMBIAR EL ORDEN PARA QUE NO SALTE NINGUN ERROR

CREATE TABLE users (
  user_id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name      VARCHAR(80)       NOT NULL,
  last_name       VARCHAR(120)      NULL,
  email           VARCHAR(120)      NOT NULL,
  username        VARCHAR(60)       NOT NULL,
  password_hash   VARCHAR(255)      NOT NULL,
  phone           VARCHAR(30)       NULL,
  addresses       JSON              NULL,  
  birth_date      DATE              NULL,
  registered_at   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_admin        BOOLEAN           NOT NULL DEFAULT FALSE,

  CONSTRAINT uq_users_email    UNIQUE (email),
  CONSTRAINT uq_users_username UNIQUE (username),


  CONSTRAINT ck_users_addresses_is_array
    CHECK (
      addresses IS NULL
      OR (JSON_VALID(addresses) AND JSON_TYPE(addresses) = 'ARRAY')
    )
) ENGINE=InnoDB;



CREATE TABLE discounts (
  discount_id     TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  discount_code   VARCHAR(50)   NOT NULL,
  percent         DECIMAL(5,2)  NOT NULL,             
  starts_at       DATETIME      NOT NULL,
  ends_at         DATETIME      NULL,
  max_uses        TINYINT       NOT NULL DEFAULT 0,     
  is_active       BOOLEAN       NOT NULL DEFAULT TRUE,

  CONSTRAINT uq_discounts_code UNIQUE (discount_code),
  CONSTRAINT ck_discounts_pct  CHECK (percent >= 0 AND percent <= 100),
  CONSTRAINT ck_discounts_mx   CHECK (max_uses >= 0)
) ENGINE=InnoDB;

CREATE TABLE dishes (
  dish_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dish_name        VARCHAR(120)  NOT NULL,
  dish_description TEXT          NOT NULL,
  base_price  DECIMAL(10,2) NOT NULL, -- Taxes included.
  images      JSON          NOT NULL, -- JSON that contain array with values path, alt and mime.
  available   BOOLEAN       NOT NULL DEFAULT true,
  category   ENUM('Ensaladas','Entrantes', 'Principales', 'Postres', 'Bebidas') NOT NULL,

  CONSTRAINT ck_dishes_images_is_array CHECK (JSON_VALID(images) AND JSON_TYPE(images) = 'ARRAY')
) ENGINE=InnoDB;

CREATE TABLE ingredients (
  ingredient_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ingredient_name          VARCHAR(120) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE allergens (
  allergen_id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  allergen_name        VARCHAR(80)  NOT NULL,
  icon        JSON      NOT NULL,

  CONSTRAINT ck_allergens_icones_array CHECK (JSON_VALID(icon) AND JSON_TYPE(icon) = 'ARRAY')

) ENGINE=InnoDB;


CREATE TABLE orders (
  order_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED NOT NULL,
  discount_id    TINYINT UNSIGNED NULL,                
  delivery_addr  VARCHAR(255)   NOT NULL,                 
  ordered_at     DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_amount   DECIMAL(10,2)  NOT NULL, -- Taxes included
  order_status   TINYINT        NOT NULL,     -- 1= paid, 2=paid and delivered
  rating         TINYINT UNSIGNED NULL,          
  notes          VARCHAR(255)   NULL,

  CONSTRAINT fk_orders_user     FOREIGN KEY (user_id)     REFERENCES users(user_id),
  CONSTRAINT fk_orders_discount FOREIGN KEY (discount_id) REFERENCES discounts(discount_id),
  CONSTRAINT ck_order_status    CHECK (order_status IN (1,2)),
  CONSTRAINT ck_orders_rating   CHECK (rating IS NULL OR (rating BETWEEN 1 AND 5))
) ENGINE=InnoDB;


CREATE TABLE order_lines (
  order_line_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id       INT UNSIGNED NOT NULL,
  dish_id        INT UNSIGNED NOT NULL,
  unit_price     DECIMAL(10,2)  NOT NULL,
  notes          VARCHAR(255)   NULL,

  CONSTRAINT fk_ol_order  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  CONSTRAINT fk_ol_dish   FOREIGN KEY (dish_id)  REFERENCES dishes(dish_id)
) ENGINE=InnoDB;


CREATE TABLE dish_ingredients (
  dish_id        INT UNSIGNED NOT NULL,
  ingredient_id  INT UNSIGNED NOT NULL,
  amount         DECIMAL(8,3)    NULL,      -- gramos
  is_default     BOOLEAN         NOT NULL DEFAULT TRUE, -- Default false significa no lo lleva de base y solo es para añadir como extra.
  ingredient_price          DECIMAL(10,2)   NOT NULL, -- precio del ingrediente

  PRIMARY KEY (dish_id, ingredient_id),
  CONSTRAINT fk_di_dish       FOREIGN KEY (dish_id)       REFERENCES dishes(dish_id),
  CONSTRAINT fk_di_ingredient FOREIGN KEY (ingredient_id)  REFERENCES ingredients(ingredient_id)
) ENGINE=InnoDB;


CREATE TABLE ingredient_allergens (
  ingredient_id INT UNSIGNED NOT NULL,
  allergen_id   TINYINT UNSIGNED NOT NULL,

  PRIMARY KEY (ingredient_id, allergen_id),
  CONSTRAINT fk_ia_ingredient FOREIGN KEY (ingredient_id) REFERENCES ingredients(ingredient_id),
  CONSTRAINT fk_ia_allergen   FOREIGN KEY (allergen_id)   REFERENCES allergens(allergen_id)
) ENGINE=InnoDB;


CREATE TABLE order_line_ingredients (
  order_line_id  BIGINT UNSIGNED NOT NULL,
  ingredient_id  INT UNSIGNED NOT NULL,
  extra_action   ENUM('add','remove') NOT NULL,
  extra_amount   DECIMAL(8,3)   NULL, -- grams
  extra_price    DECIMAL(10,2)  NULL, -- Se aplica el mismo precio de la tabla dish_ingredients

  PRIMARY KEY (order_line_id, ingredient_id),
  CONSTRAINT fk_oli_line       FOREIGN KEY (order_line_id) REFERENCES order_lines(order_line_id),
  CONSTRAINT fk_oli_ingredient FOREIGN KEY (ingredient_id) REFERENCES ingredients(ingredient_id)
) ENGINE=InnoDB;



