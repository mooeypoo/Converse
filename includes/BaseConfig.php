<?php
namespace Converse;

class BaseConfig {
	static function getDatabaseDetails() {
		return array(
			'host' => 'localhost',
			'name' => '',
			'user' => '',
			'password' => ''
		);
	}
}
