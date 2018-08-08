<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.slushman.com
 * @since             1.0.0
 * @package           RestApiSeo
 *
 * @wordpress-plugin
 * Plugin Name: 		REST API SEO
 * Plugin URI: 			https://www.github.com/slushman/rest-api-seo/
 * Description: 		Adds Yoast fields to REST API requests.
 * Author: 				slushman
 * Author URI: 			https://www.slushman.com
 * GitHub Plugin URI: 	https://github.com/slushman/rest-api-seo/
 * Github Branch: 		master
 * Version: 			1.0.0
 * License: 			GPL-2.0+
 * License URI: 		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 		rest-api-seo
 */

use RestApiSeo\Includes as Inc;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Current plugin version.
 */
define( 'RestApiSeo_VERSION', '1.0.0' );

/**
 * Include the autoloader.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';

/**
 * Activation and Deactivation Hooks.
 */
register_activation_hook( __FILE__, array( 'RestApiSeo\Includes\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'RestApiSeo\Includes\Deactivator', 'deactivate' ) );

/**
 * Initializes each class and adds the hooks action in each to init.
 *
 * @global 		$tout_social_buttons
 */
function rest_api_seo_init() {

	$classes[] = new Inc\i18n();
	$classes[] = new Inc\Fields();

	foreach ( $classes as $class ) {

		add_action( 'init', array( $class, 'hooks' ) );

	}

} // rest_api_seo_init()

add_action( 'plugins_loaded', 'rest_api_seo_init' );