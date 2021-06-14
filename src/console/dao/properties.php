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

class properties extends green\properties\dao\properties {
  protected function _unMangle( string $street) {
    $o = new dto\street;
    $_street = $street;

    if ( preg_match( '@[0-9]{4}$@', $_street)) {
      $o->postcode = substr( $_street, -4);
      $_street = trim( preg_replace('@[0-9]{4}$@', '', $_street));

      if ( (int)$o->postcode >= 4000 && (int)$o->postcode < 5000 ) {
        $o->state = 'QLD';

      }

    }

    if ( preg_match( '@(QLD|NSW|TAS)$@', $_street)) {
      $o->state = substr($_street, -3);
      $_street = trim( preg_replace('@(QLD|NSW|TAS)$@', '', $_street));

    }
    elseif ( !$o->state) {
      die( $_street);

    }

    $o->street = $_street;

    $a = explode(',', $_street);
    if ( 2 == count($a)) {
      $o->street = $a[0];
      $o->suburb = $a[1];

    }
    else {
      die($street);

    }

    if ( !preg_match('@(av|ave|blv|crs|Cresent|close|cls|cres|crt|dv|dr|drv|drive|rd| s|st|ln|lne|pl|plc|pde|tce|Esplanade|avenue|crescent|street|road|hotel|place|terrace)$@i', $o->street)) die( $o->street);

    $s = []; $r = [];
    $s[] = '@av$@i'; $r[] = 'Avenue';
    $s[] = '@ave$@i'; $r[] = 'Avenue';
    $s[] = '@blv$@i'; $r[] = 'Boulevard';
    $s[] = '@dr$@i'; $r[] = 'Drive';
    $s[] = '@drv$@i'; $r[] = 'Drive';
    $s[] = '@dv$@i'; $r[] = 'Drive';
    $s[] = '@rd$@i'; $r[] = 'Road';
    $s[] = '@cls$@i'; $r[] = 'Close';
    $s[] = '@crt$@i'; $r[] = 'Court';
    $s[] = '@crs$@i'; $r[] = 'Crescent';
    $s[] = '@cres$@i'; $r[] = 'Crescent';
    $s[] = '@Cresent$@i'; $r[] = 'Crescent';
    $s[] = '@ln$@i'; $r[] = 'Lane';
    $s[] = '@lne$@i'; $r[] = 'Lane';
    $s[] = '@st$@i'; $r[] = 'Street';
    $s[] = '@ s$@i'; $r[] = 'Street';
    $s[] = '@pl$@i'; $r[] = 'Place';
    $s[] = '@plc$@i'; $r[] = 'Place';
    $s[] = '@pde$@i'; $r[] = 'Parade';
    $s[] = '@tce$@i'; $r[] = 'Terrace';

    $o->street = preg_replace( $s, $r, $o->street);

    return $o;

  }

  public function getByStreet( string $street) {
    // \sys::logger( sprintf('<%s> %s', $street, __METHOD__));
    $o = $this->_unMangle( $street);
    // \sys::logger( sprintf('<%s> <%s, %s> %s', $street, $o->street, $o->postcode, __METHOD__));
    $sql = sprintf(
      'SELECT `id`
      FROM `properties`
      WHERE `address_street` = %s
        AND `address_suburb` = %s
        AND `address_state` = %s
        AND `address_postcode` = %s',
      $this->quote( $o->street),
      $this->quote( $o->suburb),
      $this->quote( $o->state),
      $this->quote( $o->postcode)

    );

    $id = 0;
    if ( $res = $this->Result( $sql)) {
      if ( $dto = $res->dto()) {
        $id = $dto->id;

      }
      else {
        $id = $this->Insert([
          'address_street' => $o->street,
          'address_suburb' => $o->suburb,
          'address_state' => $o->state,
          'address_postcode' => $o->postcode

        ]);

      }

    }

    if ($id) {
      return $this->getByID( $id);

    }

    return false;

  }

}
