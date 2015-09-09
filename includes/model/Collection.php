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
	public function getAllProperties( $getAllChildren ) {
		return parent::getAllProperties() + array(
			'author' => $this->getAuthor() ? $this->getAuthor()->getId() : null,
			'title_post' => $this->getTitlePost() ? $this->getTitlePost()->getId() : null,
			'primary_post' => $this->getPrimaryPost() ? $this->getPrimaryPost()->getId() : null,
			'summary_post' => $this->getSummaryPost() ? $this->getSummaryPost()->getId() : null,
			'context_collection' => $this->getContextCollection() ? $this->getContextCollection()->getId() : null,
			'children' => $getAllChildren ? $this->getChildrenProperties() : null,
		);
	}

	public function getChildrenProperties() {
		$items = $this->getItems();
		$result = array();

		foreach ( $items as $item ) {
			$result[$item->getId()] = $item->getAllProperties( true );
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
