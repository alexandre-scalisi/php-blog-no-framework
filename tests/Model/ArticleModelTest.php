<?php

use App\Model\Article;

require_once __DIR__.'/ModelBase.php';

class ArticleModelTest extends ModelBase {
  
  private $article;

  
  /** @before */
  public function start() {
    $this->startAll();
    $this->article = new Article($this->db);
  }

  public function testDatabaseWorks() {
    $article = $this->db->query("SELECT * FROM {$this->article->table_name}")->fetch(PDO::FETCH_OBJ);
    $this->assertEquals(1, $article->id);
  }

  public function testCountArticle()
  {
    $count = $this->article->count();
    $this->assertEquals(10, $count);
  }
    
  public function testSelectAllArticles() {
    $articles = $this->article->all();
    $this->assertEquals(10, count($articles));
    $this->assertInstanceOf(App\Model\Article::class, $articles[0] );
  }
  
  public function testSelectFirstArticle() {
    $article = $this->article->first();
    $this->assertInstanceOf(App\Model\Article::class, $article);
    $this->assertEquals(1, $article->id);
  }

  

  
}