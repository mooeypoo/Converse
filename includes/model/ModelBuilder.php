<?php

namespace Converse\Model;

/**
 * Create and build the model from the database.
 */
class ModelBuilder {
	protected $dbhelper = null;
	protected $nestingChildren = array();
	protected $ignoreMaxNesting = false;
	protected $overrideMaxNesting = 0;

	/**
	 * Construct the model builder; create a database helper and
	 * store configuration.
	 *
	 * @constructor
	 * @param array $config Configuration object
	 */
	public function __construct( $config = array() ) {
		$connectionParams = \Converse\Config::getDatabaseDetails();

		$this->dbhelper = new \Converse\DB\DBHelper( $connectionParams );
		$this->maxNestingLevel = \Converse\Config::getMaxNesting();
	}

	public function setIgnoreMaxNesting( $isIgnore ) {
		$this->ignoreMaxNesting = $isIgnore;
	}

	public function setOverrideMaxNesting( $newMax ) {
		$this->maxNestingLevel = $newMax;
	}

	/**
	 * Build a collection hierarchy from a parent collection, and populate
	 * all its children according to the maximum nesting levels.
	 *
	 * @param int $collectionId Collection Id
	 * @param Collection [$context] Collection's context (parent)
	 * @return Collection Collection model
	 */
	public function buildCollectionHierarchy( $collectionId ) {
		// Reset
		$this->nestingChildren = array();

		// Populate
		$collection = $this->populateCollection( $collectionId );

		// Hook
		\Converse\Hooks::run( 'afterBuildCollection' );

		return $collection;
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
	 * @param Collection [$context] Collection's context (parent)
	 * @return Collection Collection model
	 */
	protected function populateCollection( $collectionId, $context = null, $nestingLevel = 0 ) {
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

		// Analyze children to see if we should add them as children or
		// children of the ancestor
		foreach ( $children as $i => $data ) {
			// TODO: Deal with stickies here
			$childId = $data['child_collection_id'];

			if ( !$this->ignoreMaxNesting && $nestingLevel >= $this->maxNestingLevel - 1 ) {
				// We are too deep in the nesting levels. These children should be
				// added upwards, in the ancestor that is the top nesting level
				$this->nestingChildren[] = $this->populateCollection( $childId, $collection, $nestingLevel + 1 );
			} else {
				// Add to direct children's array
				$childrenArray[] = $this->populateCollection( $childId, $collection, $nestingLevel + 1 );
			}
		}

		// Now that we're done collecting children, add them
		// but only add if we are not above the nesting level
		if ( !$this->ignoreMaxNesting && $nestingLevel === $this->maxNestingLevel - 1 ) {
			// We are at the top nesting level. All descendant's children
			// should be added here, ordered by date

			// Order children by date
			usort( $this->nestingChildren, array( __CLASS__, 'compareCollectionDates' ) );

			// Add all grand-children and children
			$collection->addItems( $this->nestingChildren );

			// We've added these children, so reset the array
			$this->nestingChildren = array();
		} else if ( $this->ignoreMaxNesting || $nestingLevel < $this->maxNestingLevel ) {
			// Add children here normally
			$collection->addItems( $childrenArray );
		}

		return $collection;
	}

	/**
	 * Populate a post model with its revision based
	 * on the post id.
	 *
	 * @param [type] $postId [description]
	 * @return Post Post model
	 */
	protected function populatePost( $postId, $ownerCollection ) {
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

	/**
	 * Compare two timestamps of the primary posts of given collections.
	 * This is meant for sorting collections by date.
	 *
	 * @param Collection $collection1
	 * @param Collection $collection2
	 * @return int Comparison result; -1 if smaller, 1 if bigger or equal
	 */
	protected static function compareCollectionDates( $collection1, $collection2 ) {
		// TODO: Deal with stickies here. Sticky should always be
		// on top

		$date1 = $collection1->getPrimaryPost() !== null ?
			$collection1->getPrimaryPost()->getLatestRevision()->getTimestamp() : 0;
		$date2 = $collection2->getPrimaryPost() !== null ?
			$collection1->getPrimaryPost()->getLatestRevision()->getTimestamp() : 0;

		return $date1 < $date2 ? -1 : 1;
	}
}
