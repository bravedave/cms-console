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
use green;
use strings;
use sys;

class console_contacts extends _dao {
	protected $_db_name = 'console_contacts';

	protected function getGUIDReferenceSet() {
		$ref = [];
		if ( $res = $this->Result( sprintf( 'SELECT * FROM `%s`', $this->_db_name))) {
			while ( $dto = $res->dto()) {
				if ( $dto->GUID) {
          $dto->active_in_console = false;
					$ref[$dto->GUID] = $dto;

				}
				else {
					sys::logger( sprintf('<no guid set (%s)> %s', $dto->id, __METHOD__));

				}

			}

		}

		return ( $ref);

	}

	protected function getTenantIDs(  object $dto) {	// deprecate
		$tenantIDs = [];
		if ( $res = db::tenantLinks( $dto->ID)) {
			while ( $_dto = $res->dto()) {
				$tenantIDs[] = $_dto->FileID;

			}

		}

		//~ \sys::logger( json_encode( $tenantIDs));
		return $tenantIDs;

	}

  public function getByContactID( int $contactID) : ?\dao\dto\dto {
    $sql = sprintf(
      'SELECT
        *
      FROM
        `%s`
      WHERE
        `ConsoleID` = %d',
      $this->db_name(),
      $contactID

    );

    if ( $res = $this->Result( $sql)) {
      if ($dto = $res->dto()) {
        return $dto;

      }

    }

    return null;

  }

  public function import() {
		if( $res = db::contacts()) {
			$stats = (object)[
				'new' => 0,
				'updated' => 0,
        'nochange' => 0,
        'deleted' => 0,
				'total' => 0
			];

      $all = [];
      if ($_res = $this->Result('SELECT `id` FROM `console_contacts`')) {
        while ($_dto = $_res->dto()) {
          $all[] = $_dto->id;
        }
      }

			$ref = $this->getGUIDReferenceSet();
			while ( $_dto = $res->dto()) {
				$stats->{'total'} ++;

        $dto = false;
        if ( isset( $ref[ (string)$_dto->GUID])) {
          $ref[ (string)$_dto->GUID]->active_in_console = true;
					$dto = $ref[ (string)$_dto->GUID];

        }

				if ( $dto) {
				//~ if ( $dto = $this->getByGUID( (string)$_dto->GUID)) {

          $index = array_search($dto->id, $all);
          if ($index !== false) {
            unset($all[$index]);
          }

					//~ continue;
					$a = [];

					if ( $dto->ConsoleID != $_dto->ID) $a['ConsoleID'] = $_dto->ID;
					if ( $dto->FileAs != $_dto->FileAs) {
						$a['FileAs'] = $_dto->FileAs;

						$TenantIDs = json_encode( $this->getTenantIDs( $_dto));
						if ( $dto->TenantIDs != $TenantIDs)
							$a['TenantIDs'] = $TenantIDs;

					}
					if ( $dto->Title != $_dto->Title) $a['Title'] = $_dto->Title;
					if ( $dto->First != $_dto->First) $a['First'] = $_dto->First;
					if ( $dto->Middle != $_dto->Middle) $a['Middle'] = $_dto->Middle;
					if ( $dto->Last != $_dto->Last) $a['Last'] = $_dto->Last;
					if ( $dto->Company != $_dto->Company) $a['Company'] = $_dto->Company;
					if ( $dto->HomeStreet != $_dto->HomeStreet) $a['HomeStreet'] = $_dto->HomeStreet;
					if ( $dto->HomeCity != $_dto->HomeCity) $a['HomeCity'] = $_dto->HomeCity;
					if ( $dto->HomeState != $_dto->HomeState) $a['HomeState'] = $_dto->HomeState;
					if ( $dto->HomePostcode != $_dto->HomePostcode) $a['HomePostcode'] = $_dto->HomePostcode;
					if ( $dto->HomeCountry != $_dto->HomeCountry) $a['HomeCountry'] = $_dto->HomeCountry;
					if ( $dto->MailingStreet != $_dto->MailingStreet) $a['MailingStreet'] = $_dto->MailingStreet;
					if ( $dto->MailingCity != $_dto->MailingCity) $a['MailingCity'] = $_dto->MailingCity;
					if ( $dto->MailingState != $_dto->MailingState) $a['MailingState'] = $_dto->MailingState;
					if ( $dto->MailingPostcode != $_dto->MailingPostcode) $a['MailingPostcode'] = $_dto->MailingPostcode;
					if ( $dto->MailingCountry != $_dto->MailingCountry) $a['MailingCountry'] = $_dto->MailingCountry;
					if ( $dto->Salutation != $_dto->Salutation) $a['Salutation'] = $_dto->Salutation;
					if ( $dto->Business != $_dto->Business) $a['Business'] = $_dto->Business;
					if ( $dto->Home != $_dto->Home) $a['Home'] = $_dto->Home;
					if ( $dto->Mobile != trim( $_dto->Mobile)) $a['Mobile'] = trim( $_dto->Mobile);
					if ( $dto->Email != trim( $_dto->Email)) $a['Email'] = trim( $_dto->Email);

					if ( count( $a)) {
						$stats->updated ++;
						$this->UpdateByID( $a, $dto->id);

					}
					else {
						$stats->nochange ++;

					}

				}
				else {
					$stats->{'new'} ++;

					$this->Insert([
						'ConsoleID' => $_dto->ID,
						'FileAs' => $_dto->FileAs,
						'Title' => $_dto->Title,
						'First' => $_dto->First,
						'Middle' => $_dto->Middle,
						'Last' => $_dto->Last,
						'HomeStreet' => $_dto->HomeStreet,
						'HomeCity' => $_dto->HomeCity,
						'HomeState' => $_dto->HomeState,
						'HomePostcode' => $_dto->HomePostcode,
						'HomeCountry' => $_dto->HomeCountry,
						'MailingStreet' => $_dto->MailingStreet,
						'MailingCity' => $_dto->MailingCity,
						'MailingState' => $_dto->MailingState,
						'MailingPostcode' => $_dto->MailingPostcode,
						'MailingCountry' => $_dto->MailingCountry,
						'Salutation' => $_dto->Salutation,
						'Company' => $_dto->Company,
						'Business' => $_dto->Business,
						'Home' => $_dto->Home,
						'Mobile' => $_dto->Mobile,
						'Email' => $_dto->Email,
						'GUID' => (string)$_dto->GUID,
						'TenantIDs' => json_encode( $this->getTenantIDs( $_dto))

					]);

				/**
         * FileAs
         * Title
         * First
         * Middle
         * Last
         * HomeStreet
         * HomeCity
         * HomeState
         * HomePostcode
         * HomeCountry
         * MailingStreet
         * MailingCity
         * MailingState
         * MailingPostcode
         * MailingCountry
         * Business
         * Home
         * Mobile
         * Email
         * GUID
         */

				}

      }

      foreach ($all as $e) {
        $this->delete($e);
        \sys::logger(sprintf('delete : %s', $e));
      }

      $aDel = [];
      foreach ($ref as $cc) {
        if ( !$cc->active_in_console) {
          $aDel[] = $cc->id;
          $stats->deleted ++;

        }

      }

			if ( (bool)$stats->{'new'} || (bool)$stats->updated || (bool)$stats->deleted) {
        if ( $aDel) {
          $this->Q( sprintf(
            'DELETE FROM `%s` WHERE id IN (%s)',
            $this->_db_name,
            implode( ',', $aDel)
          ));

        }

				sys::logger(
          sprintf(
            '<new %s updated %s, no change %s, deleted %s (%s)> %s',
            $stats->{'new'},
            $stats->updated, $stats->nochange,
            $stats->deleted,
            $stats->total,
            __METHOD__

          )

        );

			}

		}
		else {
      sys::logger( sprintf('<%s> %s', 'no console_contacts', __METHOD__));

		}

	}

