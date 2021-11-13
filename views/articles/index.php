<h1 class="mb-4">Articles</h1>
<a href="<?= $router->reverse('article_new')?>" class="btn btn-primary mb-4">New</a>
<div class="row g-5">
  <?php foreach ($articles as $article) : ?>
  <div class=" col-4">

    <div class="card">
      <div class="card-header">
        <a href="/<?= $router->reverse('article_show', ['id' => $article->id ]) ?>"
          class="card-title"><?= $article->title ?></a>
      </div>
      <ul class=" list-group list-group-flush">
        <li class="list-group-item"><?= $article->published_at ?></li>
        <li class="list-group-item"><?= $article->body ?></li>
        <li class="list-group-item">
          <?php foreach ($article->categories() as $category) : ?>
          <a href="#"><?= $category->name ?></a>
          <?php endforeach ?>
        </li>
      </ul>
    </div>
  </div>
  <?php endforeach ?>

</div>