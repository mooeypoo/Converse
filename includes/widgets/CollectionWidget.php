<?php

namespace Converse\UI;

class CollectionWidget extends \OOUI\Widget {
	protected $linkPretext = 'Show';
	protected $showCollectionLink = true;
	protected $dateFormat = '';

	public function __construct( $model, $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		if ( isset( $config['showCollectionLink' ] ) ) {
			$this->showCollectionLink = $config['showCollectionLink' ];
		}

		$this->dateFormat = \Converse\Config::getDateFormat();

		// TODO: Add timestamp + author information
		// TODO: Add moderation info + controls
		// TODO: Add reply / add children
		$groupDiv = new \OOUI\Tag();
		$groupDiv->addClasses( array( 'converse-ui-collection-group' ) );

		// Mixins
		$this->mixin( new \OOUI\GroupElement( $this, array_merge( $config, array( 'group' => $groupDiv ) ) ) );

		// ID LINK
		if ( $this->showCollectionLink ) {
			// TODO: Do this whole base_url thing better. This is bad and messy.
			$linkButton = new \OOUI\ButtonWidget( array(
				'href' => BASE_URL . '?cid=' . $model->getId(),
				'label' => $this->linkPretext,
				'classes' => array( 'converse-ui-collectionWidget-linkButton' ),
				'framed' => false,
				'icon' => 'window',
				'flags' => array( 'progressive' )
			) );
			$this->appendContent( $linkButton );
		}

		// Title post widget
		if ( $model->getTitlePost() !== null ) {
			$headerDiv = new \OOUI\Tag();
			$headerDiv->addClasses( array( 'converse-ui-collection-header' ) );

			$titleWidget = new PostWidget( $model->getTitlePost() );
			$titleWidget->addClasses( array( 'converse-ui-collectionWidget-title' ) );

			$headerDiv->appendContent( $titleWidget );
			$this->appendContent( $headerDiv );
		}

		// Summary post widget
		if ( $model->getSummaryPost() !== null ) {
			$summaryPostWidget = new PostWidget( $model->getSummaryPost() );
			$summaryPostWidget->addClasses( array( 'converse-ui-collectionWidget-summary' ) );
			$this->appendContent( $summaryPostWidget );
		}

		// Primary post widget
		if ( $model->getPrimaryPost() !== null ) {
			$primaryPostWidget = new PostWidget( $model->getPrimaryPost() );
			$primaryPostWidget->addClasses( array( 'converse-ui-collectionWidget-primary' ) );

			// Ugly ugly ugly. This is just for test purposes, it really should be rewritten!
			$timestampLabelWidget = new \OOUI\LabelWidget( array(
				'label' => date( $this->dateFormat, $model->getPrimaryPost()->getLatestRevision()->getTimestamp() ),
				'classes' => array( 'converse-ui-collectionWidget-timestamp' )
			) );
			$this->appendContent( $primaryPostWidget, $timestampLabelWidget );
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
