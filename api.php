<?php
require_once( 'bootstrap.php' );

/**
 * API entry point
 */

// Silex application
$app = new Silex\Application();
$api = new Converse\API();

$app->get( '/collection/{id}', function ( Silex\Application $app, $id ) use ($api) {
	return $api->getCollectionHierarchy( $id, null );
} );

$app->get( '/collection/{id}/deep', function ( Silex\Application $app, $id ) use ($api) {
	return $api->getCollectionHierarchy( $id, true );
} );


$app->get( '/collection/{id}/deep/{nesting}', function ( Silex\Application $app, $id, $nesting ) use ($api) {
	return $api->getCollectionHierarchy( $id, $nesting );
} );

$app->get( '/revision/{id}', function ( Silex\Application $app, $id ) use ($api) {
	return $api->getRevision( $id );
} );


// Remove this in production
$app['debug'] = true;

$app->run();
