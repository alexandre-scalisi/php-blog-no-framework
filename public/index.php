<?php
session_start();

require_once __DIR__.'/../vendor/autoload.php';

use App\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$db_config = require_once __DIR__.'/../config/database.php';
$db = Database::getPDO($db_config);
require_once __DIR__.'/../src/router.php';