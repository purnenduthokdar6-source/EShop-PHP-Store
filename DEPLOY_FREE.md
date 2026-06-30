# Free Deployment Guide

This project is a PHP and MySQL store, so use a free host that supports both PHP and MySQL. Static-only hosts such as GitHub Pages will not run the cart, login, admin panel, or database pages.

## Recommended Free Host

Use InfinityFree for a no-cost PHP/MySQL deployment.

1. Create an InfinityFree account and add a free hosting account.
2. Open the hosting control panel and create a MySQL database.
3. Note the database host, database name, username, and password.
4. Open phpMyAdmin for that database.
5. Upload the project files into the host's `htdocs` folder.
6. Visit `https://your-free-domain/setup.php`.
7. Enter the MySQL host, database name, username, and password from the hosting panel.
8. Leave the import checkbox enabled and submit the form.
9. Delete `setup.php` from the server after setup completes.
10. Visit the free domain/subdomain assigned by the host.

If `setup.php` cannot write `config/db.local.php`, copy `config/db.local.example.php` to `config/db.local.php`, fill in the MySQL details manually, and import `database/shop_import.sql` from phpMyAdmin.

## Admin Login

Default admin account:

- Email: `admin@eshop.local`
- Password: `password`

Change this immediately after deployment by registering a new admin or updating the password in the database.

## Files To Upload

Upload the project contents, including:

- `admin/`
- `config/`
- `css/`
- `database/`
- `images/`
- `includes/`
- `js/`
- `setup.php`
- all `.php` files in the project root

Do not upload `.git/`.

## Common Fixes

If you see `Database connection failed`, the values in `config/db.local.php` do not match the database credentials from the host.

If product images do not load, the Unsplash image URLs may be blocked or unavailable. Add products from the admin panel with image URLs you control.

If importing `database/shop.sql` fails because of `CREATE DATABASE` or `USE eshop`, import `database/shop_import.sql` instead.
