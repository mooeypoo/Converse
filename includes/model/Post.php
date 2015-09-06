<?php

namespace Converse\Model;

class Post extends ModeratedItem {
	protected $latestRevision = null; // Revision

	public function __construct( $id, Revision $latestRevision, $data = array() ) {
		parent::__construct( $id, $data );

		$this->setLatestRevision( $latestRevision );
	}

	/**
	 * Get a full array of field/values fit for the database
	 * @return Array
	 */
	public function getAllProperties() {
		return parent::getAllProperties() + array(
			'latest_revision' => $this->getLatestRevisionId()
		);
	}

	public function getLatestRevisionId() {
		$rev = $this->getLatestRevision()
		return $rev !== null ?
			$rev->getId() : null;
	}

	public function getLatestRevision() {
		return $this->latestRevision;
	}

	public function setLatestRevision( $revId ) {
		$this->latestRevision = $revId;
	}
}
