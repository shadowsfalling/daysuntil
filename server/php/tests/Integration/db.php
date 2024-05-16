<?php


$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("
    CREATE TABLE `categories` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `user_id` INTEGER NOT NULL,
      `name` TEXT NOT NULL,
      `color` TEXT NOT NULL
    );

    CREATE TABLE `countdowns` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `title` TEXT NOT NULL,
      `datetime` DATETIME NOT NULL,
      `is_public` INTEGER DEFAULT NULL,
      `category_id` INTEGER DEFAULT NULL,
      `user_id` INTEGER DEFAULT NULL,
      FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    );

    CREATE TABLE `password_resets` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `user_id` INTEGER NOT NULL,
      `token` TEXT NOT NULL,
      `created_at` DATETIME NOT NULL,
      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    );

    CREATE TABLE `users` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `username` TEXT NOT NULL,
      `password` TEXT NOT NULL,
      `email` TEXT NOT NULL,
      `auth_token` TEXT NULL,
      UNIQUE (`username`),
      UNIQUE (`email`)
    );
");

return $pdo;