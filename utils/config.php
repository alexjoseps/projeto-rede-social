<?php
define('USER', 'root');
define('PASSWORD', '');
define('HOST', 'localhost');
define('DATABASE', 'social_network');

try {
    $connection = new mysqli(HOST, USER, PASSWORD);
    $database_sql = 'CREATE DATABASE IF NOT EXISTS social_network;';
    $connection->query($database_sql);
    $connection->close();

    $connection = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);

    $users_sql = "CREATE TABLE IF NOT EXISTS users(
        id INTEGER NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password TEXT NOT NULL,
        PRIMARY KEY(id)
    ) DEFAULT CHARSET = latin1;";
    $connection->exec($users_sql);

    $publications_sql = "CREATE TABLE IF NOT EXISTS publications(
        id INTEGER NOT NULL AUTO_INCREMENT,
        description TEXT NOT NULL,
        user_id INTEGER NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    ) DEFAULT CHARSET = latin1;";
    $connection->exec($publications_sql);

    $comments_sql = "CREATE TABLE IF NOT EXISTS comments(
        id INTEGER NOT NULL AUTO_INCREMENT,
        comment TEXT NOT NULL,
        user_id INTEGER NOT NULL,
        publication_id INTEGER NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    ) DEFAULT CHARSET = latin1;";
    $connection->exec($comments_sql);

    $likes_sql = "CREATE TABLE IF NOT EXISTS likes(
        id INTEGER NOT NULL AUTO_INCREMENT,
        likeable_id INTEGER NOT NULL,
        likeable_type VARCHAR(255) NOT NULL,
        user_id INTEGER NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    ) DEFAULT CHARSET = latin1;";
    $connection->exec($likes_sql);

    $friendships_sql = "CREATE TABLE IF NOT EXISTS friendships(
        id INTEGER NOT NULL AUTO_INCREMENT,
        requestor_id INTEGER NOT NULL,
        requestee_id INTEGER NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    ) DEFAULT CHARSET = latin1;";
    $connection->exec($friendships_sql);

} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>