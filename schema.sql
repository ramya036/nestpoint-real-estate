-- Nest Point database schema. Loaded automatically by docker compose,
-- or run it yourself:  mysql -u <user> -p <database> < schema.sql

CREATE TABLE IF NOT EXISTS buyer_data (
  buyer_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  buyer_name     VARCHAR(60)  NOT NULL,
  buyer_user_id  VARCHAR(30)  NOT NULL UNIQUE,
  buyer_email    VARCHAR(120) NOT NULL,
  buyer_password VARCHAR(255) NOT NULL  -- bcrypt hash
);

CREATE TABLE IF NOT EXISTS seller_data (
  seller_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seller_name     VARCHAR(60)  NOT NULL,
  seller_user_id  VARCHAR(30)  NOT NULL UNIQUE,
  seller_email    VARCHAR(120) NOT NULL,
  seller_password VARCHAR(255) NOT NULL  -- bcrypt hash
);

CREATE TABLE IF NOT EXISTS admin_data (
  admin_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_name     VARCHAR(60)  NOT NULL,
  admin_user_id  VARCHAR(30)  NOT NULL UNIQUE,
  admin_email    VARCHAR(120) NOT NULL,
  admin_password VARCHAR(255) NOT NULL  -- bcrypt hash
);

-- Property listings ("cards" in the UI)
CREATE TABLE IF NOT EXISTS card (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name    VARCHAR(100) NOT NULL DEFAULT '',
  address VARCHAR(255) NOT NULL,
  age     INT NOT NULL,          -- years
  bed     INT NOT NULL,          -- bedrooms
  ad      INT NOT NULL,          -- bathrooms
  garden  VARCHAR(20) NOT NULL,
  pa      VARCHAR(20) NOT NULL,  -- parking
  tax     INT NOT NULL,          -- price in USD
  image   VARCHAR(255) NOT NULL  -- file name under img/
);
