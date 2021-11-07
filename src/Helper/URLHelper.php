<?php 
namespace App\Helper;

class URLHelper {


  /**
   * Checks if given route is the active route
   * if no second argument given, defaults to $_SERVER['REQUEST_URI']
   * 
   * @return string|null 'active' if active else null
   */
  public static function checkActive(string $comp1, ?string $comp2 = null): ?string 
  {
    if($comp2 === null) $comp2 = $_SERVER['REQUEST_URI'];

    return $comp1 === $comp2 ? 'active' : null;
  }

}

?>