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

use sys;

class db {
	const contactslink_filetype_tenants = 2;	// See SELECT * FROM FileTypes

  const contactslink_filetype_creditors = 5;

  static public function connection() : ?\dvc\mssql\db {
    $config = implode( DIRECTORY_SEPARATOR, [
      config::dataPath(),
      'console-connection.json'

    ]);

    if ( file_exists( $config)) {
      $o = json_decode( \file_get_contents( $config));
      // sys::dump( (array)$o);

      return \dvc\mssql\db::instance($o);

    }
    else {
      $o = (object)[
        'serverName' => '',
        'connectionInfo' => [
          'Database'=>'',
          'Uid' => '',
          'PWD' => ''

        ]

      ];

      $config = implode( DIRECTORY_SEPARATOR, [
        config::dataPath(),
        'console-connection-sample.json'

      ]);

      \file_put_contents( $config, json_encode( $o, JSON_PRETTY_PRINT));

    }

    return null;

  }

	static public function contactLinks($id) {
		if ((int)$id) {
			$sql = sprintf(
				'SELECT
					ID,
					FileID,
					ContactID
				FROM
					ContactsLink
				WHERE
					FileType = %d
					AND FileID = %d',
				self::contactslink_filetype_tenants,
				$id
			);

			$conn = self::connection();
			$ret = $conn->Result($sql);
			//~ \sys::logger( 'have contacts');

			return ($ret);
		}

		return false;
	}

	static public function creditors() {
    // [WithholdAmount]             MONEY            NULL,
    // [WithholdReason]             NVARCHAR (50)    NULL,
    // [WithholdUntilDisbursed]     BIT              DEFAULT ((0)) NOT NULL,
    // [Commission%]                FLOAT (53)       NULL,
    // [Commission$]                MONEY            NULL,
    // [OpeningBalance]             MONEY            NULL,
    // [Credits]                    MONEY            NULL,
    // [Debits]                     MONEY            NULL,
    // [DisbursementTransactionID]  INT              NULL,
    // [DisbursementOpeningBalance] MONEY            NULL,
    // [BarcodeDefinition]          NVARCHAR (50)    NULL,
    // [RowVersion]                 ROWVERSION       NOT NULL,
    // [ModifiedDateUTC]            DATETIME         DEFAULT (getutcdate()) NOT NULL,
    // [BPAYFlag]                   BIT              DEFAULT ((0)) NOT NULL,

    // c.AccountID,
    // c.GUID,
    // c.Uploaded,
		$sql = sprintf(
      'IF OBJECT_ID(\'tempdb..#TEMP\') IS NOT NULL DROP TABLE #TEMP;

			SELECT
      c.ID,
      c.Reference,
      c.FileAs,
      c.Created,
      c.Modified,
      c.Notes,
      c.ABN,
      c.BPAYBillerCode,
      c.DissectionID
			INTO #TEMP
			FROM
				Creditors c
				WHERE NOT FileAs = \'\' AND NOT c.Inactive = 1 AND NOT c.Deleted = 1;

			ALTER TABLE #TEMP ADD ContactID INT NOT NULL DEFAULT 0;
			ALTER TABLE #TEMP ADD Dissection_Refer NVARCHAR(8) NOT NULL DEFAULT \'\';
			ALTER TABLE #TEMP ADD Dissection_FileAs NVARCHAR(200) NOT NULL DEFAULT \'\';

			-- SELECT * FROM #TEMP;

			UPDATE
        t
			SET
        t.ContactID = c.ContactID
			FROM
        #TEMP t
        INNER JOIN ContactsLink c
          ON c.FileID = t.ID AND c.FileType = %d;

			UPDATE
        t
        SET
          t.Dissection_Refer = d.Reference,
          t.Dissection_FileAs = d.FileAs
          FROM
            #TEMP t
            INNER JOIN Dissections d
            ON d.ID = t.DissectionID;',

      self::contactslink_filetype_creditors

    );

		$conn = self::connection();
		$conn->Q( $sql);

		$sql = 'SELECT
				t.*,
				Contacts.Salutation,
				Contacts.First,
				Contacts.Middle,
				Contacts.Last,
				Contacts.Mobile,
				Contacts.Email
			FROM #TEMP t
				INNER JOIN Contacts
					ON Contacts.ID = t.ContactID
			ORDER BY t.ID ASC;';

		return $conn->Result($sql);

	}

