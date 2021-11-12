<div class="card p-5">
  <h2 class="mb-4">Se connecter</h2>
  <form action="/<?= $router->reverse('login') ?>" method="POST" class="form">
    <div class="form-group mb-3">
      <label for="username" class="visually-hidden">Pseudo</label>
      <input type="text" name="username" id="username" class="form-control" placeholder="Pseudo" required>
    </div>
    <div class="form-group mb-3">
      <label for="password" class="visually-hidden">Mot de passe</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
</div>