<?php 
namespace App\Controller;

class ArticleController {
  public function index() {
    
    return [
      'view' => 'articles/index.php'
    ];
  }
}

?>