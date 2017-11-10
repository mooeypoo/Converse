<?php

namespace Converse\Model;

/**
 * Base class for items that have ids.
 */
class ModeratedItem {
	protected $id;
	protected $timestamp;

	protected $moderationStatus = '';
	protected $moderationReason = '';
	protected $moderationTimestamp = null;
	protected $moderationAuthor = null;

	/**
	 * Construct the item with its id if it is already given.
	 *
	 * @param array $data Configuration options
	 */
	public function __construct( $id, $data = array() ) {
		$this->setId( $id );
		if ( isset( $data['timestamp'] ) ) {
			$this->setTimestamp( $data['timestamp'] );
		}

		if ( isset( $data['moderation_status'] ) ) {
			$this->setModerationStatus( $data['moderation_status'] );
		}
		if ( isset( $data['moderation_reason'] ) ) {
			$this->setModerationReason( $data['moderation_reason'] );
		}
		if ( isset( $data['moderation_timestamp'] ) ) {
			$this->setModerationTimestamp( $data['moderation_timestamp'] );
		}
		if ( isset( $data['moderation_author'] ) ) {
			$this->setModerationAuthor( $data['moderation_author'] );
		}
	}

	/**
	 * Get a full array of field/values fit for the database
	 * @return Array
	 */
	public function getApiProperties( $getAllChildren = false ) {
		$result = array(
			'id' => $this->getId(),
			'timestamp' => $this->getTimestamp(),
			// Moderation stuff
			'moderation_status' => $this->getModerationStatus(),
			'moderation_author' => $this->getModerationAuthor(),
			'moderation_timestamp' => $this->getModerationTimestamp(),
			'moderation_reason' => $this->getModerationReason()
		);

		return $this->cleanEmptyProperties( $result );
	}

	public static function cleanEmptyProperties( $array ) {
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( $value !== null && $value !== '' ) {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/**
	 * Set item id
	 *
	 * @param int $id Item id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * Get item id
	 *
	 * @return int $id Item id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set item timestamp
	 *
	 * @param int $timestamp Timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Get item timestamp
	 *
	 * @return int $timestamp Timestamp
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}
	/**
	 * Set moderation status
	 *
	 * @param string $status Moderation status
	 */
	public function setModerationStatus( $status ) {
		$this->moderationStatus = $status;
	}

	/**
	 * Get moderation status
	 *
	 * @return string Moderation status
	 */
	public function getModerationStatus() {
		return $this->moderationStatus;
	}

	public function setModerationReason( $reason ) {
		$this->moderationReason = $reason;
	}

	public function getModerationReason() {
		return $this->moderationReason;
	}

	public function setModerationTimestamp( $timestamp ) {
		$this->moderationTimestamp = $timestamp;
	}

	public function getModerationTimestamp() {
		return $this->moderationTimestamp;
	}

	public function setModerationAuthor( $user ) {
		$this->moderationAuthor = $user;
	}

	public function getModerationAuthor() {
		return $this->moderationAuthor;
	}

	public function __toString() {
		return json_encode( $this->getApiProperties() );
	}
}
