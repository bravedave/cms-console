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
    echo( sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'import complete', __METHOD__, PHP_EOL));

    $dao->reconcile_people();
    echo( sprintf('%s: %s : %s%s', application::app()->timer()->elapsed(), 'reconsole complete', __METHOD__, PHP_EOL));

  }

  protected function _upgrade() {
    config::route_register( 'console_creditors', 'cms\\console\\creditors');

    config::cms_console_checkdatabase();

    green\baths\config::green_baths_checkdatabase();
    green\beds_list\config::green_beds_list_checkdatabase();

    green\people\config::green_people_checkdatabase();
    green\properties\config::green_properties_checkdatabase();
    green\property_diary\config::green_property_diary_checkdatabase();
    green\property_type\config::green_property_type_checkdatabase();
    green\postcodes\config::green_postcodes_checkdatabase();
    green\users\config::green_users_checkdatabase();

    echo( sprintf('%s : %s%s', 'updated', __METHOD__, PHP_EOL));

  }

  protected function _upgrade_dev() {
    config::route_register( 'people', 'green\\people\\controller');
    config::route_register( 'properties', 'green\\properties\\controller');
    config::route_register( 'beds', 'green\\beds_list\\controller');
    config::route_register( 'baths', 'green\\baths\\controller');
    config::route_register( 'property_type', 'green\\property_type\\controller');
    config::route_register( 'postcodes', 'green\\postcodes\\controller');
    config::route_register( 'users', 'green\\users\\controller');

    echo( sprintf('%s : %s%s', 'updated (dev)', __METHOD__, PHP_EOL));

  }

  static function upgrade() {
    $app = new self( application::startDir());
    $app->_upgrade();

  }

  static function upgrade_dev() {
    $app = new self( application::startDir());
    $app->_upgrade_dev();

  }

  static function import_contacts() {
    $app = new self( application::startDir());
    $app->_import_contacts();

  }

}
