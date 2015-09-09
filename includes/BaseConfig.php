<?php
namespace Converse;

/**
 * Base configuration.
 * To override configuration properties extend this class.
 */
class BaseConfig {
	static function getMaxNesting() {
		return 4;
	}

	static function getDateFormat() {
		return 'r';
	}

	static function getDatabaseDetails() {
		return array(
			'host' => 'localhost',
			'name' => '',
			'user' => '',
			'password' => ''
		);
	}
}
