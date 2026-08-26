-- Seed Categories
INSERT INTO categories (name, slug) VALUES 
('Men''s Tailoring', 'mens-tailoring'),
('Women''s Collection', 'womens-collection'),
('Outerwear', 'outerwear'),
('Knitwear', 'knitwear'),
('Essentials', 'essentials'),
('Accessories', 'accessories');

-- Seed Products
INSERT INTO products (category_id, name, slug, description, price, status) VALUES 
(3, 'Navy Cashmere Overcoat', 'navy-cashmere-overcoat', 'The epitome of effortless sophistication. Crafted in Italy from pure cashmere, this overcoat offers exceptional warmth without the weight.', 895.00, 'new_in'),
(6, 'Essential Leather Tote', 'essential-leather-tote', 'Handcrafted from full-grain leather, this tote is designed to carry your daily essentials in style.', 450.00, 'active'),
(2, 'Silk Blouse', 'silk-blouse', 'A versatile silk blouse that transitions seamlessly from day to night.', 180.00, 'sale'),
(1, 'Classic Oxford Shirt', 'classic-oxford-shirt', 'A wardrobe staple, our classic oxford shirt features a tailored fit and premium cotton construction.', 120.00, 'active');

-- Seed Product Images
-- Navy Cashmere Overcoat
INSERT INTO product_images (product_id, image_url, is_primary) VALUES 
(1, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=1936&auto=format&fit=crop', TRUE),
(1, 'https://images.unsplash.com/photo-1591047139825-d91aecb6caea?q=80&w=1936&auto=format&fit=crop', FALSE),
(1, 'https://images.unsplash.com/photo-1591047139830-d91aecb6caea?q=80&w=1936&auto=format&fit=crop', FALSE);

-- Essential Leather Tote
INSERT INTO product_images (product_id, image_url, is_primary) VALUES 
(2, 'https://images.unsplash.com/photo-1434389678278-be42b4432831?q=80&w=1740&auto=format&fit=crop', TRUE);

-- Silk Blouse
INSERT INTO product_images (product_id, image_url, is_primary) VALUES 
(3, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=1964&auto=format&fit=crop', TRUE);

-- Classic Oxford Shirt
INSERT INTO product_images (product_id, image_url, is_primary) VALUES 
(4, 'https://images.unsplash.com/photo-1507680434267-dbd3f551dbb8?q=80&w=1856&auto=format&fit=crop', TRUE);

-- Seed Product Variants
-- Navy Cashmere Overcoat
INSERT INTO product_variants (product_id, color_name, color_hex, size, stock) VALUES 
(1, 'Navy', '#1a2b3c', '46', 10),
(1, 'Navy', '#1a2b3c', '48', 15),
(1, 'Navy', '#1a2b3c', '50', 12),
(1, 'Navy', '#1a2b3c', '52', 5),
(1, 'Camel', '#c19a6b', '48', 8),
(1, 'Camel', '#c19a6b', '50', 6),
(1, 'Charcoal', '#36454f', '48', 20),
(1, 'Charcoal', '#36454f', '50', 18);

-- Silk Blouse
INSERT INTO product_variants (product_id, color_name, color_hex, size, stock) VALUES 
(3, 'Ivory', '#fffff0', 'XS', 5),
(3, 'Ivory', '#fffff0', 'S', 12),
(3, 'Ivory', '#fffff0', 'M', 10),
(3, 'Ivory', '#fffff0', 'L', 3);
