-- Clean up existing data (Compatible with phpMyAdmin Import)
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM product_variants;
DELETE FROM product_images;
DELETE FROM products;
DELETE FROM categories;
DELETE FROM users;
DELETE FROM site_settings;
ALTER TABLE order_items AUTO_INCREMENT = 1;
ALTER TABLE orders AUTO_INCREMENT = 1;
ALTER TABLE product_variants AUTO_INCREMENT = 1;
ALTER TABLE product_images AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE site_settings AUTO_INCREMENT = 1;
SET FOREIGN_KEY_CHECKS = 1;

-- Seed Admin User
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@PRAIRAVEE.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); -- Password is 'password'

-- Seed Categories
INSERT INTO categories (name, slug) VALUES 
('ยาดมสมุนไพร (Herbal Inhaler)', 'herbal-inhaler'),
('พิมเสนน้ำ (Liquid Balm)', 'liquid-balm'),
('ยาหม่อง (Herbal Balm)', 'herbal-balm'),
('เซ็ตของขวัญ (Gift Sets)', 'gift-sets');

-- Seed Products
INSERT INTO products (category_id, name, slug, description, price, status) VALUES 
(1, 'ยาดมสมุนไพรไทย สูตรต้นตำรับ', 'PRAIRAVEE-original-herbal-inhaler', 'หอม สดชื่น ผ่อนคลาย ยาดมสมุนไพรไทยสูตรดั้งเดิม คัดสรรวัตถุดิบคุณภาพดี ช่วยบรรเทาอาการวิงเวียนศีรษะ', 220.00, 'active'),
(1, 'ยาดมสมุนไพรไทย สูตรอ่อนโยน', 'PRAIRAVEE-gentle-herbal-inhaler', 'หอม อ่อนโยน ในทุกลมหายใจ เหมาะสำหรับผู้ที่ชอบกลิ่นหอมเบาสบาย ไม่ฉุนจนเกินไป', 220.00, 'active'),
(1, 'ยาดมสมุนไพรไทย สูตรพรีเมียม', 'PRAIRAVEE-premium-herbal-inhaler', 'หอม เข้มข้น ผ่อนคลายยาวนาน สูตรพรีเมียมที่เพิ่มปริมาณสมุนไพรหายาก บรรจุในขวดดีไซน์หรูหรา', 250.00, 'new_in');

-- Seed Product Images
INSERT INTO product_images (product_id, image_url, is_primary) VALUES 
(1, 'https://i.ibb.co/SXjLFB0X/S-14688261-0.jpg', TRUE),
(2, 'https://i.ibb.co/DfCvQdsL/product2.jpg', TRUE),
(3, 'https://i.ibb.co/fGPQ1RQN/product3.jpg', TRUE);

-- Seed Product Variants
INSERT INTO product_variants (product_id, color_name, color_hex, size, stock) VALUES 
(1, 'กระปุกเขียว (Green)', '#2d4a3e', '1 pc', 50),
(1, 'กระปุกเขียว (Green)', '#2d4a3e', '3 pcs', 20),
(2, 'กระปุกชมพู (Pink)', '#d4a1a1', '1 pc', 40),
(3, 'กระปุกทอง (Gold)', '#b89768', '1 pc', 30);

-- Seed Site Settings (ค่าเริ่มต้น - แก้ไขได้ผ่าน Admin Panel)
INSERT INTO site_settings (setting_key, setting_value, setting_type, label) VALUES
-- Hero Section
('hero_bg_image',       'https://i.ibb.co/SXjLFB0X/S-14688261-0.jpg',  'image_url', 'Hero: ภาพพื้นหลัง'),
('hero_product_image',  'https://i.ibb.co/qM5rLb5R/product-hero.jpg',   'image_url', 'Hero: ภาพสินค้าด้านขวา'),
('hero_title',          'หอม สดชื่น ผ่อนคลาย',                          'text',      'Hero: หัวข้อหลัก'),
('hero_subtitle',       'ด้วยสมุนไพรไทยแท้',                             'text',      'Hero: หัวข้อรอง'),
('hero_description',    'ยาดมสมุนไพรไทย ไพราวี คัดสรรสมุนไพรคุณภาพ หอมสดชื่น อ่อนโยน สูดลึกแค่ไหนก็สบายใจ ในทุกลมหายใจ', 'textarea', 'Hero: คำอธิบาย'),
-- Promotion Banners
('promo1_title',        'ซื้อ 2 แถม 1',                                  'text',      'โปรโมชัน 1: หัวข้อ'),
('promo1_subtitle',     'เฉพาะเดือนนี้เท่านั้น',                         'text',      'โปรโมชัน 1: คำอธิบาย'),
('promo1_image',        'https://i.ibb.co/SXjLFB0X/S-14688261-0.jpg',    'image_url', 'โปรโมชัน 1: ภาพประกอบ'),
('promo2_title',        'จัดส่งฟรี',                                      'text',      'โปรโมชัน 2: หัวข้อ'),
('promo2_subtitle',     'เมื่อสั่งซื้อครบ 499 บาท',                      'text',      'โปรโมชัน 2: คำอธิบาย'),
-- Story Section
('story_image',         'https://i.ibb.co/DfCvQdsL/story.jpg',           'image_url', 'เรื่องราว: ภาพพื้นหลัง'),
('story_title',         'เรื่องราวของไพราวี',                              'text',      'เรื่องราว: หัวข้อ'),
('story_description',   'เราเชื่อในพลังแห่งสมุนไพรไทย ที่ส่งต่อความหอม สดชื่น และผ่อนคลายจากภูมิปัญญาไทย สู่คุณภาพชีวิตที่ดีขึ้น', 'textarea', 'เรื่องราว: คำอธิบาย'),
-- Articles
('article1_image',      'https://i.ibb.co/fGPQ1RQN/article1.jpg',        'image_url', 'สาระน่ารู้ 1: ภาพ'),
('article1_title',      'ประโยชน์ของสมุนไพรไทย',                          'text',      'สาระน่ารู้ 1: หัวข้อ'),
('article1_description','ช่วยบรรเทาอาการวิงเวียน หน้ามืด และผ่อนคลายความเครียด', 'textarea', 'สาระน่ารู้ 1: คำอธิบาย'),
('article2_image',      'https://i.ibb.co/qM5rLb5R/article2.jpg',        'image_url', 'สาระน่ารู้ 2: ภาพ'),
('article2_title',      'วิธีใช้ยาดมให้ได้ประสิทธิภาพ',                   'text',      'สาระน่ารู้ 2: หัวข้อ'),
('article2_description','สูดลึกๆ เมื่อรู้สึกเครียด วิงเวียน หรืออ่อนเพลีย', 'textarea', 'สาระน่ารู้ 2: คำอธิบาย'),
-- General
('site_name',           'ไพราวี PRAIRAVEE',                                 'text',      'ทั่วไป: ชื่อเว็บไซต์'),
('footer_copyright',    '© 2026 ไพราวี PRAIRAVEE. All rights reserved.',    'text',      'ทั่วไป: ข้อความ Copyright');
