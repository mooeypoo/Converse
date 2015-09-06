<?php

namespace Converse\Model;

class Collection extends ModeratedItem {
	use \Converse\Model\Mixins\Group;

	protected $author = null; // User
	protected $titlePost = null; // Post
	protected $primaryPost = null; // Post
	protected $summaryPost = null; // Post
	protected $contextCollection = null; // Collection

	protected $children = array();

	public function __construct( $id, $data = array() ) {
		parent::__construct( $id, $data );

		// Set optional data items
		if ( isset( $config[ 'author' ] ) ) {
			$this->setAuthor( $config[ 'author' ] );
		}
		if ( isset( $config[ 'title_post' ] ) ) {
			$this->setTitlePost( $config[ 'title_post' ] );
		}
		if ( isset( $config[ 'primary_post' ] ) ) {
			$this->setPrimaryPost( $config[ 'primary_post' ] );
		}
		if ( isset( $config[ 'summary_post' ] ) ) {
			$this->setSummaryPost( $config[ 'summary_post' ] );
		}
		if ( isset( $config[ 'context_collection' ] ) ) {
			$this->setContextCollection( $config[ 'context_collection' ] );
		}
	}

	/**
	 * Get a full array of field/values fit for the database
	 *
	 * @return Array
	 */
	public function getAllProperties() {
		return parent::getAllProperties() + array(
			'author' => $this->getAuthor() ? $this->getAuthor()->getId() : null,
			'title_post' => $this->getTitlePost() ? $this->getTitlePost()->getId() : null,
			'primary_post' => $this->getPrimaryPost() ? $this->getPrimaryPost()->getId() : null,
			'summary_post' => $this->getSummaryPost() ? $this->getSummaryPost()->getId() : null,
			'context_collection' => $this->getContextCollection() ? $this->getContextCollection()->getId() : null,
		);
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
