<?php 
class Migration {
  /** @var PDO */
  private static $db;
  private static $db_name;
  private static $db_user;
  private static $db_host;
  private static $db_password;

  public static function migrate(PDO $pdo) {
    self::$db = $pdo;
    self::startAll();
    self::$db->exec('set foreign_key_checks = 1');
  }

  private static function startAll()
  {
    self::initDatabase();
    self::createArticleTable();
    self::createCategoryTable();
    self::createArticleCategoryTable();
    self::createUserTable();
  }


  private static function initDatabase()
  {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    self::$db_host = $_ENV['DB_HOST'];
    self::$db_name = $_ENV['DB_NAME'];
    self::$db_user = $_ENV['DB_USER'];
    self::$db_password = $_ENV['DB_PASSWORD'];

    self::$db = new PDO(sprintf("mysql:host=%s", self::$db_host), self::$db_user, self::$db_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    self::$db->exec('use ' . self::$db_name);
    self::$db->exec('set foreign_key_checks = 0');
  }

  private static function createArticleTable()
  {
    self::$db->exec("DROP TABLE IF EXISTS article");
    self::$db->exec(
      "CREATE TABLE IF NOT EXISTS article (
        id INT AUTO_INCREMENT,
        title VARCHAR(50) NOT NULL,
        published_at DATETIME NOT NULL DEFAULT NOW(),
        body TEXT NOT NULL,
        PRIMARY KEY(id)             
      )"
    );
  }

  

  private static function createCategoryTable()
  {
    self::$db->exec("DROP TABLE IF EXISTS category");
    self::$db->exec(
      "CREATE TABLE IF NOT EXISTS category (
        id INT AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL UNIQUE,
        PRIMARY KEY(id)             
      )"
    );

  }
  

  private static function createArticleCategoryTable()
  {
    self::$db->exec("DROP TABLE IF EXISTS article_category");
    self::$db->exec(
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
  
  private static function createUserTable()
  {
    self::$db->exec("DROP TABLE IF EXISTS user");
    self::$db->exec(
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
  
}
?>