	static public function contacts() {
		$sql = 'SELECT
			ID,
			FileAs,
			Title,
			First,
			Middle,
			Last,
			HomeStreet,
			HomeCity,
			HomeState,
			HomePostcode,
			HomeCountry,
			MailingStreet,
			MailingCity,
			MailingState,
			MailingPostcode,
			MailingCountry,
			Salutation,
			Company,
			Business,
			Home,
			Mobile,
			Email,
			GUID
		FROM
			Contacts
		WHERE
			NOT Contacts.Inactive = 1';

		$conn = self::connection();
		$ret = $conn->Result( $sql);
		//~ \sys::logger( 'have contacts');

		return ( $ret);

	}

	static public function properties() {
		$sql = 'SELECT
			Properties.ID,
			Properties.Street,
			Properties.City,
			Properties.State,
			Properties.Postcode,
			Properties.Rent,
			Properties.Period,
			Properties.LetFee,
			Properties.Bedrooms,
			Properties.Bathrooms,
			Properties.Furnished,
			Properties.Fenced,
			Properties.Pets,
			Properties.CarAccomm,
			Properties.Zone,
			Properties.Type,
			Properties.[Key],
			Properties.PropertyManagerID,
			Properties.OwnerID,
			Users.Code PropertyManager,
			Properties.GUID,
			Properties.Inactive
		FROM Properties
			LEFT JOIN users ON users.ID = Properties.PropertyManagerID
		WHERE
			NOT Properties.Inactive = 1 AND NOT Properties.Deleted = 1;';
		//~ WHERE NOT Properties.inactive = 1;

		$conn = self::connection();
		return $conn->Result($sql);

	}

	static public function tenants() {
		$sql =
			'IF OBJECT_ID(\'tempdb..#TEMP\') IS NOT NULL DROP TABLE #TEMP;

			SELECT
				t.ID,
				t.FileAs,
				p.Street,
				p.City,
				p.State,
				p.Postcode,
				p.id PropertyID,
				t.Rent,
				t.Bond,
				t.Period,
				t.PaidTo,
				t.Credit,
				t.LeaseFirstStart,
				t.LeaseStart,
				t.LeaseStop,
				t.LeaseTerm,
				t.Vacating,
				t.Inactive,
				p.[Key],
				t.GUID
			INTO #TEMP
			FROM
				Tenants t
				INNER JOIN
					Properties p
						ON p.ID = t.PropertyID AND NOT t.Inactive = 1 AND NOT t.Deleted = 1;

			ALTER TABLE #TEMP ADD ContactID INT NOT NULL DEFAULT 0;

			-- SELECT * FROM #TEMP;

			UPDATE
				t
			SET
				t.ContactID = c.ContactID
			FROM
				#TEMP t
				INNER JOIN ContactsLink c
					ON c.FileID = t.id AND c.FileType = 2;';

		$conn = self::connection();
		$conn->Q( $sql);

		$sql = 'SELECT
				t.ID,
				t.FileAs,
				Contacts.Mobile,
				Contacts.Email,
				t.ContactID,
				t.Street,
				t.City,
				t.State,
				t.Postcode,
				t.PropertyID,
				t.Bond,
				t.Rent,
				t.Period,
				t.PaidTo,
				t.Credit,
				t.LeaseFirstStart,
				t.LeaseStart,
				t.LeaseStop,
				t.LeaseTerm,
				t.Vacating,
				t.Inactive,
				t.[Key],
				t.GUID
			FROM #TEMP t
				INNER JOIN Contacts
					ON Contacts.ID = t.ContactID
			ORDER BY t.ID ASC;';

		return $conn->Result($sql);

	}

	static public function tenantLinks( $id) {
		if ( (int)$id) {
			$sql = sprintf( 'SELECT
					ID,
					FileID,
					ContactID
				FROM
					ContactsLink
				WHERE
					ContactID = %d', $id);

			$conn = self::connection();
			$ret = $conn->Result( $sql);

			return ( $ret);

		}

		return false;

	}

}