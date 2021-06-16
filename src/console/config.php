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

class config extends \config {
	const cms_console_db_version = 0.5;

  const label_creditors = 'Console Creditors';

  static protected $_CMS_CONSOLE_VERSION = 0;

	static protected function cms_console_version( $set = null) {
		$ret = self::$_CMS_CONSOLE_VERSION;

		if ( (float)$set) {
			$config = self::cms_console_config();

			$j = file_exists( $config) ?
				json_decode( file_get_contents( $config)):
				(object)[];

			self::$_CMS_CONSOLE_VERSION = $j->cms_console_version = $set;

			file_put_contents( $config, json_encode( $j, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

		}

		return $ret;

	}

	static function cms_console_checkdatabase() {
		if ( self::cms_console_version() < self::cms_console_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::cms_console_version( self::cms_console_db_version);

		}

		// sys::logger( 'bro!');

	}

	static function cms_console_config() {
		$path = method_exists(__CLASS__, 'cmsStore') ? self::cmsStore() : self::dataPath();
		return implode( DIRECTORY_SEPARATOR, [
			rtrim( $path, '/ '),
			'cms_console.json'

		]);

	}

  static function cms_console_init() {
		if ( file_exists( $config = self::cms_console_config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->cms_console_version)) {
				self::$_CMS_CONSOLE_VERSION = (float)$j->cms_console_version;

			};

		}

	}

}

config::cms_console_init();
