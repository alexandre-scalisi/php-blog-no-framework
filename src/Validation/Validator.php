<?php 
namespace App\Helper;

class Validator {
  private $db;
  private static $instance;

  private function __construct(\PDO $db) {
    $this->db = $db;
  }

  public static function getInstance(\PDO $db) {
    if(self::$instance !== null) return self::$instance;
    
    self::$instance = new self($db);
    return self::$instance;
  }

  /**
   * @param array $options = [
   *    'min' => 0  (int) minimum value [default 0]
   *    'max' =>
   *        
   * ]
   */
  public function validateInt(array $options = [
    'min' => 0,
    'max' => 999999,
    
  ]){
    
  }
}

?>