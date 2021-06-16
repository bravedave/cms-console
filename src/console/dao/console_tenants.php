<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms\console\dao;

use cms\console\db;
use dao\_dao;

class console_tenants extends _dao {
  protected $_db_name = 'console_tenants';

  public function getVersion() {
    $sql = sprintf(
      'SELECT MAX(version) v FROM `%s`',
      $this->db_name()

    );

    if ($res = $this->Result($sql)) {
      if ($dto = $res->dto()) {
        return ((int)$dto->v);
      }
    }

    return (0);
  }

  public function UpdateByID($a, $id) {
    $a['version'] = $this->getVersion() + 1;
    return (parent::UpdateByID($a, $id));
  }

}