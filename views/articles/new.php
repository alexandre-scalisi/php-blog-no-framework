<div class="card p-5">
  <h2 class="mb-4">Publish a new article</h2>
  <form action="/<?= $router->reverse('article_create') ?>" method="POST" class="form">
    <div class="form-group mb-3">
      <label for="title" class="visually-hidden">Title</label>
      <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
      <?php if (isset($_SESSION['errors']) && isset($_SESSION['errors']['title'])) : ?>
      <ul class="ps-3">
        <?php foreach ($_SESSION['errors']['title'] as $error) : ?>
        <li class="text-danger"><?= $error ?></li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
    </div>
    <div class="form-group mb-3">
      <label for="body" class="visually-hidden">Message</label>
      <textarea name="body" id="body" cols="30" rows="10" placeholder="Message" class="form-control"
        required></textarea>
      <?php if (isset($_SESSION['errors']) && isset($_SESSION['errors']['body'])) : ?>
      <ul class="ps-3">
        <?php foreach ($_SESSION['errors']['body'] as $error) : ?>
        <li class="text-danger"><?= $error ?></li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
    </div>

    <div class="form-group mb-4">
      <h5>Choose your categories</h5>
      <div class="row m-0 g-3">
        <?php foreach ($categories as $category) : ?>
        <div class="col-3 p-0">
          <input type="checkbox" name="categories[]" id="<?= $category->name ?>" value="<?= $category->id ?>"
            class="me-2">
          <label for="<?= $category->name ?>"><?= $category->name ?></label>
        </div>
        <?php endforeach ?>
      </div>
      <?php if (isset($_SESSION['errors']) && isset($_SESSION['errors']['categories'])) : ?>
      <ul class="ps-3">
        <?php foreach ($_SESSION['errors']['categories'] as $error) : ?>
        <li class="text-danger"><?= $error ?></li>
        <?php endforeach ?>
      </ul>
      <?php endif ?>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
  </form>
</div>