<?php
session_start();

require_once '../vendor/autoload.php';

use App\Database;
use App\Helper\URLHelper;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$db = Database::getPDO();
require '../src/router.php';