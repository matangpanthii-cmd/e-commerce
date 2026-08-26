# Implementation Plan: E-Commerce Core Features & Auth

## Goal Description
Implement the core e-commerce functionality for LUMINA, transforming it from a static UI into a fully dynamic platform. This includes connecting the Product Listing Page (PLP) and Product Detail Page (PDP) to the database, building a functional Cart and Checkout system, and adding User Authentication (Login/Register).

## User Review Required
> [!IMPORTANT]
> The backend logic will use a simple "Page Controller" pattern (processing logic at the top of view files or in separate controller files required by `index.php`). The cart will utilize PHP Sessions `$_SESSION['cart']` for simplicity and performance on shared hosting, instead of requiring users to be logged in to add items to their cart.

## Proposed Changes

### 1. User Authentication (Login/Register)
- **Model:** 
  - `src/models/User.php`: Methods for `register()`, `login()`, and `getUserById()`. Passwords will be securely hashed using `password_hash()`.
- **Views:**
  - `src/views/auth/login.php` (New)
  - `src/views/auth/register.php` (New)
- **Routing (`public/index.php`):**
  - Add routes for `/login`, `/register`, and `/logout`.
- **Layout:**
  - Update `main.php` header to show "Login" or the user's name if logged in.

### 2. Dynamic Products (PLP & PDP)
- **Model:**
  - Enhance `Product.php` to handle dynamic category filtering, size/price filtering, and sorting for the PLP.
- **Views:**
  - Modify `plp.php`: Fetch categories and products from DB, generate dynamic filter URLs, and populate Alpine.js state or PHP foreach loops.
  - Modify `pdp.php`: Fetch specific product details, images, and variants by slug. Populate Alpine.js state for variant selection (Color, Size) before adding to cart.

### 3. Cart System (PHP Sessions)
- **Routing:**
  - Add `/cart/add`, `/cart/remove`, and `/cart/update` endpoints (Processing scripts that redirect back to the cart or product page).
- **Views:**
  - Modify `cart.php` to calculate totals directly from `$_SESSION['cart']` linked with Database prices, rather than mock JS data.

### 4. Checkout & Order Processing
- **Model:**
  - `src/models/Order.php` (New): Methods to create an order and order items.
- **Views:**
  - Modify `checkout.php`: Render the real order summary from the session cart. Add a POST handler at the top of the file to save the order to the `orders` and `order_items` tables upon submission, then clear the cart and redirect to a success page.
  - `src/views/checkout/success.php` (New): Order confirmation page.

## Verification Plan
### Automated & Manual Testing
1. **Auth:** Register a new user, log in, and log out. Verify session data securely stores user ID.
2. **PLP/PDP:** Click a product on the homepage to ensure it routes correctly to the dynamic PDP.
3. **Cart:** Add a specific variant (Color/Size) to the cart, update quantity, and remove it. Ensure totals calculate correctly.
4. **Checkout:** Complete a purchase flow. Verify that records correctly appear in the `orders` and `order_items` MySQL tables via phpMyAdmin.
