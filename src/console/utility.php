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
use green;

class utility extends service {
  protected function _import_contacts() {
    $dao = new dao\console_contacts;

    $dao->import();
    echo (sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'import complete', __METHOD__, PHP_EOL));

    $dao->reconcile_people();
    echo (sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'reconsole complete', __METHOD__, PHP_EOL));
  }

  protected function _import_maintenance() {

    $dao = new dao\console_owners_maintenance;
    $dao->import();

    echo (sprintf(
      '%s: %s : %s%s',
      application::app()->timer()->elapsed(),
      'import complete',
      __METHOD__,
      PHP_EOL

    ));
  }

  protected function _import_properties() {
    $dao = new dao\console_properties;
    $dao->import();
    // $dao = new dao\console_contacts;

    // $dao->import();
    // echo( sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'import complete', __METHOD__, PHP_EOL));

    $dao->reconcile_properties();
    echo (sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'reconsole complete', __METHOD__, PHP_EOL));
  }

  protected function _import_owners() {
    $dao = new dao\console_owners;
    $dao->import();

    echo (sprintf(
        '%s: %s : %s%s',
        application::app()->timer()->elapsed(),
        'import owners complete',
        __METHOD__,
        PHP_EOL
      ));
  }

  protected function _import_tenants() {
    $dao = new dao\console_tenants;
    $dao->import();

    echo (sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'import tenants complete', __METHOD__, PHP_EOL));
  }

  protected function _upgrade() {
    config::route_register('console_creditors', 'cms\\console\\creditors');

    green\people\config::green_people_checkdatabase();
    green\properties\config::green_properties_checkdatabase();
    green\users\config::green_users_checkdatabase();

    config::cms_console_checkdatabase();

    echo (sprintf('%s : %s%s', 'updated', __METHOD__, PHP_EOL));
  }

  protected function _upgrade_dev() {
    config::route_register('people', 'green\\people\\controller');

    echo (sprintf('%s : %s%s', 'updated (dev)', __METHOD__, PHP_EOL));
  }

  static function upgrade() {
    $app = new self(application::startDir());
    $app->_upgrade();
  }

  static function upgrade_dev() {
    $app = new self(application::startDir());
    $app->_upgrade_dev();
  }

  static function import_contacts() {
    $app = new self(application::startDir());
    $app->_import_contacts();
  }

  static function import_maintenance() {
    $app = new self(application::startDir());
    $app->_import_maintenance();
  }

  static function import_properties() {
    $app = new self(application::startDir());
    $app->_import_properties();
  }

  static function import_owners() {
    $app = new self(application::startDir());
    $app->_import_owners();
  }

  static function import_tenants() {
    $app = new self(application::startDir());
    $app->_import_tenants();
  }
}
