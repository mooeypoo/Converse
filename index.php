<?php
require_once "vendor/autoload.php";
require 'vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

use Doctrine\Common\ClassLoader;

$classLoader = new ClassLoader('Doctrine', 'vendor/doctrine');
$classLoader->register();

OOUI\Theme::setSingleton( new OOUI\ApexTheme() );
OOUI\Element::setDefaultDir( 'ltr' );
$styles = array(
	'styles/ooui/oojs-ui-apex.css',
	// 'styles/converse.css',
	// 'styles/converse.PostWidget.css'
);


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

// Build a collection model
$builder = new Converse\Model\ModelBuilder();
$collection = $builder->populateCollection( 16 );

$collectionWidget = new Converse\UI\CollectionWidget( $collection );
echo $collectionWidget;
// var_dump( $collection );
