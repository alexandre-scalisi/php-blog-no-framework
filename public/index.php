<?php
session_start();

require_once __DIR__.'/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;
use App\App;

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$dbConfig = require_once __DIR__.'/../config/database.php';

App::set(PDO::class, function() use($dbConfig){
  return Database::getPDO($dbConfig); 
});

$db = App::get(PDO::class);

require_once __DIR__.'/../src/router.php';