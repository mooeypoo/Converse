<?php
require_once( 'bootstrap.php' );

/**
 * API entry point
 */

// Silex application
$app = new Silex\Application();


$app->get( '/collection/{id}', function ( Silex\Application $app, $id ) {
	// Converse model builder
	$builder = new Converse\Model\ModelBuilder();
	// Build the collection
	$collection = $builder->buildCollectionHierarchy( $id, false );

	// Get the properties
	return json_encode( $collection->getAllProperties() );
} );

$app->get( '/collection/{id}/full', function ( Silex\Application $app, $id ) {
	// Converse model builder
	$builder = new Converse\Model\ModelBuilder();
	$builder->setIgnoreMaxNesting( true );

	// Build the collection
	$collection = $builder->buildCollectionHierarchy( $id );

	// Get the properties
	return json_encode( $collection->getAllProperties( true ) );
} );


$app->get( '/collection/{id}/full/{nesting}', function ( Silex\Application $app, $id, $nesting ) {
	// Converse model builder
	$builder = new Converse\Model\ModelBuilder();
	$builder->setOverrideMaxNesting( $nesting );

	// Build the collection
	$collection = $builder->buildCollectionHierarchy( $id );

	// Get the properties
	return json_encode( $collection->getAllProperties( true ) );
} );


// Remove this in production
$app['debug'] = true;

$app->run();
