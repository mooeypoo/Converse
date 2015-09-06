<?php

namespace Converse\UI;

class PostWidget extends \OOUI\Widget {
	public function __construct( $model, $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		$revision = $model->getLatestRevision();

		// Revision details
		if ( $revision->getContent() !== null ) {
			$content = new \OOUI\Tag( 'div' );
			$content->addClasses( array( 'converse-ui-postWidget-content' ) );
			$content->appendContent( $revision->getContent() );
			$this->appendContent( $content );
		}

		$this->addClasses( 'converse-ui-postWidget' );
	}
}
