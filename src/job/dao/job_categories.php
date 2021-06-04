<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms\job\dao;

use cms;
use dao\_dao;
use green;
// use strings;
// use sys;

class job_categories extends _dao {
  protected $_db_name = 'job_categories';

  function getByCategory( string $category, bool $autoAdd = false) : ?\dao\dto\dto {
    $sql = sprintf(
      'SELECT
        *
      FROM
        `%s`
      WHERE
        `category` = "%s"',
      $this->db_name(),
      $this->escape( $category)

    );

    if ( $res = $this->Result( $sql)) {
      if ( $dto = $res->dto()) {
        return $dto;

      }
      elseif ( $autoAdd) {
        $id = $this->Insert(['category' => $category]);
        if ( $dto = $this->getByID( $id)) {
          return $dto;

        }

      }

    }

    return null;

  }

}
