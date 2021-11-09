<?php

namespace App\Model;

use App\Exception\MissingParamsException;
use App\Helper\ModelHelper;
use App\Model\BaseModel;

class Article extends BaseModel
{

  public function categories(): ?array
  {
    $query =
      "SELECT c.* FROM article_category ac
      JOIN article a ON ac.article_id = a.id
      JOIN category c ON ac.category_id = c.id
     WHERE a.id = ? 
     ";
    $prepare = $this->db->prepare($query);
    $prepare->execute([$this->id]);
    return $prepare->fetchAll(\PDO::FETCH_CLASS, Category::class, [$this->db]) ?? null;
  }

  /**
   * @throws MissingParamsException
   */
  public function insert(array $params): bool
  {
    $validArticleParams = [
      'title', 'body'
    ];
    $articleResult = $this->insertHelper($validArticleParams, $params, 'article');
    if(!array_key_exists('categories', $params))
    throw new MissingParamsException(['categories']);
    foreach($params['categories'] as $category) {
      $validArticleCategoryParams = ['article_id', 'category_id'];
      
      $actualArticleCategoryParams = [
        'category_id' => $category,
        'article_id' => $this->count()
      ];
      
      $articleCategoryResult = $this->insertHelper($validArticleCategoryParams, $actualArticleCategoryParams, 'article_category');
    }
    
    return $articleResult && $articleCategoryResult;
  }
  /**
   * @throws MissingParamsException
   */
  private function insertHelper(array $expected, array $actual, string $tableName): bool {
    $sanitizedProperties = ModelHelper::sanitize($expected, array_keys($actual));
    ModelHelper::checkSameParameters($expected, $sanitizedProperties);
    
    $finalParams = array_filter($actual, function ($k) use ($sanitizedProperties) {
      return in_array($k, $sanitizedProperties);
    }, ARRAY_FILTER_USE_KEY);

    $setQuery = '';
    foreach($expected as $v) {
      $setQuery .= "$v = :$v,";
    }

    $setQuery = rtrim($setQuery, ',');
    
    $articleQuery = 
    "INSERT INTO $tableName 
      SET $setQuery
    ";

    $articleStmt = $this->db->prepare($articleQuery);
    return $articleStmt->execute($finalParams);
  }
}