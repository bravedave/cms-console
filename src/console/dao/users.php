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

use green;

class users extends green\users\dao\users {

  public function getByConsoleCode( string $code) {
    if ( $code) {
      $sql = sprintf(
        'SELECT `id`
        FROM `users`
        WHERE `console_code` = %s',
        $this->quote( $code)

      );

      $id = 0;
      if ( $res = $this->Result( $sql)) {
        if ( $dto = $res->dto()) {
          $id = $dto->id;

        }
        else {
          $id = $this->Insert([
            'name' => $code,
            'console_code' => $code

          ]);

        }

      }

      if ($id) {
        return $this->getByID( $id);

      }

    }

    return false;

  }

}
