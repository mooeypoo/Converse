<?php

namespace Converse\UI;

class PostWidget extends \OOUI\Widget {
	protected $revContent = null;

	public function __construct( $model, $config = array() ) {
		// Parent constructor
		parent::__construct( $config );

		$revision = $model->getLatestRevision();
		$this->revContent = $revision->getContent();
		$this->appendContent( $this->revContent );
		$this->addClasses( array( 'converse-ui-postWidget' ) );
	}
}
