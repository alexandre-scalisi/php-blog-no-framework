<?php 
namespace App\Controller;

class CategorieController {

  public function index() {
    
    return [
      'view' => 'categories/index.php'
    ];
  }
  
}