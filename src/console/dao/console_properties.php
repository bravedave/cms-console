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

class console_properties extends _dao {
	protected $_db_name = 'console_properties';

  protected function getByGUID($guid) {  // deprecating
    if ($guid = (string)$guid) {
      if ($res = $this->Result(sprintf('SELECT * FROM `%s` WHERE GUID = "%s"', $this->_db_name, $this->escape($guid)))) {
        return ($res->dto());
      }
    }

    return (false);
  }

  public function import() {
    if ($res = db::properties()) {
      $stats = (object)[
        'new' => 0,
        'updated' => 0,
        'nochange' => 0,
        'total' => 0
      ];

      $all = [];
      if ($_res = $this->Result('SELECT `id` FROM `console_properties`')) {
        while ($_dto = $_res->dto()) {
          $all[] = $_dto->id;
        }
      }

      while ($_dto = $res->dto()) {
        $stats->{'total'}++;
        if ($dto = $this->getByGUID((string)$_dto->GUID)) {
          $index = array_search($dto->id, $all);
          if ($index !== false) {
            unset($all[$index]);
          }

          $a = [];
          if ($dto->ConsoleID != $_dto->ID)
            $a['ConsoleID'] = $_dto->ID;

          if ($dto->ConsoleOwnerID != $_dto->OwnerID)
            $a['ConsoleOwnerID'] = $_dto->OwnerID;

          if ($dto->Street != $_dto->Street)
            $a['Street'] = $_dto->Street;

          if ($dto->City != $_dto->City)
            $a['City'] = $_dto->City;

          if ($dto->State != $_dto->State)
            $a['State'] = $_dto->State;

          if ($dto->Postcode != $_dto->Postcode)
            $a['Postcode'] = $_dto->Postcode;

          if ((int)$dto->Period != (int)$_dto->Period)
            $a['Period'] = (int)$_dto->Period;

          if ((float)$dto->Rent != (float)$_dto->Rent)
            $a['Rent'] = (float)$_dto->Rent;

          if ($dto->LetFee != $_dto->LetFee)
            $a['LetFee'] = $_dto->LetFee;

          if ($dto->Bedrooms != $_dto->Bedrooms)
            $a['Bedrooms'] = $_dto->Bedrooms;

          if ($dto->Bathrooms != $_dto->Bathrooms)
            $a['Bathrooms'] = $_dto->Bathrooms;

          if ($dto->Furnished != $_dto->Furnished)
            $a['Furnished'] = $_dto->Furnished;

          if ($dto->Fenced != $_dto->Fenced)
            $a['Fenced'] = $_dto->Fenced;

          if ($dto->Pets != $_dto->Pets)
            $a['Pets'] = $_dto->Pets;

          if ($dto->CarAccomm != $_dto->CarAccomm)
            $a['CarAccomm'] = $_dto->CarAccomm;

          if ($dto->Zone != $_dto->Zone)
            $a['Zone'] = $_dto->Zone;

          if ($dto->Type != $_dto->Type)
            $a['Type'] = $_dto->Type;

          if ($dto->Key != $_dto->Key)
            $a['Key'] = $_dto->Key;

          if ((int)$dto->Inactive != (int)$_dto->Inactive)
            $a['Inactive'] = (int)$_dto->Inactive;

          if ($dto->PropertyManager != $_dto->PropertyManager)
            $a['PropertyManager'] = $_dto->PropertyManager;

          if (count($a)) {
            $stats->updated++;
            //~ foreach ( $a as $k => $v) {
            //~ \sys::logger( sprintf('update %s = %s', $k, $v));

            //~ }
            //~ break;
            $this->UpdateByID($a, $dto->id);
          } else {
            $stats->nochange++;
          }
        } else {
          $stats->{'new'}++;

          $this->Insert([
            'ConsoleID' => $_dto->ID,
            'ConsoleOwnerID' => $_dto->OwnerID,
            'Street' => $_dto->Street,
            'City' => $_dto->City,
            'State' => $_dto->State,
            'Postcode' => $_dto->Postcode,
            'Rent' => $_dto->Rent,
            'LetFee' => $_dto->LetFee,
            'Bedrooms' => $_dto->Bedrooms,
            'Bathrooms' => $_dto->Bathrooms,
            'Furnished' => $_dto->Furnished,
            'Fenced' => $_dto->Fenced,
            'Pets' => $_dto->Pets,
            'CarAccomm' => $_dto->CarAccomm,
            'Zone' => $_dto->Zone,
            'Type' => $_dto->Type,
            'Key' => $_dto->Key,
            'PropertyManager' => $_dto->PropertyManager,
            'GUID' => $_dto->GUID

          ]);
        }
      }

      foreach ($all as $e) {
        $this->delete($e);
        \sys::logger(sprintf('delete : %s', $e));
      }

      if ($stats->{'new'} || $stats->updated) {
        \sys::logger(sprintf(
          'update console_properties : new %s updated %s no change %s, inactive %s (%s)',
          $stats->{'new'},
          $stats->updated,
          $stats->nochange,
          count($all),
          $stats->total
        ));
      }
    } else {
      \sys::logger('no console_properties');
    }
  }

}
