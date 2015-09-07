<?php
require_once "vendor/autoload.php";
require 'vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
use Doctrine\Common\ClassLoader;

$classLoader = new ClassLoader('Doctrine', 'vendor/doctrine');
$classLoader->register();

// TODO: This should REALLY be done better...
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
define( 'BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0] );

// OOUI
OOUI\Theme::setSingleton( new OOUI\ApexTheme() );
OOUI\Element::setDefaultDir( 'ltr' );
$styles = array(
	'includes/styles/ooui/oojs-ui-apex.css',
	'includes/styles/Converse.css'
);

$paramCollectionId = $_GET['cid'];

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

echo "<h1>Converse</h1>";

/* Example DB operation */
// $dbhelper = new Converse\DB\DBHelper( \Converse\Config::getDatabaseDetails() );
// $boardId = $dbhelper->addNewCollection( null, 'This is the board description', 'This is the board title' );
// $topicId = $dbhelper->addNewCollection( $boardId, 'This is the topic content', 'This is the topic title', 'This is an optional description or summary' );
// $replyIdLevel1a = $dbhelper->addNewCollection( $topicId, 'This is reply #1 content' );
// $replyIdLevel1b = $dbhelper->addNewCollection( $topicId, 'This is reply #2 content' );
// $replyIdLevel2a = $dbhelper->addNewCollection( $replyIdLevel1a, 'This is reply #1a1 content' );
// $replyIdLevel2b = $dbhelper->addNewCollection( $replyIdLevel1a, 'This is reply #1a2 content' );
// $topic2Id = $dbhelper->addNewCollection( $boardId, 'This is another topic content', 'This is another topic title', 'This is another optional description or summary' );
// $replyIdTopic2 = $dbhelper->addNewCollection( $topic2Id, 'This is the content of the reply to another topic' );

$boardId = isset( $paramCollectionId ) ? $paramCollectionId : 1;

// Build a collection model
$builder = new Converse\Model\ModelBuilder();
$collection = $builder->populateCollection( $boardId );

$collectionWidget = new Converse\UI\CollectionWidget( $collection );
echo $collectionWidget;
