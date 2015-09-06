<?php

namespace Converse\Model\Mixins;

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

	public function addItems( $items = array() ) {
		for ( $i = 0; $i < count( $items ); $i++ ) {
			$this->addItem( $items[$i] );
		}
	}

	public function removeItems( $items = array() ) {
		for ( $i = 0; $i < count( $items ); $i++ ) {
			$this->removeItem( $items[$i] );
		}
	}

	public function removeItemsById( $ids = array() ) {
		for ( $i = 0; $i < count( $ids ); $i++ ) {
			$item = $this->getItemById( $ids[$i] );
			if ( $item ) {
				$this->removeItem( $item );
			}
		}
	}

	public function getItems() {
		return $this->items;
	}

	public function getItemById( $id ) {
		return array_key_exists( $id, $this->itemsById ) ?
			$this->itemsById[ $id ] :
			null;
	}

	private function getItemIndex( $item ) {
		for ( $i = 0; $i < count( $this->items); $i++ ) {
			if ( $this->items[$i] == $item ) {
				return $i;
			}
		}
		return null;
	}
	private function getItemIndexById( $id ) {
		for ( $i = 0; $i < count( $this->items); $i++ ) {
			if ( $this->items[$i]->getId() == $id ) {
				return $i;
			}
		}
		return null;
	}

	public function isEmpty() {
		return count( $this->items ) === 0;
	}

	public function clearItems() {
		$this->items = array();
		$this->itemsById = array();
	}

	public function getItemCount() {
		return count( $this->items );
	}
	public function getAllItemIds() {
		return array_keys( $this->itemsById );
	}
}
