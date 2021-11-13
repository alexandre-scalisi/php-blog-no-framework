<?php 
namespace Database\Seeders;

use PDO;

class Seeder {

  private $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }
  
  public function startAll()
  {
    
    $this->initDatabase();
    $this->fillArticleTable();
    $this->fillCategoryTable();
    $this->fillArticleCategoryTable();
    $this->fillUserTable();
    $this->db->exec('set foreign_key_checks = 1');
  }

  public function initDatabase()
  {
    $this->db->exec('set foreign_key_checks = 0');
  }

  public function fillArticleTable(int $quantity = 10)
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


  public function fillCategoryTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      $this->db->exec(
        "INSERT INTO category VALUES (
    $i, 'category$i'
    )
    "
      );
    }
  }


  public function fillArticleCategoryTable(int $quantity = 10)
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
  
  public function fillUserTable(int $quantity = 10)
  {
    /* CREATE 10 USERS WITH ROLE USER */
    for ($i = 1; $i <= $quantity; $i++) {
      $password = password_hash('password'.$i, PASSWORD_BCRYPT);
      $this->db->exec(
        "INSERT INTO user (email, username, password, age) VALUES (
        'email$i@gmail.com', 'username$i', '$password', 20 + $i)
      "
      );
    }
    /* CREATE 1 USER WITH ROLE ADMIN */
    $password = password_hash('password', PASSWORD_BCRYPT);
    $this->db->exec(
      "INSERT INTO user (email, username, password, age, role) VALUES (
      'admin@gmail.com', 'admin', '$password', 100, 'admin')
    ");
  }
}

?>