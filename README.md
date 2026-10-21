# Sari POS — IT0049 TFA3: Forms, Validation, and File Upload

A CodeIgniter 4 point-of-sale app extended (from TFA2) with validated create/edit forms for **customers** and **user accounts**, plus **avatar upload** with display-ready image preparation. UI uses a neumorphic theme with SVG icons throughout.

| | |
|---|---|
| **Framework** | CodeIgniter 4.7.5 (bundled — no Composer needed) |
| **Requirements** | PHP **8.2+** with `intl`, `mbstring`, `gd`; MySQL/MariaDB (XAMPP 8.2+ works) |
| **Hosted link** | _add your hosted URL here_ |

---

## Features (activity checklist)

| Requirement | Where |
|---|---|
| New Customer form at `/customers/new` — full name required, email required + valid | `app/Controllers/Customers.php`, `app/Views/customers/form.php` |
| New User form at `/users/new` — username required + **unique**, full name required | `app/Controllers/Users.php`, `app/Views/users/form.php` |
| Edit pages for customers and users, pre-filled, update on submit | `/customers/{id}/edit`, `/users/{id}/edit` |
| Errors redisplayed with the user's previous input kept | `redirect()->back()->withInput()` + `old()` in the views |
| `avatar` column added to `users` | `app/Database/Migrations/2026-10-21-000001_AddAvatarToUsers.php` |
| Avatar: JPG/PNG only, max 2MB | `is_image`, `mime_in`, `ext_in`, `max_size[avatar,2048]` rules |
| Display-ready copy + thumbnail via the Image service | 400×400 copy → `public/uploads/avatars/`, 96×96 thumb → `public/uploads/avatars/thumbs/` |
| Only the filename is saved in the database | `users.avatar` = e.g. `9f2c…b1.jpg` |
| Avatars shown on the User Accounts list, placeholder if none | `app/Views/users/index.php`, `public/assets/img/avatar-placeholder.svg` |

Extras: CSRF protection on all forms, passwords stored with `password_hash()`, server-chosen random filenames, old avatar files deleted on replace, re-encoding through GD (strips anything that isn't image data), script execution blocked in `public/uploads`, client-side live preview and table search.

---

## Setup on XAMPP (localhost)

1. **Copy the folder** `pos_tfa3` into `C:\xampp\htdocs\` so you have `C:\xampp\htdocs\pos_tfa3\public\index.php`.
2. **Enable PHP extensions** (XAMPP → Apache *Config* → `php.ini`). Make sure these lines have **no** leading `;`:
   ```ini
   extension=intl
   extension=gd
   extension=mbstring
   ```
   Restart Apache after saving.
3. **Start Apache and MySQL** in the XAMPP Control Panel.
4. **Create the database** — choose one:
   - **phpMyAdmin (easiest):** open `http://localhost/phpmyadmin` → *Import* → choose `database/pos_db.sql` → *Go*. This creates `pos_db` with sample data.
   - **Spark CLI:** create an empty database named `pos_db`, then in the project folder run:
     ```bash
     php spark migrate --all
     php spark db:seed DatabaseSeeder
     ```
5. **Open** `http://localhost/pos_tfa3/public/` (or `http://localhost/pos_tfa3/`, which redirects there).

Sample user accounts all use the password `password123`.

### If your setup differs
Edit the `.env` file in the project root:
```ini
app.baseURL = 'http://localhost/YOUR_FOLDER_NAME/public/'
database.default.username = root
database.default.password =
```

---

## Routes

| Method | URL | Action |
|---|---|---|
| GET | `/` | Dashboard |
| GET | `/products` | Product list (read-only, from TFA2) |
| GET | `/customers` | Customer list |
| GET | `/customers/new` | New Customer form |
| POST | `/customers` | Validate + insert customer |
| GET | `/customers/{id}/edit` | Edit Customer form |
| POST | `/customers/{id}` | Validate + update customer |
| GET | `/users` | User Accounts list (with avatars) |
| GET | `/users/new` | New User form |
| POST | `/users` | Validate + insert user |
| GET | `/users/{id}/edit` | Edit User form + avatar upload |
| POST | `/users/{id}` | Validate + update user, process avatar |

---

## Project structure

```
pos_tfa3/
├── app/
│   ├── Config/Routes.php            routes
│   ├── Controllers/                 Dashboard, Products, Customers, Users
│   ├── Models/                      CustomerModel, UserModel, ProductModel
│   ├── Database/Migrations/         tables + AddAvatarToUsers
│   ├── Database/Seeds/              sample data
│   ├── Helpers/ui_helper.php        icon(), error_text(), avatar_url() …
│   └── Views/
│       ├── layouts/main.php         page shell
│       ├── partials/                sidebar, SVG sprite, flash, header, error summary
│       ├── dashboard/  customers/  users/  products/
├── database/pos_db.sql              database export (import in phpMyAdmin)
├── public/                          web root
│   ├── assets/css/app.css           neumorphic theme
│   ├── assets/img/avatar-placeholder.svg
│   └── uploads/avatars/(thumbs/)    prepared avatars
├── system/                          CodeIgniter framework
└── writable/                        cache, logs, sessions
```

---

## How the avatar upload works

1. `Users::update()` reads the file with `$this->request->getFile('avatar')`.
2. If a file was chosen, the avatar rules are added and run with the other fields:
   `uploaded`, `is_image`, `mime_in` (JPG/PNG), `ext_in`, `max_size[avatar,2048]`, `max_dims`.
3. On success, the Image service crops and resizes it twice with `fit()`:
   a 400×400 display copy and a 96×96 thumbnail, saved under a random filename.
4. The previous avatar files are deleted, and **only the filename** is written to `users.avatar`.
5. `avatar_url()` builds the public URL for the list/edit pages, or returns the placeholder SVG.

---

## Hosting notes

- Upload everything; point the domain's document root to `public/` if your host allows it. Otherwise set `app.baseURL` to the URL that ends in `/public/`.
- Import `database/pos_db.sql` into the host's database (remove the `CREATE DATABASE` / `USE` lines if the host gives you a database with a fixed name) and update the `database.default.*` values in `.env`.
- Set `CI_ENVIRONMENT = production` in `.env`.
- Make `writable/` and `public/uploads/avatars/` (with `thumbs/`) writable by the web server.
