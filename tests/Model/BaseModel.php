<?php

use PHPUnit\Framework\TestCase;

abstract class BaseModel extends TestCase
{
  /** @var PDO */
  protected $db;

  protected $db_host;
  protected $db_name;
  protected $db_user;
  protected $db_password;

  protected function startAll()
  {
    $this->initDatabase();
    $this->createArticleTable();
    $this->fillArticleTable();
    $this->createCategoryTable();
    $this->fillCategoryTable();
    $this->createArticleCategoryTable();
    $this->fillArticleCategoryTable();
    $this->createUserTable();
    $this->fillUserTable();
  }

  protected function initDatabase()
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $this->db_host = $_ENV['DB_HOST'];
    $this->db_name = 'test_' . $_ENV['DB_NAME'];
    $this->db_user = $_ENV['DB_USER'];
    $this->db_password = $_ENV['DB_PASSWORD'];

    $this->db = new PDO(sprintf("mysql:host=$this->db_host"), $this->db_user, $this->db_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $this->db->exec('DROP DATABASE IF EXISTS ' . $this->db_name);
    $this->db->exec('CREATE DATABASE IF NOT EXISTS ' . $this->db_name);
    $this->db->exec("use $this->db_name");
  }

  protected function createArticleTable()
  {
    $this->db->exec("DROP TABLE IF EXISTS article");
    $this->db->exec(
      "CREATE TABLE IF NOT EXISTS article (
        id INT AUTO_INCREMENT,
        title VARCHAR(50) NOT NULL,
        published_at DATETIME NOT NULL DEFAULT NOW(),
        body TEXT NOT NULL,
        PRIMARY KEY(id)             
      )"
    );
  }

  protected function fillArticleTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      $date = date("Y-m-d H:i:s");

      $this->db->exec(
        "INSERT INTO article VALUES (
           $i, 'title$i', '$date', 'lorem ipsum$i'  
        ) 
        "
      );
    }
  }

  protected function createCategoryTable()
  {
    $this->db->exec("DROP TABLE IF EXISTS category");
    $this->db->exec(
      "CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL UNIQUE,
        PRIMARY KEY(id)             
      )"
    );
  }
  protected function fillCategoryTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {

      $this->db->exec(
        "INSERT INTO category VALUES (
           $i, 'name$i'  
        ) 
        "
      );
    }
  }

  protected function createArticleCategoryTable()
  {
    $this->db->exec("DROP TABLE IF EXISTS article_category");
    $this->db->exec(
      "CREATE TABLE IF NOT EXISTS article_category (
        id INT AUTO_INCREMENT,
        article_id int NOT NULL,
        category_id int NOT NULL,
        UNIQUE(article_id, category_id),
        PRIMARY KEY(id),
        FOREIGN KEY(article_id)
          REFERENCES article(id),
        FOREIGN KEY(category_id)
          REFERENCES category(id)            
      )"
    );
  }
  protected function fillArticleCategoryTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {

      $this->db->exec(
        "INSERT INTO article_category VALUES (
           $i, $i, $i  
        ) 
        "
      );
    }
  }

  protected function createUserTable()
  {
    $this->db->exec("DROP TABLE IF EXISTS user");
    $this->db->exec(
      "CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT,
        email VARCHAR(100) NOT NULL UNIQUE,
        username VARCHAR(50) UNIQUE,
        password VARCHAR(100) NOT NULL,
        age INT,
        photo VARCHAR(255),
        PRIMARY KEY(id)        
      )"
    );
  }

  protected function fillUserTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      $password = password_hash('password' . $i, PASSWORD_BCRYPT);
      $this->db->exec(
        "INSERT INTO user VALUES (
          $i, 'email$i@gmail.com', 'username$i', '$password', 20 + $i, null
        )
        "
      );
    }
  }
}