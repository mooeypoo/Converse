<?php
namespace Converse\DB;

class DBHelper {
	protected $connection;

	/**
	 * Instantiate the helper with a database connection.
	 * @param array $config Configuration options
	 */
	public function __construct( $connectionParams = array() ) {
		$dbal_config = new \Doctrine\DBAL\Configuration();
		$this->connection = \Doctrine\DBAL\DriverManager::getConnection( $connectionParams, $dbal_config );
	}

	/***********/
	/** Select */
	/***********/

	/**
	 * Get a collection from the database
	 *
	 * @param int $id Collection id
	 * @return Array Collection data
	 */
	public function getCollection( $id ) {
		return $this->connection->fetchAssoc(
			'SELECT * FROM collections WHERE id=?',
			array( $id )
		);
	}

	public function getCollectionChildren( $id ) {
		return $this->connection->fetchAll(
			'SELECT child_collection_id FROM collection_children WHERE collection_id='.$id
		);
	}

	/**
	 * Get a collection from the database
	 *
	 * @param int $id Collection id
	 * @return Array Collection data
	 */
	public function getPost( $id ) {
		return $this->connection->fetchAssoc(
			'SELECT * FROM posts WHERE id=?',
			array( $id )
		);
	}

	/**
	 * Get a collection from the database
	 *
	 * @param int $id Collection id
	 * @return Array Collection data
	 */
	public function getRevision( $id ) {
		return $this->connection->fetchAssoc(
			'SELECT * FROM revisions WHERE id=?',
			array( $id )
		);
	}

	/*******************************/
	/** Simple add to the database */
	/*******************************/

	/**
	 * Add a new collection.
	 *
	 * @param int [$context] The ID of the parent (or 'context') collection. Null if none.
	 * @param string [$content] Content of the primary post.
	 * @param string [$title] Content of the title post
	 * @param string [$summary] Content of the summary post.
	 * @return int Collection id
	 */
	public function addNewCollection( $context = null, $content = null, $title = null, $summary = null ) {
		$updateCollectionData = array();

		// Create a new post collection (includes primary post)
		$newCollectionId = $this->addNewPostCollection( $content, $context );

		// Create title revision + post
		if ( $title !== null ) {
			$titleRevisionId = $this->addRawRevision( array(
				'content' => $title
			) );
			$updateCollectionData['title_post'] = $this->addRawPost( $titleRevisionId );
		}

		// Create summary revision + post
		if ( $summary !== null ) {
			$summaryRevisionId = $this->addRawRevision( array(
				'content' => $summary
			) );
			$updateCollectionData['summary_post'] = $this->addRawPost( $summaryRevisionId );
		}

		// Update the new collection to have title+summary
		if ( count( $updateCollectionData ) > 0 ) {
			$this->update( 'collections', $updateCollectionData, $newCollectionId );
		}

		return $newCollectionId;
	}

	/**
	 * Add a new post collection.
	 *
	 * @param [type] $content [summary]
	 * @param string $editComment [description]
	 */
	public function addNewPostCollection( $content, $context = null, $editComment = null ) {
		// Create revision
		$data = array( 'content' => $content );
		if ( $editComment !== null ) {
			$data['edit_comment'] = $editComment;
		}

		// Add a revision
		$revisionId = $this->addRawRevision( $data );
		// Add a new post
		$postId = $this->addRawPost( $revisionId );

		// Wrap the post with a new collection
		$collectionData = array();
		if ( $context !== null ) {
			$collectionData['context_collection'] = $context;
		}
		$collectionId = $this->addRawCollection( $postId, $collectionData );

		// If there's a context, add this collection as a child of the context
		if ( $context !== null ) {
			$this->insert(
				'collection_children',
				array(
					'collection_id' => $context,
					'child_collection_id' => $collectionId
				),
				true
			);
		}

		return $collectionId;
	}

	/**
	 * Add a revision to a post.
	 *
	 * @param int $postId Post id
	 * @param array $data Revision data
	 * @return int Revision Id
	 */
	protected function addRevisionToPost( int $postId, $data = array() ) {
		$latestRevision = $this->connection->fetchColumn( 'SELECT previous_revision FROM posts WHERE id=?', array( $postId ), 0 );
		if ( $latestRevision !== null ) {
			$data['previous_revision'] = $latestRevision;
		}
		$revisionId = $this->insert( 'revisions', $data );

		// Update the post
		$this->update( 'posts', array( 'latest_revision' => $revisionId ), $postId );

		return $revisionId;
	}

	/************/
	/** Updates */
	/************/

	public function updateCollection( int $id, array $fieldValues ) {
		return $this->update( 'collections', $fieldValues, $id );
	}

	public function updatePost( int $id, array $fieldValues ) {
		return $this->update( 'posts', $fieldValues, $id );
	}

	public function updateRevision( int $id, array $fieldValues ) {
		return $this->update( 'revisions', $fieldValues, $id );
	}

	/****************************/
	/** Raw add to the database */
	/****************************/

	/**
	 * Add a new collection
	 *
	 * @param int $primaryPostId Primary post id
	 * @param array $data Collection data
	 */
	protected function addRawCollection( $primaryPostId, $data = array() ) {
		$data[ 'primary_post' ] = $primaryPostId;
		return $this->insert( 'collections', $data, true );
	}

	/**
	 * Add a new revision
	 *
	 * @param array $data Revision data
	 * @return number Revision id
	 */
	protected function addRawRevision( $data = array() ) {
		return $this->insert( 'revisions', $data );
	}

	/**
	 * Add a new post
	 * @param int $revisionId Revision id. A post must
	 * have at least one revision.
	 */
	protected function addRawPost( $revisionId ) {
		$data[ 'latest_revision' ] = $revisionId;
		$postId = $this->insert( 'posts', $data, true );

		// Update the revision's parent id
		$this->update( 'revisions', array( 'parent_post' => $postId ), $revisionId );

		return $postId;
	}

	/**
	 * Verify that timestamps exist, and add them if needed.
	 *
	 * @param array $data [description]
	 * @return [type] [description]
	 */
	protected function _verifyAndSetTimestamps( $data = array() ) {
		if ( !isset( $data['timestamp'] ) ) {
			$data['timestamp'] = time();
		}
		if ( isset( $data['moderation_status'] ) && !isset( $data['moderation_timestamp'] ) ) {
			$data['moderation_timestamp'] = time();
		}
		return $data;
	}

	/**
	 * Perform an update to a table in the database
	 *
	 * @param string $table [description]
	 * @param array $fieldValues Fields and their values
	 * @param int $id Row id
	 * @return int Affected rows
	 */
	protected function update( $table, $fieldValues, $id ) {
		return $this->connection->update(
			$table,
			$fieldValues,
			array( 'id' => $id )
		);
	}

	/**
	 * Perform the actual insertion into the database.
	 *
	 * @param string $table Table name
	 * @param array $data Fields and their values
	 * @return int Inserted row id
	 */
	protected function insert( $table, $data, $skipTimestamp = false ) {
		if ( $skipTimestamp === false ) {
			$data = $this->_verifyAndSetTimestamps( $data );
		}
		$this->connection->insert( $table, $data );
		return (int)$this->connection->lastInsertId();
	}
}
