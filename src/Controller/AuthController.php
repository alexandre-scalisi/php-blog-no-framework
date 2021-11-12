<?php 
namespace App\Controller;

class AuthController{
  
  
  public function __construct(\PDO $db)
  {
   $this->db = $db; 
  }


  public function login() {
    
    return [
      'view' => 'auth/login.php'
    ];
  }
}
    
?>