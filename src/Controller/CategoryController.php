<?php 
namespace App\Controller;

class CategoryController extends BaseController{

  public function index() {
    
    return [
      'view' => 'categories/index.php'
    ];
  }
  
}