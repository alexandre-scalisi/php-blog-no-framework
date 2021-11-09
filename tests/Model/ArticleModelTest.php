<?php

use App\Model\Article;

require_once __DIR__ . '/BaseModel.php';

class ArticleModelTest extends BaseModel
{

  /** @var App\Model\Article */
  private $article;

  /** @before */
  public function start()
  {
    $this->startAll();
    $this->article = new Article($this->db);
  }

  public function testDatabaseWorks()
  {
    $article = $this->db->query("SELECT * FROM {$this->article->table_name}")->fetch(PDO::FETCH_OBJ);
    $this->assertEquals(1, $article->id);
  }

  public function testCountArticle()
  {
    $count = $this->article->count();
    $this->assertEquals(10, $count);
  }

  public function testSelectAllArticles()
  {
    $articles = $this->article->all();
    $this->assertEquals(10, count($articles));
    $this->assertInstanceOf(App\Model\Article::class, $articles[0]);
  }

  public function testSelectFirstArticle()
  {
    $article = $this->article->first();
    $this->assertInstanceOf(App\Model\Article::class, $article);
    $this->assertEquals(1, $article->id);
  }

  public function testFindByIdArticle1()
  {
    $article = $this->article->find(1);
    $this->assertInstanceOf(Article::class, $article);
    $this->assertEquals(1, $article->id);
  }

  public function testSelectCategoriesForArticle1()
  {
    /** @var Article */
    $article = $this->article->find(1);
    $categories = $article->categories();
    $this->assertEquals(1, $article->id);
    $this->assertNotEmpty($categories);
    $this->assertInstanceOf(App\Model\Category::class, $categories[0]);
    $this->assertEquals(1, $categories[0]->id);
  }

  public function testInsertArticle() {
    $params = [
      'title' => 'randomTitle',
      'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe ea eligendi sed perferendis minus quaerat recusandae dolore cum, nemo natus!',
      'categories' => [1, 8, 9],
      'new' => 3,
      'encore ' => 888
    ];
    $this->article->insert($params);
    $this->assertEquals(11, $this->article->count());
    $this->assertEquals(3, count($this->article->find(11)->categories()));
  }

}