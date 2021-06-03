<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms\console;

class creditors extends \Controller {
	protected $label = config::label_creditors;
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {
    $this->data = (object)[
      'creditors' => db::creditors()

    ];

    $this->render([
      'primary' => 'creditors',
      'secondary' => 'index'

    ]);

  }

}