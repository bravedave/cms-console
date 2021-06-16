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

  protected function getByGUID($guid) {
    if ($guid = (string)$guid) {
      if ($res = $this->Result(sprintf('SELECT * FROM `%s` WHERE GUID = "%s"', $this->_db_name, $this->escape($guid)))) {
        return ($res->dto());
      }
    }

    return (false);
  }

  protected function getFileIDs(object $dto) {
    /*
		 *	get the contacts associated with this console tenant
		 */
    $fileIDs = [];
    if ($res = db::contactLinks($dto->ID)) {
      while ($_dto = $res->dto()) {
        $fileIDs[] = $_dto->ContactID;
      }
    }

    return $fileIDs;
  }

  protected function getVersion() {
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

  public function Insert($a) {
    $a['version'] = $this->getVersion() + 1;
    return (parent::Insert($a));
  }

  public function import() {
    $debug = false;
    // $debug = true;

    if ($res = db::tenants()) {
      $stats = (object)[
        'new' => 0,
        'updated' => 0,
        'nochange' => 0,
        'total' => 0
      ];

      $seen = [];
      // this->Q('UPDATE console_tenants SET seen = 0');

      while ($_dto = $res->dto()) {
        $a = [];
        $stats->{'total'}++;
        if ($dto = $this->getByGUID((string)$_dto->GUID)) {
          $seen[] = $dto->id;
          if ($dto->TenantID != $_dto->ID) $a['TenantID'] = $_dto->ID;

          if ($dto->FileAs != $_dto->FileAs) {
            $a['FileAs'] = $_dto->FileAs;

            $ContactIDs = json_encode($this->getFileIDs($_dto));
            if ($dto->ContactIDs != $ContactIDs) $a['ContactIDs'] = $ContactIDs;

          }

          if ($dto->Mobile != trim($_dto->Mobile)) $a['Mobile'] = trim($_dto->Mobile);
          if ($dto->Email != trim($_dto->Email)) $a['Email'] = trim($_dto->Email);
          if ($dto->ContactID != $_dto->ContactID) $a['ContactID'] = $_dto->ContactID;
          if ($dto->Street != $_dto->Street) $a['Street'] = $_dto->Street;
          if ($dto->City != $_dto->City) $a['City'] = $_dto->City;
          if ($dto->State != $_dto->State) $a['State'] = $_dto->State;
          if ($dto->Postcode != $_dto->Postcode) $a['Postcode'] = $_dto->Postcode;
          if ($dto->ConsolePropertyID != $_dto->PropertyID) $a['ConsolePropertyID'] = $_dto->PropertyID;
          if ($dto->Bond != $_dto->Bond) $a['Bond'] = $_dto->Bond;
          if ($dto->Rent != $_dto->Rent) $a['Rent'] = $_dto->Rent;
          if ($dto->Credit != $_dto->Credit) $a['Credit'] = $_dto->Credit;
          if ($dto->Period != $_dto->Period) $a['Period'] = $_dto->Period;
          if ($dto->LeaseTerm != $_dto->LeaseTerm) $a['LeaseTerm'] = $_dto->LeaseTerm;
          if ($dto->Key != $_dto->Key) $a['Key'] = $_dto->Key;
          if ((int)$dto->Inactive != (int)$_dto->Inactive) $a['Inactive'] = (int)$_dto->Inactive;
          if (is_null($_dto->PaidTo)) {
            if ($dto->PaidTo != '0000-00-00')
            $a['PaidTo'] = '0000-00-00';

          }
          elseif (date('Y-m-d', strtotime($dto->PaidTo)) != $_dto->PaidTo->format('Y-m-d')) {
            $a['PaidTo'] = $_dto->PaidTo->format('Y-m-d');

          }

          if (is_null($_dto->LeaseFirstStart)) {
            if ($dto->LeaseFirstStart != '0000-00-00')
            $a['LeaseFirstStart'] = '0000-00-00';
          } elseif (date('Y-m-d', strtotime($dto->LeaseFirstStart)) != $_dto->LeaseFirstStart->format('Y-m-d')) {
            $a['LeaseFirstStart'] = $_dto->LeaseFirstStart->format('Y-m-d');
          }

          if (is_null($_dto->LeaseStart)) {
            if ($dto->LeaseStart != '0000-00-00')
            $a['LeaseStart'] = '0000-00-00';
          } elseif (date('Y-m-d', strtotime($dto->LeaseStart)) != $_dto->LeaseStart->format('Y-m-d')) {
            $a['LeaseStart'] = $_dto->LeaseStart->format('Y-m-d');
          }

          if (is_null($_dto->LeaseStop)) {
            if ($dto->LeaseStop != '0000-00-00') {
              $a['LeaseStop'] = '0000-00-00';
              //~ \sys::logger( sprintf('LeaseStop %s (%s) => %s', $dto->LeaseStop, strtotime( $dto->LeaseStop), $a['LeaseStop']));

            }
          } elseif (date('Y-m-d', strtotime($dto->LeaseStop)) != $_dto->LeaseStop->format('Y-m-d')) {
            $a['LeaseStop'] = $_dto->LeaseStop->format('Y-m-d');
            //~ \sys::logger( sprintf('LeaseStop %s => %s', $dto->LeaseStop, $a['LeaseStop']));

          }

          if (is_null($_dto->Vacating) || '' == $_dto->Vacating) {
            if ($dto->Vacating != '0000-00-00') {
              $a['Vacating'] = '0000-00-00';
              if ( $debug) \sys::logger( sprintf('Vacating %s (%s) => %s', $dto->Vacating, strtotime( $dto->Vacating), $a['Vacating']));

            }

          }
          elseif (date('Y-m-d', strtotime($dto->Vacating)) != $_dto->Vacating->format('Y-m-d')) {
            $a['Vacating'] = $_dto->Vacating->format('Y-m-d');
            if ( $debug) \sys::logger( sprintf('Vacating %s => %s', $dto->Vacating, $a['Vacating']));

          }

          if ($a) {
            if (count($a) > 1) {
              $stats->updated++;

            }
            else {
              $stats->nochange++;
              if ( $debug && PHP_SAPI === 'cli') echo '.';

            }
            $this->UpdateByID($a, $dto->id);
            if (count($a) > 1 && $debug) {

              foreach ( $a as $k => $v) \sys::logger( sprintf('update %s = %s', $k, $v));
              \sys::logger( sprintf('id %s', $dto->id));
              die;

            }

          }
          else {
            $stats->nochange++;
            if ( $debug && PHP_SAPI === 'cli') echo '.';

          }

        }
        else {
          $stats->{'new'}++;

          $a = [
            'TenantID' => $_dto->ID,
            'FileAs' => $_dto->FileAs,
            'Mobile' => $_dto->Mobile,
            'Email' => $_dto->Email,
            'ContactID' => $_dto->ContactID,
            'ContactIDs' => json_encode($this->getFileIDs($_dto)),
            'Street' => $_dto->Street,
            'City' => $_dto->City,
            'State' => $_dto->State,
            'Postcode' => $_dto->Postcode,
            'ConsolePropertyID' => $_dto->PropertyID,
            'Bond' => $_dto->Bond,
            'Rent' => $_dto->Rent,
            'LeaseTerm' => $_dto->LeaseTerm,
            'Inactive' => $_dto->Inactive,
            'Key' => $_dto->Key,
            'GUID' => (string)$_dto->GUID

          ];

          if (!is_null($_dto->PaidTo)) {
            $a['PaidTo'] = $_dto->PaidTo->format('Y-m-d');
          }

          if (!is_null($_dto->LeaseFirstStart)) {
            $a['LeaseFirstStart'] = $_dto->LeaseFirstStart->format('Y-m-d');
          }

          if (!is_null($_dto->LeaseStart)) {
            $a['LeaseStart'] = $_dto->LeaseStart->format('Y-m-d');
          }

          if (!is_null($_dto->LeaseStop)) {
            $a['LeaseStop'] = $_dto->LeaseStop->format('Y-m-d');
          }

          $seen[] = $this->Insert($a);

        }

      }

      // $this->Q( 'UPDATE console_tenants SET Inactive = 1 WHERE seen = 0');
      // $this->Q('DELETE FROM `console_tenants` WHERE seen = 0');
      // $this->Q('UPDATE `console_tenants` SET Inactive = 0');
      $sql = sprintf(
        'DELETE FROM `console_tenants` WHERE NOT id in (%s)',
        implode( ',', $seen)

      );

      // \sys::logSQL( sprintf('<%s> %s', $sql, __METHOD__));
      $this->Q( $sql);

      if ($stats->{'new'} || $stats->updated) {
        \sys::logger(sprintf(
          'update console_tenants : new %s updated %s no change %s (%s)',
          $stats->{'new'},
          $stats->updated,
          $stats->nochange,
          $stats->total
        ));
      }
    } else {
      \sys::logger('no console_tenants');
    }
  }

  public function UpdateByID($a, $id) {
    $a['version'] = $this->getVersion() + 1;
    return (parent::UpdateByID($a, $id));
  }

}