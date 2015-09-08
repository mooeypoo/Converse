<?php
namespace Converse;

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
