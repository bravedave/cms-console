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

class console_owners extends _dao {
	protected $_db_name = 'console_owners';

	public function getByConsoleID( $id) {
		if ( $id = (int)$id) {
			if ( $res = $this->Result( sprintf( 'SELECT * FROM `%s` WHERE ConsoleID = %d', $this->_db_name, $id))) {
				return ( $res->dto());

			}

		}

		return ( false);

	}

	protected function getVersion() {
		if ( $res = $this->Result( sprintf( 'SELECT MAX(version) v FROM `%s`', $this->_db_name))) {
			if ( $dto = $res->dto()) {
				return ( (int)$dto->v);

			}

		}

		return ( 0);

	}

	public function Insert( $a ) {
		$a['version'] = $this->getVersion()+1;
		return ( parent::Insert( $a));

	}

	public function import() {
		if( $res = db::owners()) {
			$stats = (object)[
				'new' => 0,
				'updated' => 0,
				'nochange' => 0,
				'total' => 0
			];
			while ( $_dto = $res->dto()) {
				$stats->{'total'} ++;
				if ( $dto = $this->getByConsoleID( $_dto->ID)) {
					$a = [];
					//~ if ( $dto->ConsoleID != $_dto->ID)
						//~ $a['ConsoleID'] = $_dto->ID;

					if ( $dto->ContactID != $_dto->ContactID)
						$a['ContactID'] = $_dto->ContactID;

					if ( $dto->FileAs != $_dto->FileAs)
						$a['FileAs'] = $_dto->FileAs;

					if ( $dto->Mobile != $_dto->Mobile)
						$a['Mobile'] = $_dto->Mobile;

					if ( $dto->Email != $_dto->Email) {
						$a['Email'] = $_dto->Email;
						\sys::logger( sprintf('Contact Email changed :%s: = :%s: (%s)', $dto->Email, (string)$_dto->Email, $dto->id));

					}

					//~ if ( $dto->Street != $_dto->Street) {
						//~ $a['Street'] = $_dto->Street;

					//~ }

					//~ if ( $dto->City != $_dto->City)
						//~ $a['City'] = $_dto->City;

					//~ if ( $dto->State != $_dto->State)
						//~ $a['State'] = $_dto->State;

					//~ if ( $dto->Postcode != $_dto->Postcode)
						//~ $a['Postcode'] = $_dto->Postcode;

					if ( $dto->Street != '') {
						$a['Street'] = '';

					}

					if ( $dto->City != '')
						$a['City'] = '';

					if ( $dto->State != '')
						$a['State'] = '';

					if ( $dto->Postcode != '')
						$a['Postcode'] = '';

					if ( count( $a)) {
						$stats->updated ++;
						//~ foreach ( $a as $k => $v) {
							//~ \sys::logger( sprintf('update %s = %s', $k, $v));

						//~ }

						$this->UpdateByID( $a, $dto->id);
						//~ break;

					}
					else {
						$stats->nochange ++;
						//~ \sys::logger( sprintf('%s == %s', $dto->GUID, $_dto->GUID));

					}

				}
				else {
					$stats->{'new'} ++;

					$this->Insert([
						'ConsoleID' => $_dto->ID,
						'FileAs' => $_dto->FileAs,
						'Mobile' => $_dto->Mobile,
						'Email' => $_dto->Email,
						'Street' => $_dto->Street,
						'City' => $_dto->City,
						'State' => $_dto->State,
						'Postcode' => $_dto->Postcode,
						'GUID' => (string)$_dto->GUID,

					]);

				}

			}

			if ( $stats->{'new'} || $stats->updated) {
				\sys::logger( sprintf( 'update console_owners : new %s updated %s no change %s (%s)',
					$stats->{'new'},
					$stats->updated, $stats->nochange, $stats->total));

			}

		}
		else {
			\sys::logger('no console_owners');

		}

	}

	public function UpdateByID( $a, $id ) {
		$a['version'] = $this->getVersion()+1;
		return ( parent::UpdateByID( $a, $id));

	}

}
