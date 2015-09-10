<?php

namespace Converse\Model;

class Revision extends ModeratedItem {
	protected $author = null; // User
	protected $previousRevision = null; // Revision
	protected $parentPost = null; // Post
	protected $content = ''; // String
	protected $contentFormat = ''; // String
	protected $editComment = ''; // String

	public function __construct( $id, $data = array() ) {
		parent::__construct( $id, $data );
var_dump( $data );
		// Set optional data items
		if ( isset( $data[ 'author' ] ) ) {
			$this->setAuthor( $data[ 'author' ] );
		}
		if ( isset( $data[ 'previous_revision' ] ) ) {
			$this->setPreviousRevision( $data[ 'previous_revision' ] );
		}
		if ( isset( $data[ 'content' ] ) ) {
			$this->setContent( $data[ 'content' ] );
		}
		if ( isset( $data[ 'content_format' ] ) ) {
			$this->setContentFormat( $data[ 'content_format' ] );
		}
		if ( isset( $data[ 'parent_post' ] ) ) {
			$this->setParentPostId( $data[ 'parent_post' ] );
		}
	}

	/**
	 * Get a full array of field/values fit for the database
	 * @return Array
	 */
	public function getApiProperties() {
		$result = parent::getApiProperties() + array(
			'author' => $this->getAuthor() ? $this->getAuthor()->getId() : null,
			'previous_revision_id' => $this->getPreviousRevision() ? $this->getPreviousRevision()->getId() : null,
			'parent_post_id' => $this->getParentPostId(),
			'content' => $this->getContent(),
			'content_format' => $this->getContentFormat(),
			'edit_comment' => $this->getEditComment(),
		);

		return parent::cleanEmptyProperties( $result );
	}

	/** Setters and getters */

	public function setParentPost( $id ) {
		$this->parentPost = $id;
		// Change id
		$this->setParentPostId( $this->parentPost->getId() );
	}

	public function getParentPost() {
		return $this->parentPost;
	}

	public function getParentPostId() {
		return $this->parentPostId;
	}

	public function setParentPostId( $id ) {
		$this->parentPostId = $id;
	}

	public function setAuthor( $author ) {
		$this->author = $author;
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setPreviousRevision( $revision ) {
		$this->previousRevision = $revision;
	}

	public function getPreviousRevision() {
		return $this->previousRevision;
	}

	public function setContent( $content ) {
		$this->content = $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function setContentFormat( $format ) {
		$this->contentFormat = $format;
	}

	public function getContentFormat() {
		return $this->contentFormat;
	}

	public function setEditComment( $comment ) {
		$this->editComment = $comment;
	}

	public function getEditComment() {
		return $this->editComment;
	}
}
