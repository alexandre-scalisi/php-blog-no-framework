<?php

namespace App\Controller;

use App\App;
use App\Helper\ModelHelper;
use App\Model\Article;
use App\Model\Category;
use App\Validation\Validator;

class ArticleController extends BaseController
{
  /** @var App\Model\Article */
  protected $article;


  public function index()
  {
    $articles = $this->article->all();
    return [
      'view' => 'articles/index.php',
      'articles' => $articles
    ];
  }

  public function new()
  {
    $category = new Category($this->db);
    $categories = $category->all();

    return [
      'view' => 'articles/new.php',
      'categories' => $categories
    ];
  }

  public function show(string $route, array $params) {
    
  }

  public function create(string $route)
  {
    $this->validator
      ->validate('title', $_POST['title'] ?? null, [
        'type' => 'string',
        'required' => true,
        'min' => 5,
        'max' => 50
      ])
      ->validate('body', $_POST['body'] ?? null, [
        'type' => 'string',
        'required' => true,
        'min' => 10,
        'max' => 9999999
      ])
      ->validate('categories', $_POST['categories'] ?? null, [
        'required' => true,
      ]);
    if ($_SESSION['errors']) {
      header('Location: /article/new', true, 301);
      exit();
    }


    $this->article->insert($_POST);

    $_SESSION['success'] = ['Article created with success'];
    header("Location: $route", true, 301);
    die();
  }

}