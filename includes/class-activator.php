<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 			1.0.0
 * @package 		RestApiSeo
 * @subpackage 		RestApiSeo/Includes
 * @author 			slushman <chris@slushman.com>
 */

namespace RestApiSeo\Includes;

class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$check = Activator::check_for_yoast_seo();

		if ( ! $check ) {

			deactivate_plugins( plugin_basename( __FILE__ ), true );

			$url 		= esc_url( admin_url( 'plugins-install.php?s=Yoast+SEO&tab=search' ) );
			$message 	= sprintf( wp_kses( __( 'Please install and activate the Yoast SEO plugin before activating REST API SEO.<p><a href="%1$s">Install Yoast SEO from the WordPress plugin directory.</a></p>', 'rest-api-seo' ), array( 'a' => array( 'href' => array() ), 'p' => array() ) ), $url );

		}

	} // activate()

	/**
	 * Checks for the existance of the TOUT_SOCIAL_BUTTONS_FILE constant
	 * and if the plugin is activated.
	 *
	 * @since 		1.0.0
	 * @return 		bool 		TRUE if activated, otherwise FALSE.
	 */
	public static function check_for_yoast_seo() {

		if ( ! defined( 'WPSEO_VERSION' ) ) { return FALSE; }

		$plugins = get_option( 'active_plugins' );

		if ( ! in_array( 'wordpress-seo/wp-seo.php', $plugins ) ) { return FALSE; }

		return TRUE;

	} // check_for_yoast_seo()

} // class
