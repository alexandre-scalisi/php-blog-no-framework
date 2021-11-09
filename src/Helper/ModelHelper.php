<?php 
namespace App\Helper;

use App\Exception\MissingParamsException;

class ModelHelper {
  public static function sanitize(array $expected, array $to_sanitize):array {
    return array_filter($to_sanitize, function ($v) use ($expected) {
      return in_array($v, $expected);
    });
  }

  /** 
   * Must sanitize before
   */
  public static function checkSameParameters($expected, $actual) {
    if(count($expected) === count($actual)) return true;

    $diff = array_diff($expected, $actual);

    throw new MissingParamsException($diff);
  }
}

?>