  public function reconcile_people() {
    $sql =
    'SELECT
      id,
      First, Middle, Last, Company, Salutation,
      Email, Mobile, Home, Business
    FROM
      `console_contacts`
    WHERE
      people_id = 0';
    if ( $res = $this->Result( $sql)) {
      $res->dtoSet( function( $dto) {
        $this->reconcile_person( $dto);

      });

    }

  }

  public function reconcile_person( $dto) {
    $a = [];
    if ( $dto->First) $a[] = $dto->First;
    if ( $dto->Middle) $a[] = $dto->Middle;
    if ( $dto->Last) $a[] = $dto->Last;

    if ( !$a) {
      if ( $dto->Company) $a[] = $dto->Company;

    }

    if ( $a) {
      $a = [
        'name' => implode( ' ', $a)

      ];
      if ( $dto->Salutation) $a['salutation'] = $dto->Salutation;

      if ( strings::isMobilePhone( $dto->Mobile)) $a['mobile'] = $dto->Mobile;
      if ( strings::isPhone( $dto->Home)) $a['phone'] = $dto->Home;
      if ( strings::isPhone( $dto->Business)) $a['telephone_business'] = $dto->Business;
      if ( strings::isEmail( $dto->Email)) $a['email'] = $dto->Email;

      $Pdto = green\people\dao\QuickPerson::find( $a);
      if ( isset( $Pdto->errorText)) {
        \sys::logger( sprintf('<%s> <%s> %s', $a['name'], $Pdto->errorText, __METHOD__));

      }
      else {

        $this->UpdateByID([ 'people_id' => $Pdto->id ], $dto->id);

        if ( isset( $Pdto->isNew) && $Pdto->isNew) {
          \sys::logger( sprintf('<%s> <#%s> <NEW> %s', $Pdto->name, $Pdto->id, __METHOD__));

        }
        else {
          \sys::logger( sprintf('<%s> <#%s> %s', $Pdto->name, $Pdto->id, __METHOD__));

        }

      }

    }

  }

}
