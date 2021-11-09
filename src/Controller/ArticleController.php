<?php

namespace App\Controller;

use App\Model\Category;

class ArticleController extends BaseController
{
  /** @var App\Model\Article */
  public $article;


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

  public function create()
  {
    $this->article->insert($_POST);
    header('Location: /', true, 301);
    die();
  }
}