<?php

namespace Converse\Model\Mixins;

/**
 * A trait that allows for groups of items.
 * Items must have an id property and a getId() method.
 */
trait Group {
	/**
	 * List of items in the group.
	 *
	 * @var Item[]
	 */
	protected $items = array();

	/**
	 * Cached item ids reference in the group.
	 *
	 * @var string => Item
	 */
	protected $itemsById = array();

	/**
	 * Add an item to the group
	 *
	 * @param \Converse\Model\ModeratedItem $item Item to add
	 */
	public function addItem( $item ) {
		$existingItem = $this->getItemById( $item->getId() );

		if ( $existingItem ) {
			// Remove item
			$this->removeItem( $existingItem );
		}

		// Add to Id cache
		$this->itemsById[ $item->getId() ] = $item;
		// Add item
		array_push( $this->items, $item );
	}

	/**
	 * Remove an item from the group
	 *
	 * @param \Converse\Model\ModeratedItem $item Item to add
	 * @return \Converse\Model\ModeratedItem|null Removed item or null if the
	 *  item was not found.
	 */
	public function removeItem( $item ) {
		$existingItem = $this->getItemById( $item->getId() );

		if ( !$existingItem ) {
			return null;
		}

		// Remove item
		array_splice( $this->items, $this->getItemIndex( $existingItem ), 1 );
		unset( $this->itemsById[ $existingItem->getId() ] );

		return $existingItem;
	}

	/**
	 * Add multiple items to the group
	 *
	 * @param array $items Items to add
	 */
	public function addItems( $items = array() ) {
		for ( $i = 0; $i < count( $items ); $i++ ) {
			$this->addItem( $items[$i] );
		}
	}

	/**
	 * Remove multiple items from the group
	 *
	 * @param array $items Items to remove
	 */
	public function removeItems( $items = array() ) {
		for ( $i = 0; $i < count( $items ); $i++ ) {
			$this->removeItem( $items[$i] );
		}
	}

	/**
	 * Remove items by their id
	 *
	 * @param array $ids Item ids
	 */
	public function removeItemsById( $ids = array() ) {
		for ( $i = 0; $i < count( $ids ); $i++ ) {
			$item = $this->getItemById( $ids[$i] );
			if ( $item ) {
				$this->removeItem( $item );
			}
		}
	}

	/**
	 * Get all items in the group
	 *
	 * @return array All items
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * Get an item by its id
	 *
	 * @param int $id Item id
	 * @return \Converse\Model\ModeratedItem|null Requested item. Null if not found.
	 */
	public function getItemById( $id ) {
		return array_key_exists( $id, $this->itemsById ) ?
			$this->itemsById[ $id ] :
			null;
	}

	/**
	 * Get the array index value of the requested item
	 *
	 * @param \Converse\Model\ModeratedItem $item Requested item
	 * @return int Array index
	 */
	private function getItemIndex( $item ) {
		for ( $i = 0; $i < count( $this->items); $i++ ) {
			if ( $this->items[$i] == $item ) {
				return $i;
			}
		}
		return null;
	}

	/**
	 * Get the array index value of a requested item by its id
	 *
	 * @param int $id Item id
	 * @return int Array index
	 */
	private function getItemIndexById( $id ) {
		for ( $i = 0; $i < count( $this->items); $i++ ) {
			if ( $this->items[$i]->getId() == $id ) {
				return $i;
			}
		}
		return null;
	}

	/**
	 * Check whether the group is empty
	 *
	 * @return boolean Group is empty
	 */
	public function isEmpty() {
		return count( $this->items ) === 0;
	}

	/**
	 * Remove all items in the group
	 */
	public function clearItems() {
		$this->items = array();
		$this->itemsById = array();
	}

	/**
	 * Get the number of items in this group
	 *
	 * @return int Number of items in the group
	 */
	public function getItemCount() {
		return count( $this->items );
	}

	/**
	 * Get an array of all item ids
	 *
	 * @return array Item ids
	 */
	public function getAllItemIds() {
		return array_keys( $this->itemsById );
	}
}
