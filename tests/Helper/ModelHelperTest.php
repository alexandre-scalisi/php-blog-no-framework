<?php

use PHPUnit\Framework\TestCase;
use App\Exception\MissingParamsException;
use App\Helper\ModelHelper;

class ModelHelperTest extends TestCase {
  public function testSanitize() {
    $expected = [
      'id', 'title', 'body', 'published_at', 'categories'
    ];
    $to_sanitize = [
      'id', 'categories', 'published_at', 'idontknow', 1555
    ];
    
    $sanitized = ModelHelper::sanitize($expected, $to_sanitize);
    $this->assertEmpty(array_diff($sanitized, $expected));
  }
  
  public function testCheckSameParametersThrowsErrorIfMissingParam() {
    $this->expectException(MissingParamsException::class);
    $expected = [
      'id', 'title', 'body', 'published_at', 'categories'
    ];
    $to_sanitize = [
      'id', 'categories', 'published_at'
    ];

    ModelHelper::checkSameParameters($expected, $to_sanitize);
  }
}