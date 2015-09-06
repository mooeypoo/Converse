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
	public function populateCollection( $collectionId ) {
		$childrenArray = array();

		// Get the collection data
		$collectionData = $this->dbhelper->getCollection( $collectionId );
		// Initialize model
		$collection = new Collection( $collectionId, $collectionData );
		// Get children
		$children = $this->dbhelper->getCollectionChildren( $collectionId );

		// Populate each child
		foreach ( $children as $i => $data ) {
			// TODO: Deal with stickies here, maybe?
			$childId = $data['child_collection_id'];
			$childrenArray[] = $this->populateCollection( $id );
		}

		$collection->addItems( $childrenArray );
		return $collection;
	}
}
