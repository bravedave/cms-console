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

use strings;

class creditors extends \Controller {
	protected $label = config::label_creditors;
  protected $viewPath = __DIR__ . '/views/';

  protected function _index() {
    $this->data = (object)[
      'creditors' => db::creditors(),
      'pageUrl' => strings::url( $this->route)

    ];

    // 'searchFocus' => false,
    $this->render([
      'title' => $this->title = $this->label,
      'primary' => 'creditors',
      'secondary' => 'index',
      'data' => (object)[
        'pageUrl' => $this->data->pageUrl

      ]

    ]);

  }

}