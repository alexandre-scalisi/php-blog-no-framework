<?php 
class Seeder {

  private static $db;
  private static $db_name;
  private static $db_user;
  private static $db_host;
  private static $db_password;

  public static function seed(PDO $pdo)
  {
    self::$db = $pdo;
    self::startAll();
  }
  
  private static function startAll()
  {
    
    self::initDatabase();
    self::fillArticleTable();
    self::fillCategoryTable();
    self::fillArticleCategoryTable();
    self::fillUserTable();
    self::$db->exec('set foreign_key_checks = 1');
  }

  private static function initDatabase()
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    self::$db_host = $_ENV['DB_HOST'];
    self::$db_name = $_ENV['DB_NAME'];
    self::$db_user = $_ENV['DB_USER'];
    self::$db_password = $_ENV['DB_PASSWORD'];

    self::$db = new PDO(sprintf("mysql:host=%s;dbname=%s", self::$db_host, self::$db_name), self::$db_user, self::$db_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    self::$db->exec('set foreign_key_checks = 0');
  }

  private static function fillArticleTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      $date = date("Y-m-d H:i:s");
      self::$db->exec(
        "INSERT INTO article VALUES (
  $i, 'title$i', '$date', 'lorem ipsum$i'
  )
  "
      );
    }
  }


  private static function fillCategoryTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      self::$db->exec(
        "INSERT INTO category VALUES (
    $i, 'category$i'
    )
    "
      );
    }
  }


  private static function fillArticleCategoryTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      self::$db->exec(
        "INSERT INTO article_category VALUES (
      $i, $i, $i
      )
      "
      );
    }
  }
  
  private static function fillUserTable(int $quantity = 10)
  {
    for ($i = 1; $i <= $quantity; $i++) {
      $password = password_hash('password'.$i, PASSWORD_BCRYPT);
      self::$db->exec(
        "INSERT INTO user VALUES (
      $i, 'email$i@gmail.com', 'username$i', '$password', 20 + $i, null
      )
      "
      );
    }
  }

}

?>