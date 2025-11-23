CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  calories_target INT DEFAULT 2000,
  diet_type ENUM('none','balanced','low_carb','high_protein','vegetarian') DEFAULT 'none',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  meal_type ENUM('breakfast','lunch','dinner','snack') NOT NULL,
  calories INT NOT NULL,
  diet_type ENUM('none','balanced','low_carb','high_protein','vegetarian') DEFAULT 'none',
  instructions TEXT
);

CREATE TABLE meal_plans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  total_calories INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE meal_plan_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  meal_plan_id INT NOT NULL,
  plan_date DATE NOT NULL,
  meal_type ENUM('breakfast','lunch','dinner','snack') NOT NULL,
  recipe_id INT NOT NULL,
  FOREIGN KEY (meal_plan_id) REFERENCES meal_plans(id),
  FOREIGN KEY (recipe_id) REFERENCES recipes(id)
);
