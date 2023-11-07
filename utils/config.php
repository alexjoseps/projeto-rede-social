<?php
    define('USER', 'root');
    define('PASSWORD', '');
    define('HOST', 'localhost');
    define('DATABASE', 'progweb');

    try {
        $connection = new PDO("mysql:host=".HOST.";dbname=".DATABASE, USER, PASSWORD);

        $sql = "CREATE TABLE IF NOT EXISTS users(
          id INTEGER NOT NULL AUTO_INCREMENT,
          name VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          password VARCHAR(255),
          PRIMARY KEY (id)
          ) DEFAULT CHARSET=utf8;";
        
        $connection->exec($sql);
    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
?>