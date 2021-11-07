<?php

use App\Controller\ArticleController;
use Mezon\Router\Router;

$router = new Router();

$articleController = new ArticleController();

$router->addRoute('/', [$articleController, 'index'], 'GET', 'article_index');

try {
  $returnedValues = $router->callRoute($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
  require_once '../views/404.php';
}

if (!empty($returnedValues)) {
  extract($returnedValues);

  $content = "";
  if ($view) {
    ob_start();
    require_once '../views/' . $view;
    $content = ob_get_clean();
  }

  require_once '../views/layout.php';
}