<?php 
use App\App;
use App\Database;
use Database\Migrations\Migration;
use Database\Seeders\Seeder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BaseModel extends TestCase{
  protected function initDatabase() {
    $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
    $dotenv->load();
    $dbConfig = require __DIR__ . '/../../config/database.php';
    $_ENV['ENV'] = 'test';
    App::set(PDO::class, function() use($dbConfig) {
      $database = Database::getPDO($dbConfig);
      
      return $database;
    });
    $this->db = App::get(PDO::class);
    
    
  }
  protected function startAll() {
    $this->initDatabase();
    App::get(Migration::class)->startAll();
    App::get(Seeder::class)->startAll();
  }
}