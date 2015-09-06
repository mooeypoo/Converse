<?php

namespace Converse\Model;

class ModelBuilder {
	protected $dbhelper = null;

	public function __construct( $config = array() ) {
		$connectionParams = \Converse\Config::getDatabaseDetails();

		$this->dbhelper = new \Converse\DB\DBHelper( $connectionParams );
	}

	/**
	 * Populate a collection with its data and children.
	 *
	 * TODO: This operation will become extremely costly with large
	 * collections. In that case, we will need to optimize the database
	 * and find easier ways to retrieve a children-list rather than
	 * to retrieve it recursively.
	 *
	 * @param int $collectionId Collection Id
	 * @return Collection Collection model
	 */
	public function populateCollection( $collectionId, $context = null ) {
		$childrenArray = array();

		// Get the collection data
		$collectionData = $this->dbhelper->getCollection( $collectionId );

		// Initialize model
		$collection = new Collection( $collectionId, $collectionData );
		if ( $context !== null ) {
			$collection->setContextCollection( $context );
		}

		// Translate data into models
		if ( isset( $collectionData['title_post'] ) ) {
			$collection->setTitlePost( $this->populatePost( $collectionData['title_post'], $collection ) );
		}
		if ( isset( $collectionData['primary_post'] ) ) {
			$collection->setPrimaryPost( $this->populatePost( $collectionData['primary_post'], $collection ) );
		}
		if ( isset( $collectionData['summary_post'] ) ) {
			$collection->setSummaryPost( $this->populatePost( $collectionData['summary_post'], $collection ) );
		}

		// Get children
		$children = $this->dbhelper->getCollectionChildren( $collectionId );

		// Populate each child
		// TODO: This piece should use logic to preserve maximum nesting
		// The nesting is also relative to the view. If we are looking at
		// a topic, we will see certain posts as the same level, but they
		// might have different levels if we look at a more specific post
		// thread.
		foreach ( $children as $i => $data ) {
			// TODO: Deal with stickies here
			$childId = $data['child_collection_id'];
			$childrenArray[] = $this->populateCollection( $childId );
		}

		$collection->addItems( $childrenArray );
		return $collection;
	}

	/**
	 * Populate a post model with its revision based
	 * on the post id.
	 *
	 * @param [type] $postId [description]
	 * @return Post Post model
	 */
	public function populatePost( $postId, $ownerCollection ) {
		// Get post data
		$postData = $this->dbhelper->getPost( $postId );

		// Get latest revision
		$latestRevId = $postData['latest_revision'];
		$revisionData = $this->dbhelper->getRevision( $latestRevId );

		// Create revision model
		$revision = new Revision( $latestRevId, $revisionData );

		// Create post model
		$post = new Post( $postId, $ownerCollection, $revision, $postData );

		// Attach revision to post
		$revision->setParentPost( $post );

		return $post;
	}
}
