<?php

namespace App\Model;

use PDO;

class BaseModel
{
  /** @var PDO */
  protected $db;

  /** @var string */
  public $table_name;

  public function __construct(PDO $db)
  {
    $this->db = $db;
    $splittedClassName = explode('\\', get_class($this));
    $this->table_name = strtolower(array_pop($splittedClassName));
  }

  public function all(): ?array
  {
    $query = "SELECT * FROM $this->table_name";

    return $this->db->query($query)->fetchAll(PDO::FETCH_CLASS, static::class, [$this->db]) ?? null;
  }

  public function first(): ?self
  {
    $query = "SELECT * FROM $this->table_name";
    return $this->db->query($query)->fetchObject(static::class, [$this->db]) ?? null;
  }

  public function find(int $id): ?self
  {
    $query = "SELECT * FROM $this->table_name WHERE id = ?";
    $prepare = $this->db->prepare($query);
    $prepare->execute([$id]);
    return $prepare->fetchObject(static::class, [$this->db]) ?? null;
  }

  public function count(): int
  {    
    return (int)$this->db->query("SELECT COUNT(id) as count FROM $this->table_name")->fetchObject(self::class, [$this->db])->count;
  }
}