<?php

use App\App;
use App\Database;
use App\Validation\Validator;
use Database\Migrations\Migration;
use Database\Seeders\Seeder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;


class ValidatorTest extends TestCase
{
  /** @var Validator */
  private $validator;


  /** @before */
  public function init()
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
    $dbConfig = require __DIR__ . '/../../config/database.php';
    $_ENV['ENV'] = 'test';
    App::set(PDO::class, function () use ($dbConfig) {
      return Database::getPDO($dbConfig);
    });
    $_SESSION['errors'] = [];
    App::get(Migration::class)->startAll();
    App::get(Seeder::class)->startAll();
    $this->validator = App::get(Validator::class);
  }

  

  public function testValidateNotRequiredWithoutValue()
  {
    $this->validator->validate('abc', null, [
      'type' => 'int',
      'required' => false,
      'min' => 301,
      'max' => 400
    ]);

    $this->assertEmpty($_SESSION['errors']);
  }
  
  public function testValidateNotRequiredButWithValue()
  {
    $this->validator->validate('abc', 100, [
      'type' => 'int',
      'required' => false,
      'min' => 301,
      'max' => 400
    ]);

    $this->assertNotEmpty($_SESSION['errors']['abc']['min']);
  }
  
  public function testValidate1IsInt()
  {

    $_POST['name'] = 1;
    $this->validator->validate('name', $_POST['name'], [
      'int'
    ]);

    $this->assertEmpty($_SESSION['errors']);
  }

  public function testValidateStringLength() {
    $this->validator->validate('name', 'alex', [
      'required',
      'min'=> 1,
      'max' => 10 
    ]);

    $this->assertEmpty($_SESSION['errors']);

  }

  public function testNotEmail() {
    $this->validator->validate('wrong-email', 'wrongemail@wrongemail.', [
      'type' => 'email',
      'required' => true,
      'min' => 5
    ]);

    $this->assertNotEmpty($_SESSION['errors']['wrong-email']['type']);
  }
  public function testEmail() {
    $this->validator->validate('email', 'wrongemail@wrongemail.fr', [
      'type' => 'email',
      'required' => true,
      'min' => 5
    ]);

    $this->assertEmpty($_SESSION['errors']);
  }

  public function testUnique()
  {
    $this->validator->validate('username', 'non existing user', [
      'type' => 'string',
      'unique' => ['user', 'username']
    ]);
    $this->assertEmpty($_SESSION['errors']);
  }
  
  public function testNotUnique()
  {
    $this->validator->validate('username', 'username1', [
      'type' => 'string',
      'unique' => ['user', 'username']
    ]);
    $this->assertNotEmpty($_SESSION['errors']['username']['unique']);
  }

  public function testPassword() {
    $this->validator->validate('password', 'password', [
      'type' => 'password',
      'passwordConfirm' => ['passwordConfirm', 'password'],
      'min' => 10,
      'required' => true
    ]);
    $this->assertEmpty($_SESSION['errors']);
  }
  
  public function testPasswordWithoutPasswordConfirmValue() {
    $this->expectException(Exception::class);
    $this->validator->validate('password', null, [
      'type' => 'password',
      'passwordConfirm' => ['passwordConfirm'],
      'min' => 10,
      'required' => true
    ]);
  }
  
  public function testPasswordWithoutPasswordConfirmValueButNotRequired() {
    $this->expectException(Exception::class);
    $this->validator->validate('password', null, [
      'type' => 'password',
      'passwordConfirm' => ['passwordConfirm'],
      'min' => 10,
      'required' => false
    ]);

    $this->assertEmpty($_SESSION['errors']);
  }

  public function testPasswordWithWrongPasswordConfirm() {
    $this->validator->validate('password', 'alex', [
      'type' => 'password',
      'passwordConfirm' => ['passwordConfirm', 'zijij'],
      'min' => 10,
      'required' => true
    ]);
    $this->assertNotEmpty($_SESSION['errors']['password']['passwordConfirm']);
  }
  

  
}