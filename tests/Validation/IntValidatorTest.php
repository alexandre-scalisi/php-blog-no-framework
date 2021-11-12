<?php

use App\Helper\Validator;

require_once __DIR__ . '/../Model/DatabaseInitializer.php';

class IntValidatorTest extends DatabaseInitializer {
  /** @before */
  public function init() {
    $this->initDatabase();
    $_SESSION['errors'] = [];
    $this->validator = Validator::getInstance($this->db);
  }
  
  public function testValidate1IsInt() {
    
  } 
}

?>