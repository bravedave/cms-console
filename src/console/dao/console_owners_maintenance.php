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
use dvc\dao\_dao;

class console_owners_maintenance extends _dao {
	protected $_db_name = 'console_owners_maintenance';

	public function getSchedule(int $OwnerID = 0) {
		$sql = 'SELECT
			cm.`id`,
			cm.`ConsoleID`,
			cm.`OwnerID`,
			cm.`ContactID`,
			co.`FileAs` OwnerName,
			co.`ContactID` OwnerContactID,
			cm.`Type`,
			cm.`Name`,
			cm.`Company`,
			cm.`Position`,
			cm.`Business`,
			cm.`Home`,
			cm.`Mobile`,
			cm.`Email`,
			cm.`Fax`,
			cm.`Limit`,
			cm.`Notes`,
			cc.`people_id`
		FROM
			`console_owners_maintenance` cm
				LEFT JOIN
			`console_owners` co ON co.`ConsoleID` = cm.`OwnerID`
				LEFT JOIN
			`console_contacts` cc ON cc.`ConsoleID` = co.`ContactID`
		WHERE
			co.`ConsoleID` IS NOT NULL
		ORDER BY
			cm.`OwnerID`';

		if ($OwnerID) {
			$sql = sprintf(
				'SELECT * FROM (%s) x WHERE `OwnerID` = %d',
				$sql,
				$OwnerID

			);

		}

		return $this->Result($sql);
	}

	public function getByConsoleID($id) {
		if ($id = (int)$id) {

			$sql = sprintf(
				'SELECT
					*
				FROM `%s`
				WHERE
					ConsoleID = %d',
				$this->db_name(),
				$id
			);

			if ($res = $this->Result($sql)) {
				return ($res->dto());
			}
		}

		return (false);
	}

	public function import() {
		$debug = false;
		//~ $debug = true;
		//~ \sys::logger( sprintf( '<ready ...> : %s', __METHOD__));
		//~ return;

		if ($res = db::owners_maintenance()) {
			$stats = (object)[
				'new' => 0,
				'updated' => 0,
				'nochange' => 0,
				'total' => 0
			];
			$seen = [];
			while ($_dto = $res->dto()) {
				$stats->total++;
				if ($dto = $this->getByConsoleID($_dto->ID)) {
					$seen[] = $dto->id;
					$a = [];

					if ((int)$_dto->OwnerID != (int)$dto->OwnerID)
						$a['OwnerID'] = (int)$_dto->OwnerID;

					if ((int)$_dto->ContactID != (int)$dto->ContactID)
						$a['ContactID'] = (int)$_dto->ContactID;

					if ((string)$_dto->Type != (string)$dto->Type)
						$a['Type'] = (string)$_dto->Type;

					if ((string)$_dto->Name != (string)$dto->Name)
						$a['Name'] = (string)$_dto->Name;

					if ((string)$_dto->Company != (string)$dto->Company)
						$a['Company'] = (string)$_dto->Company;

					if ((string)$_dto->Position != (string)$dto->Position)
						$a['Position'] = (string)$_dto->Position;

					if ((string)$_dto->Business != (string)$dto->Business)
						$a['Business'] = (string)$_dto->Business;

					if ((string)$_dto->Home != (string)$dto->Home)
						$a['Home'] = (string)$_dto->Home;

					if ((string)$_dto->Mobile != (string)$dto->Mobile)
						$a['Mobile'] = (string)$_dto->Mobile;

					if ((string)$_dto->Fax != (string)$dto->Fax)
						$a['Fax'] = (string)$_dto->Fax;

					if ((string)$_dto->Email != (string)$dto->Email)
						$a['Email'] = (string)$_dto->Email;

					if (round((float)$_dto->Limit, 3) != round((float)$dto->Limit, 3))
						$a['Limit'] = (float)$_dto->Limit;

					if ((string)$_dto->Notes != (string)$dto->Notes)
						$a['Notes'] = (string)$_dto->Notes;

					if (count($a)) {
						if ($debug) \sys::logger(sprintf('<update ...> : %s', __METHOD__));
						$this->UpdateByID($a, $dto->id);
						$stats->updated++;
					} else {
						$stats->nochange++;
						if ($debug) \sys::logger(sprintf('<%s no update ...> : %s', $_dto->ID, __METHOD__));
					}
				} else {
					if ($debug) \sys::logger(sprintf('<new ...> : %s', __METHOD__));
					$a = [
						'ConsoleID' => (int)$_dto->ID,
						'OwnerID' => (int)$_dto->OwnerID,
						'ContactID' => (int)$_dto->ContactID,
						'Type' => (string)$_dto->Type,
						'Name' => (string)$_dto->Name,
						'Company' => (string)$_dto->Company,
						'Position' => (string)$_dto->Position,
						'Business' => (string)$_dto->Business,
						'Home' => (string)$_dto->Home,
						'Mobile' => (string)$_dto->Mobile,
						'Fax' => (string)$_dto->Fax,
						'Email' => (string)$_dto->Email,
						'Limit' => round((float)$_dto->Limit, 3),
						'Notes' => (string)$_dto->Notes,

					];

					$stats->{'new'}++;
					$seen[] = $this->Insert($a);
				}
			}

			// clean up
			$this->Q(sprintf('DELETE FROM `console_owners_maintenance` WHERE `id` NOT IN(%s)', implode(',', $seen)));


			if ($stats->{'new'} || $stats->updated || $debug) {
				\sys::logger(sprintf(
					'update console_owners_maintenance : new %s updated %s no change %s (%s)',
					$stats->{'new'},
					$stats->updated,
					$stats->nochange,
					$stats->total
				));
			}
		}
	}
}
