<div class="card p-5">
  <h2 class="mb-4">Publier un nouvel article</h2>
  <form action="/<?= $router->reverse('article_create') ?>" method="POST" class="form">
    <div class="form-group mb-3">
      <label for="title" class="visually-hidden">Titre</label>
      <input type="text" name="title" id="title" class="form-control" placeholder="Titre" required>
    </div>
    <div class="form-group mb-3">
      <label for="body" class="visually-hidden">Message</label>
      <textarea name="body" id="body" cols="30" rows="10" placeholder="Ecrivez votre article" class="form-control"
        required></textarea>
    </div>

    <div class="form-group mb-4">
      <h5>Selectionnez vos catégories</h5>
      <div class="row m-0 g-3">
        <?php foreach ($categories as $category) : ?>
        <div class="col-3 p-0">
          <input type="checkbox" name="categories[]" id="<?= $category->name ?>" value="<?= $category->id ?>"
            class="me-2">
          <label for="<?= $category->name ?>"><?= $category->name ?></label>
        </div>
        <?php endforeach ?>
      </div>

    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
  </form>
</div>