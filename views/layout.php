<?php

use App\Helper\URLHelper; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/main.css">
  <script src="/assets/bundle.js"></script>

  <title><?= $pageTitle ?? 'Blog' ?></title>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Blog</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link <?= URLHelper::checkActive($router->reverse('article_index')) ?>"
            href="/<?= $router->reverse('article_index') ?>">Articles</a>
          <a class="nav-link <?= URLHelper::checkActive($router->reverse('category_index')) ?>"
            href="/<?= $router->reverse('category_index') ?>">Catégories</a>
        </div>
      </div>
    </div>
  </nav>


  <div class="container mt-5">
    <?php if (isset($_SESSION['errors'])) : ?>
    <div class=" alert alert-danger">
      <ul>
        <?php foreach ($_SESSION['errors'] as $k => $error) : ?>
        <li class="list-item">
          <?= $k . ': ' . $error ?>
        </li>
        <?php endforeach ?>
        <?php unset($_SESSION['errors']) ?>
      </ul>
    </div>
    <?php endif ?>

    <?= $content ?>
  </div>

</body>

</html>