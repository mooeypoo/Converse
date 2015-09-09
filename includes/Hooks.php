<?php

namespace Converse;

/**
 * A class that implements hooks functionality.
 */
class Hooks {
	const PRIORITY_NORMAL = 0;
	const PRIORITY_HIGH = 1;

	protected $handlers = array();

	/**
	 * Register a handler
	 *
	 * @param string $hook Hook name
	 * @param func $function Function to run
	 * @param int [$priority] Priority. If set to high, the function will be
	 *  added to the top of the handler array.
	 */
	public function register( $hook, $function, $priority = self::PRIORITY_NORMAL ) {
		if ( !isset( $this->handlers[ $hook ] ) ) {
			// Initialize if needed
			$this->handlers[$hook] = array();
		}

		// Add the function to the hook array
		if ( $priority === self::PRIORITY_HIGH ) {
			// Push to top of array
			array_unshift( $this->handlers[$hook], $function );
		} else {
			// Add to the end
			$this->handlers[$hook][] = $function;
		}
	}

	/**
	 * Call the hook and run all its handler functions
	 *
	 * @param string $hook Hook name
	 */
	public function run( $hook ) {
		if ( isset( $this->handlers[$hook] ) ) {
			// Call each function
			foreach ( $this->handlers[$hook] as $function ) {
				call_user_func( $function );
			}
		}
	}
}
