<?php 
namespace Database\Migrations;

use PDO;

class Migration {
  /** @var PDO */
  public $db;

  public function __construct(PDO $pdo) {
    $this->db = $pdo;
    
  }

  public function startAll()
  {
    $this->db->exec('set foreign_key_checks = 1');
    $this->initDatabase();
    $this->createArticleTable();
    $this->createCategoryTable();
    $this->createArticleCategoryTable();
    $this->createUserTable();
  }


  public function initDatabase()
  {
    $this->db->exec('set foreign_key_checks = 0');
  }

  public function createArticleTable()
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

  

  public function createCategoryTable()
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
  

  public function createArticleCategoryTable()
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
  
  public function createUserTable()
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
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        PRIMARY KEY(id)        
      )"
    );
  }
  
}
?>