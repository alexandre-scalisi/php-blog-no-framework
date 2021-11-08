<?php 
return [
  'DB_HOST' => $_ENV['DB_HOST'] ?? 'localhost',
  'DB_NAME' => $_ENV['DB_NAME'] ?? 'php_project_db',
  'DB_USER' => $_ENV['DB_USER'] ?? 'root',
  'DB_PASSWORD' => $_ENV['DB_PASSWORD'] ?? ''
];