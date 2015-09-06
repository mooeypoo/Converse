/*global module:false*/
module.exports = function ( grunt ) {
	// Project configuration.

	grunt.loadNpmTasks('grunt-contrib-less');
	// grunt.loadNpmTasks('grunt-contrib-jshint');
	// grunt.loadNpmTasks('grunt-jscs');
	// grunt.loadNpmTasks('grunt-contrib-concat');
	// grunt.loadNpmTasks('grunt-simple-mocha');
	// grunt.loadNpmTasks('grunt-jsduck');

	grunt.initConfig({
		// Metadata.
		pkg: grunt.file.readJSON( 'package.json' ),
		less: {
			development: {
				files: {
					'includes/styles/Converse.css': [ 'includes/widgets/less/*.less' ]
				}
			},
			production: {
				options: {
					cleancss: true
				},
				files: {
					'includes/styles/Converse.css': [ 'includes/widgets/less/*.less' ]
				}
			}
		},
	} );

	// Default task.
	grunt.registerTask( 'default', [ 'less' ] );
	// Build
	// grunt.registerTask( 'build', [ 'jshint', 'jscs', 'less', 'concat' ] );
};
