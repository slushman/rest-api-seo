<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 			1.0.0
 * @package 		RestApiSeo
 * @subpackage 		RestApiSeo/Includes
 * @author 			slushman <chris@slushman.com>
 */

namespace RestApiSeo\Includes;

class i18n {

	/**
	 * Registers all the WordPress hooks and filters related to this class.
	 *
	 * @hooked 		init
	 * @since 		1.0.0
	 */
	public function hooks() {

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

	} // hooks()

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rest-api-seo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	} // load_plugin_textdomain()

} // class