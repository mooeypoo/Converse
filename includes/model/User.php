<?php

namespace Converse\Model;

class User {
	protected $userid;
	protected $displayname;
	protected $level;

	public function __construct( $userid, $level ) {
		$this->userid = $userid;
		$this->level;
	}
}
