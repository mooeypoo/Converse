<?php

namespace Converse\Model;

class Collection extends ModeratedItem {
	use \Converse\Model\Mixins\Group;

	protected $author = null; // User
	protected $titlePost = null; // Post
	protected $primaryPost = null; // Post
	protected $summaryPost = null; // Post
	protected $contextCollection = null; // Collection

	public function __construct( $id, $data = array(), $config = array() ) {
		parent::__construct( $id, $data );
		// Set optional data items
		if ( isset( $data[ 'author' ] ) ) {
			$this->setAuthor( $data[ 'author' ] );
		}
	}

	/**
	 * Get a full array of field/values fit for the database
	 *
	 * @return Array
	 */
	public function getApiProperties( $getAllChildren = false ) {
		$result = parent::getApiProperties() + array(
			'author' => $this->getAuthor(),
			'title_post' => $this->getTitlePost() ? $this->getTitlePost()->getApiProperties() : null,
			'primary_post' => $this->getPrimaryPost() ? $this->getPrimaryPost()->getApiProperties() : null,
			'summary_post' => $this->getSummaryPost() ? $this->getSummaryPost()->getApiProperties() : null,
			'context_collection_id' => $this->getContextCollection() ? $this->getContextCollection()->getId() : null,
			'children' => $getAllChildren ? $this->getChildrenProperties() : null,
		);
		return parent::cleanEmptyProperties( $result );
	}

	public function getChildrenProperties() {
		$items = $this->getItems();
		$result = array();

		foreach ( $items as $item ) {
			$result[$item->getId()] = $item->getApiProperties( true );
		}
		return $result;
	}

	public function getChildrenIds() {
		$items = $this->getItems();
		$childrenIds = array();

		foreach ( $items as $child ) {
			$hildrenIds[$child->getId()] = array();
			// Do the same for the child
			$childrenIds[$child->getId()] = $child->getChildrenIds();
		}

		return $childrenIds;
	}

	protected function populateChildrenIds() {

	}

	public function setAuthor( $author ) {
		$this->author = $author;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setTitlePost( $post ) {
		$this->titlePost = $post;
	}

	public function getTitlePost() {
		return $this->titlePost;
	}

	public function setPrimaryPost( $post ) {
		$this->primaryPost = $post;
	}

	public function getPrimaryPost() {
		return $this->primaryPost;
	}

	public function setSummaryPost( $post ) {
		$this->summaryPost = $post;
	}

	public function getSummaryPost() {
		return $this->summaryPost;
	}

	public function setContextCollection( $context ) {
		$this->contextCollection = $context;
	}

	public function getContextCollection() {
		return $this->contextCollection;
	}
}
