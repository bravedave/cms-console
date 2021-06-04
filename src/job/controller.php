<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms\job;

class controller extends \Controller {
	protected $label = config::label;
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {
    $this->render([
      'title' => $this->title = $this->label,
      'primary' => 'blank',
      'secondary' => 'index'

    ]);

  }

  function contractors() {
    $dao = new dao\job_contractors;
    $this->data = (object)[
      'res' => $dao->getAll()

    ];

    $this->render([
      'title' => $this->title = config::label_contractors,
      'primary' => 'contractors',
      'secondary' => 'index'

    ]);

  }

}