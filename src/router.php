<?php

use App\App;
use App\Controller\ArticleController;
use App\Controller\AuthController;
use App\Controller\CategoryController;
use Mezon\Router\Router;

$router = App::get(Router::class);
$articleController = App::get(ArticleController::class);
$categoryController = App::get(CategoryController::class);
$authController = App::get(AuthController::class);

/* Article routes */
$router->addRoute('/', [$articleController, 'index'], 'GET', 'article_index');
$router->addRoute('/article/new', [$articleController, 'new'], 'GET', 'article_new');
$router->addRoute('/article/create', [$articleController, 'create'], 'POST', 'article_create');
$router->addRoute('/article/[i:id]', [$articleController, 'show'], 'GET', 'article_show');

/* Category routes */
$router->addRoute('/category', [$categoryController, 'index'], 'GET', 'category_index');

/* Authentication routes */
$router->addRoute('/login', [$authController, 'login'], ['GET','POST'], 'login');
// $router->addRoute('/register', [$authController, 'login'], 'GET', 'login');


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