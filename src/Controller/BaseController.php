<?php 
namespace App\Controller;

use App\Validation\Validator;

class BaseController {
  protected $validator;

  public function __construct(\PDO $db, Validator $validator) {
    $this->db = $db;
    $controller_name_explode = explode('\\', get_class($this));
    $model_name =  str_replace('Controller', '', array_pop($controller_name_explode));
    $class_name = "App\\Model\\$model_name";
    
    /** @var "App\\Model\\$model_name" */
    $this->{strtolower($model_name)} = new $class_name($db);
    $this->validator = $validator;
  }
}

?>