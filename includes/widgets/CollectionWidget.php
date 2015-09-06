<?php

namespace Converse\UI;

class CollectionWidget extends \OOUI\Widget {

	public function __construct( $model, $config = array() ) {
		// Parent constructor
		parent::__construct( $config );
		// TODO: Add timestamp + author information
		// TODO: Add moderation info + controls
		// TODO: Add reply / add children
		$groupDiv = new \OOUI\Tag();
		$groupDiv->addClasses( array( 'converse-ui-collection-group' ) );

		$headerDiv = new \OOUI\Tag();
		$headerDiv->addClasses( array( 'converse-ui-collection-header' ) );

		// Mixins
		$this->mixin( new \OOUI\GroupElement( $this, array_merge( $config, array( 'group' => $groupDiv ) ) ) );

		// Title post widget
		if ( $model->getTitlePost() !== null ) {
			$titleWidget = new PostWidget( $model->getTitlePost() );
			$titleWidget->addClasses( array( 'converse-ui-collectionWidget-title' ) );
			$headerDiv->appendContent( $titleWidget );
		}

		// Summary post widget
		if ( $model->getSummaryPost() !== null ) {
			$summaryPostWidget = new PostWidget( $model->getSummaryPost() );
			$summaryPostWidget->addClasses( array( 'converse-ui-collectionWidget-summary' ) );
			$headerDiv->appendContent( $summaryPostWidget );
		}
		if ( $model->getTitlePost() !== null || $model->getSummaryPost() !== null ) {
			$this->appendContent( $headerDiv );
		}

		// Primary post widget
		if ( $model->getPrimaryPost() !== null ) {
			$primaryPostWidget = new PostWidget( $model->getPrimaryPost() );
			$primaryPostWidget->addClasses( array( 'converse-ui-collectionWidget-primary' ) );
			$this->appendContent( $primaryPostWidget );
		}

		// Recurse through children...
		// TODO: When the board/topic is big, this can get costly.
		// We can consider another approach to improve this for those cases
		$children = $model->getItems();
		$childWidgets = array();
		for ( $i = 0; $i < count( $children ); $i++ ) {
			$childWidgets[] = new CollectionWidget( $children[$i] );
		}
		$this->addItems( $childWidgets );

		$this->addClasses( array( 'converse-ui-collectionWidget' ) );
		$this->appendContent( $groupDiv );
	}
}
