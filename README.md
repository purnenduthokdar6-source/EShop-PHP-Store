# EShop

A PHP and MySQL ecommerce demo with product browsing, cart, checkout, user registration, and an admin panel.

Live site: https://eshop.freehosting.dev/

## Features

- Product catalog with category filtering
- Product details pages
- Session cart
- User registration and login
- Checkout flow
- Admin dashboard
- Product management
- Order listing

## Local Setup

1. Create a MySQL database named `eshop`.
2. Import `database/shop.sql`.
3. Copy `config/db.local.example.php` to `config/db.local.php`.
4. Update `config/db.local.php` with your local database credentials.
5. Run the project with a PHP server.

Default admin login:

- Email: `admin@eshop.local`
- Password: `password`

Change the admin password before using this project beyond a demo.

## Deployment

This app needs PHP and MySQL hosting. Static hosts such as GitHub Pages will not run the app.

For free PHP/MySQL hosting notes, see `DEPLOY_FREE.md`.
