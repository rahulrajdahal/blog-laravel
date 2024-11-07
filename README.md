# [Blog](https://github.com/rahulrajdahal/blog-laravel). Create your own blog using the APIs provided

- Register an account.
- Create blog tags.
- Create blog categories.
- Create blog post.
- Browse blog posts.

## Preview

[![Blog](./screenshots/swagger.png)](https://github.com/rahulrajdahal/blog-laravel)
![Blog](./screenshots/swagger_open.png)

## 🏗 Development Guide

### 1. clone the repository

```sh
git clone https://github.com/rahulrajdahal/blog-laravel.git
```

### 2. Install Dependencies

#### npm

```sh
cd blog-laravel && composer install
```

### 3. Connect to your API 💾

- Copy **.env.example** file.
- Rename **.env.example copy** to **.env** file.
- Update key value pairs.

### 4. Prepare and migrate the database

```sh
php artisan migrate
```

### 5. Run development server

```sh
php artisan serve
```

## 🚀 Project Structure

Inside of project [Blog](https://github.com/rahulrajdahal/blog-laravel), you'll see the following folders and files:

```text
/
├── app/
|   ├── Models/
│   │   └── Model.php
│   ├── Http
│   │   └── Controllers
│   │       └── Controller.php
│   └── Providers
├── config/
│   └── config.php
├── database/
│   ├── factories
│   │   └── Factory.php
│   ├── migrations
│   │   └── my_migration_table.php
│   ├── seeders
│   │   └── seeder.php
├── tests/
│   ├── Feature
│   │   └── FeatureTest.php
│   ├── Unit
│   │   └── UnitTest.php
│── composer.json
└── package.json
```
