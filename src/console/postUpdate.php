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

use application;
use dvc\service;

class postUpdate extends service {
  protected function _upgrade() {
    config::route_register( 'console_creditors', 'cms\\console\\creditors');

    echo( sprintf('%s : %s%s', 'updated', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( application::startDir());
    $app->_upgrade();

  }

}
