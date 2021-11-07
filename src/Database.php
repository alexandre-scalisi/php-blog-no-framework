<?php

namespace App;

use PDO;
use PDOException;

class Database
{
  /** @var PDO */
  private static $pdo;

  private static $db_host;
  private static $db_name;
  private static $db_password;
  private static $db_user;

  /**
   * static function to get an instance of PDO with optional options
   */
  public static function getPDO(?array $options = [], ?array $pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ]): PDO
  {
    if (self::$pdo !== null) return self::$pdo;
    
    self::$db_host = $options['db_host'] ?? $_ENV['DB_HOST'] ?? 'localhost';
    self::$db_name = $options['db_name'] ?? $_ENV['DB_NAME'] ?? 'php_project_db';
    self::$db_user = $options['db_user'] ?? $_ENV['DB_USER'] ?? 'root';
    self::$db_password = $options['db_password'] ?? $_ENV['DB_PASSWORD'] ?? '';
    
    self::createDBIfNotExists();
    
    try {
      self::$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', self::$db_host, self::$db_name), self::$db_user, self::$db_password, $pdoOptions);
    } catch (PDOException $e) {
      die('PDO error when trying to get PDO: ' . $e->getMessage());
    }

    return self::$pdo;
  }


  /**
   * IF the database doesn't exists, we create it when calling Database::getPDO()
   */
  private static function createDBIfNotExists(): void
  {
    try {
      $query = 'CREATE DATABASE IF NOT EXISTS ' . self::$db_name;
      $db = new PDO(sprintf('mysql:host=%s', self::$db_host), self::$db_user, self::$db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ]);

      $db->query($query);
    } catch (PDOException $e) {
      die('PDO error when trying to create DB: ' . $e->getMessage());
    }
  }
}