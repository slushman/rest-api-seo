<?php

/**
 * Autoloader for PHP 5.3+
 *
 * @link 		https://www.slushman.com
 * @since 		1.0.0
 * @package 	RestApiSeo\Includes
 * @author 		Slushman <chris@slushman.com>
 */

namespace RestApiSeo\Includes;

class Autoloader {

	/**
	 * Autoloader function.
	 *
	 * @param 		string 		$class_name 		The class name.
	 */
	public static function autoloader( $class_name ) {

		if ( false === strpos( $class_name, 'RestApiSeo' ) ) { return; }

		$file_parts = explode( '\\', $class_name );

		$namespace = '';

		for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

			$current = strtolower( $file_parts[$i] );
			$current = str_ireplace( '_', '-', $current );

			if ( count( $file_parts ) -1 === $i ) {

				if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {

					$interface_name = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
					$interface_name = $interface_name[0];

					$file_name = "interface-$interface_name.php";

				} else {

					$file_name = "class-$current.php";

				}

			} else {

				$namespace = '/' . $current . $namespace;

			}

		} // for

		$filepath = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
		$filepath .= $file_name;

		if ( file_exists( $filepath ) ) {

			include_once( $filepath );

		} else {

			wp_die(
				esc_html( "The file attempting to be loaded at $filepath does not exist in $class_name." )
			);

		}

	} // autoloader()

	public static function can_autoload( $class ) {

		return class_exists( $class );

		return $check;

	} // can_autoload()

} // class

spl_autoload_register( 'RestApiSeo\Includes\Autoloader::autoloader' );
