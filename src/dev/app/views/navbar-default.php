<?php
/**
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 */	?>

<style>
@media screen and (max-width: 767px) {
  .navbar-brand {max-width: 70%;}
}
@media screen and (min-width: 768px) {
  .navbar-brand {min-width: 30%;}
}
</style>
<nav class="navbar navbar-expand-md navbar-dark bg-primary sticky-top" role="navigation" >
	<div class="container-fluid">
    <div class="navbar-brand text-truncate"><?= $this->data->title	?></div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#<?= $_uid = strings::rand() ?>"
      aria-controls="<?= $_uid ?>" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="<?= $_uid ?>">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= strings::url('console_creditors') ?>">
            Creditors

          </a>

        </li>

        <li class="nav-item dropdown">
          <a class="nav-link pb-0 dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-label="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= dvc\icon::get( dvc\icon::gear ) ?>

          </a>

          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?= strings::url('people') ?>">People</a>

          </div>

        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= strings::url() ?>">
            <?= dvc\icon::get( dvc\icon::house ) ?>
            <span class="sr-only">home</span>

          </a>

        </li>

        <li class="nav-item">
          <a class="nav-link" href="https://github.com/bravedave/">
            <?= dvc\icon::get( dvc\icon::github ) ?>
            <span class="sr-only">github</span>

          </a>

        </li>

      </ul>

    </div>

  </div>

</nav>
