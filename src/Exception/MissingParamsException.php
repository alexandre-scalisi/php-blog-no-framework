<?php 
namespace App\Exception;

class MissingParamsException extends \Exception {
  public function __construct(array $params) {
    $diff_text = implode(', ', $params);
    parent::__construct("Missing parameters: $diff_text");
  }
}
?>