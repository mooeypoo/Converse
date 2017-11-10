<?php
require_once( 'bootstrap.php' );

// OOUI
OOUI\Theme::setSingleton( new OOUI\WikimediaUITheme() );
OOUI\Element::setDefaultDir( 'ltr' );
$styles = array(
	'includes/styles/ooui/oojs-ui-wikimediaui.css',
	'includes/styles/ooui/oojs-ui-wikimediaui-icons-content.min.css',
	'includes/styles/Converse.css'
);

$collectionId = isset( $_GET['cid'] ) ? $_GET['cid'] : 1;

// Test
?>
<html>
	<head>
<?php
	for ( $i = 0; $i < count( $styles ); $i++ ) {
		echo '<link rel="stylesheet" href="' . $styles[$i] . '">'."\n";
	}
?>
	</head>
<body>
<?php

echo "<h1>Converse Demo</h1>";

/* Example DB operation */
// $dbhelper = new Converse\DB\DBHelper( \Converse\Config::getDatabaseDetails() );
// $boardId = $dbhelper->addNewCollection( null, 'This is the board description', 'This is the board title' );
// $topicId = $dbhelper->addNewCollection( $boardId, 'This is the topic content', 'This is the topic title', 'This is an optional description or summary' );
// $replyIdLevel1a = $dbhelper->addNewCollection( $topicId, 'This is reply #1 content' );
// $replyIdLevel1b = $dbhelper->addNewCollection( $topicId, 'This is reply #2 content' );
// $replyIdLevel2a = $dbhelper->addNewCollection( $replyIdLevel1a, 'This is reply #1a1 content' );
// $replyIdLevel2b = $dbhelper->addNewCollection( $replyIdLevel1a, 'This is reply #1a2 content' );
// $replyIdLevel2a1 = $dbhelper->addNewCollection( $replyIdLevel2a, 'This is reply #2a11 content' );
// $replyIdLevel2a2 = $dbhelper->addNewCollection( $replyIdLevel2a, 'This is reply #2a22 content' );
// $replyIdLevel2a11 = $dbhelper->addNewCollection( $replyIdLevel2a1, 'This is reply #2a11 content' );
// $replyIdLevel2a12 = $dbhelper->addNewCollection( $replyIdLevel2a11, 'This is reply #2a22 content' );
// $replyIdLevel2a121 = $dbhelper->addNewCollection( $replyIdLevel2a12, 'This is reply #2a22 content' );
// $replyIdLevel2a1211 = $dbhelper->addNewCollection( $replyIdLevel2a121, 'This is reply #2a22 content' );
// $replyIdLevel2a12111 = $dbhelper->addNewCollection( $replyIdLevel2a1211, 'This is reply #2a22 content' );
// $replyIdLevel2a121111 = $dbhelper->addNewCollection( $replyIdLevel2a12111, 'This is reply #2a22 content' );
// $topic2Id = $dbhelper->addNewCollection( $boardId, 'This is another topic content', 'This is another topic title', 'This is another optional description or summary' );
// $replyIdTopic2 = $dbhelper->addNewCollection( $topic2Id, 'This is the content of the reply to another topic' );




// Build a collection model
$builder = new Converse\Model\ModelBuilder();
$collection = $builder->buildCollectionHierarchy( $collectionId );

// Display collection widget
$collectionWidget = new Converse\UI\CollectionWidget( $collection );
echo $collectionWidget;
