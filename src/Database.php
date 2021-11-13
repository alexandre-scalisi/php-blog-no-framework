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
  public static function getPDO(?array $dbParameters = [], ?array $pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
  ])
  {
    if (self::$pdo !== null) return self::$pdo;
    self::$db_host = $dbParameters['DB_HOST'];
    self::$db_name = $_ENV['ENV'] === 'test' ? 'test_' . $dbParameters['DB_NAME'] : $dbParameters['DB_NAME'];
    self::$db_user = $dbParameters['DB_USER'];
    self::$db_password = $dbParameters['DB_PASSWORD'];
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