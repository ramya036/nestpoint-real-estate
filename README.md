# Nest Point — Real Estate Listing Platform

A PHP + MySQL web app where **sellers list homes**, **buyers browse and save them**, and **admins manage the platform**. Built as a team project for the Web Programming course at Georgia State University.

![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)

## Features

- **Three roles** with separate registration, login and dashboards: buyer, seller and admin
- **Sellers** create property cards with address, age, bedrooms, garden, parking, tax and a photo, and can delete their listings
- **Buyers** browse listings and search them by bedrooms, garden, parking, tax or address
- **Admins** get an overview of every listing on the platform
- Session-based access control for each dashboard
- Passwords are stored as **bcrypt hashes** (`password_hash` / `password_verify`) and every query uses **prepared statements**

## Project structure

| File(s) | Purpose |
|---|---|
| `index.php` | Landing page |
| `*_registration_form.php`, `*_register_submit.php` | Sign-up for each role |
| `loginb.php`, `logins.php`, `login.php`, `*_login_submit.php` | Login for buyers, sellers and admins |
| `buyer_dashboard.php`, `seller_dashboard.php`, `admin_dashboard.php` | Role dashboards |
| `form.php`, `insert_card.php`, `delete_card.php` | Create and remove property listings |
| `create_users_table.php`, `create_buyer.php`, `create_card.php` | One-time table setup scripts |
| `db.php` | Database connection |

## Run locally

1. Install [XAMPP](https://www.apachefriends.org/) (Apache + PHP + MySQL) and clone this repo into `htdocs/`.
2. Create a MySQL database and user, then update the connection details in `db.php` and the `*_submit.php` files.
3. Open `create_users_table.php`, `create_buyer.php` and `create_card.php` once in the browser to create the tables.
4. Visit `http://localhost/Nest-Point/`.

## Team

Built by Ramya Sree Narra with classmates in the GSU Web Programming course.
