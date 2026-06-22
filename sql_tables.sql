-- ==============================================
-- JONAH'S URBAN TAILS - COMPLETE DATABASE SCHEMA
-- ==============================================

-- ==============================================
-- 1. CUSTOMERS TABLE
-- Stores all registered customer information
-- ==============================================
CREATE TABLE IF NOT EXISTS my_Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    salutation VARCHAR(10) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_initial VARCHAR(5),
    last_name VARCHAR(50) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    street_address VARCHAR(100) NOT NULL,
    city VARCHAR(50) NOT NULL,
    region VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    registration_date DATETIME NOT NULL,
    login_name VARCHAR(50) NOT NULL UNIQUE,
    login_password VARCHAR(255) NOT NULL,
    INDEX idx_email (email),
    INDEX idx_login (login_name)
);

-- ==============================================
-- 2. PRODUCT CATEGORIES TABLE
-- Stores product/service categories
-- ==============================================
CREATE TABLE IF NOT EXISTS my_ProductCategories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_description TEXT,
    category_image VARCHAR(255) DEFAULT '🐾'
);

-- ==============================================
-- 3. PRODUCTS TABLE
-- Stores all products/services offered
-- ==============================================
CREATE TABLE IF NOT EXISTS my_Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(200) NOT NULL,
    product_description TEXT,
    category_id INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES my_ProductCategories(category_id),
    INDEX idx_category (category_id),
    INDEX idx_price (unit_price)
);

-- ==============================================
-- 4. SHOPPING CART TABLE
-- Stores items in user's shopping cart
-- ==============================================
CREATE TABLE IF NOT EXISTS my_ShoppingCart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_date DATETIME NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES my_Customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES my_Products(product_id),
    UNIQUE KEY unique_cart_item (customer_id, product_id),
    INDEX idx_customer (customer_id)
);

-- ==============================================
-- 5. ORDERS TABLE
-- Stores completed orders
-- ==============================================
CREATE TABLE IF NOT EXISTS my_Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(20) DEFAULT 'completed',
    FOREIGN KEY (customer_id) REFERENCES my_Customers(customer_id),
    INDEX idx_customer (customer_id),
    INDEX idx_date (order_date)
);

-- ==============================================
-- 6. ORDER ITEMS TABLE
-- Stores individual items within each order
-- ==============================================
CREATE TABLE IF NOT EXISTS my_OrderItems (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES my_Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES my_Products(product_id),
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
);

-- ==============================================
-- INSERT SAMPLE CATEGORY DATA
-- ==============================================
INSERT INTO my_ProductCategories (category_name, category_description, category_image) VALUES
('Dog Walking', 'Professional dog walking services in your neighborhood', '🐾'),
('Cat Sitting', 'In-home cat care including playtime and feeding', '🐾'),
('Small Pet Care', 'Care for hamsters, guinea pigs, rabbits, and other small pets', '🐾'),
('Exotic Pet Care', 'Specialized care for reptiles, birds, and fish', '🐾'),
('Overnight Stays', 'Extended overnight pet sitting in your home', '🐾');

-- ==============================================
-- INSERT SAMPLE PRODUCT DATA
-- ==============================================
INSERT INTO my_Products (product_name, product_description, category_id, unit_price) VALUES
-- Dog Walking (category_id = 1)
('30-Minute Dog Walk', 'Perfect for small dogs or quick potty breaks', 1, 20.00),
('60-Minute Dog Walk', 'Extended walk for exercise and exploration', 1, 35.00),
('Puppy Play Date', 'Supervised playtime and basic training reinforcement', 1, 25.00),
('Group Dog Walk', 'Social walk with up to 3 friendly dogs', 1, 30.00),

-- Cat Sitting (category_id = 2)
('Daily Cat Visit', '30-minute visit for feeding, play, and cuddles', 2, 18.00),
('Cat Litter Box Service', 'Complete litter box cleaning and maintenance', 2, 15.00),
('Cat Medication', 'Oral medication or insulin shots for cats', 2, 25.00),
('Cat Enrichment Session', 'Interactive play with wand toys and puzzles', 2, 22.00),

-- Small Pet Care (category_id = 3)
('Hamster/Guinea Pig Care', 'Daily feeding, water change, and playtime', 3, 15.00),
('Small Pet Cage Cleaning', 'Complete cage cleaning and bedding change', 3, 25.00),
('Rabbit Care Package', 'Feeding, exercise time, and litter box cleaning', 3, 20.00),

-- Exotic Pet Care (category_id = 4)
('Reptile Feeding Service', 'Proper feeding with live/frozen prey as needed', 4, 25.00),
('Fish Tank Maintenance', 'Water testing, partial change, and filter check', 4, 30.00),
('Bird Care Visit', 'Cage cleaning, fresh food/water, and social time', 4, 22.00),

-- Overnight Stays (category_id = 5)
('Overnight Pet Sitting', '12-hour overnight stay in your home', 5, 75.00),
('Weekend Package', 'Friday evening to Monday morning care', 5, 200.00),
('Holiday Premium Stay', '24-hour care during holidays', 5, 100.00);

-- ==============================================
-- VERIFICATION QUERIES
-- ==============================================
-- Check all tables were created
SELECT '=== TABLES CREATED ===' as '';
SHOW TABLES LIKE 'my_%';

-- Check counts
SELECT '=== TABLE COUNTS ===' as '';
SELECT 'my_Customers' as Table, COUNT(*) as Records FROM my_Customers
UNION
SELECT 'my_ProductCategories', COUNT(*) FROM my_ProductCategories
UNION
SELECT 'my_Products', COUNT(*) FROM my_Products
UNION
SELECT 'my_ShoppingCart', COUNT(*) FROM my_ShoppingCart
UNION
SELECT 'my_Orders', COUNT(*) FROM my_Orders
UNION
SELECT 'my_OrderItems', COUNT(*) FROM my_OrderItems;

-- Show sample data
SELECT '=== SAMPLE PRODUCT DATA ===' as '';
SELECT p.product_id, p.product_name, c.category_name, p.unit_price
FROM my_Products p
JOIN my_ProductCategories c ON p.category_id = c.category_id
LIMIT 10;
