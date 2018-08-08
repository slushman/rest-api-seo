<?php

/**
 * Defines the functionality for adding fields to REST API requests.
 *
 * @since 			1.0.0
 * @package 		RestApiSeo
 * @subpackage 		RestApiSeo/Includes
 * @author 			slushman <chris@slushman.com>
 */

namespace RestApiSeo\Includes;

class Fields {

	/**
	 * Registers all the WordPress hooks and filters related to this class.
	 *
	 * @hooked 		init
	 * @since 		1.0.0
	 */
	public function hooks() {

		add_action( 'rest_api_init', array( $this, 'add_meta_fields' ) );

	} // hooks()

	/**
	 * Registers fields for the REST requests for posts and pages.
	 * 
	 * @hooked 		rest_api_init
	 * @since 		1.0.0
	 */
	public function add_meta_fields() {

		register_rest_field( 'post', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta' ),
			'schema' 		=> null
		));

		register_rest_field( 'page', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta' ),
			'schema' 		=> null
		));

		register_rest_field( 'category', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta_for_category' ),
			'schema' 		=> null
		));

		register_rest_field( 'tag', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta_for_tag' ),
			'schema' 		=> null
		));

		$types = get_post_types( array(
			'public' => true,
			'_builtin' => false
		) );

		foreach ( $types as $key => $type ) {

			register_rest_field( $type, 'yoast', array(
				'get_callback' 	=> array( $this, 'add_yoast_meta' ),
				'schema' 		=> null
			));

		}

	} // add_meta_fields()

	/**
	 * Adds Yoast SEO metadata to the JSON data returned via the REST API.
	 * 
	 * @since 		1.0.0
	 * @return 		array 		An array of Yoast SEO metadata.
	 */
	public function add_yoast_meta( $post ) {

		$frontend = \WPSEO_Frontend::get_instance();
		$frontend->reset();

		$query['p'] 		= $post['id'];
		$query['post_type'] = 'any';

		query_posts( $query );

		the_post();

		$yoastMeta['title'] 		= $frontend->get_content_title();
		$yoastMeta['metadesc']		= $frontend->metadesc( false );
		$yoastMeta['canonical'] 	= $frontend->canonical( false );
		// $yoastMeta['opengraph_title'] 		= ! empty( $meta['_yoast_wpseo_opengraph-title'] ) ? $meta['_yoast_wpseo_opengraph-title'] : '';
		// $yoastMeta['opengraph_description'] = ! empty( $meta['_yoast_wpseo_opengraph-description'] ) ? $meta['_yoast_wpseo_opengraph-description'] : '';
		// $yoastMeta['opengraph_image'] 		= ! empty( $meta['_yoast_wpseo_opengraph-image'] ) ? $meta['_yoast_wpseo_opengraph-image'] : '';
		// $yoastMeta['twitter_title'] 		= ! empty( $meta['_yoast_wpseo_twitter-title'] ) ? $meta['_yoast_wpseo_twitter-title'] : '';
		// $yoastMeta['twitter_description'] 	= ! empty( $meta['_yoast_wpseo_twitter-description'] ) ? $meta['_yoast_wpseo_twitter-description'] : '';
		// $yoastMeta['twitter_image'] 		= ! empty( $meta['_yoast_wpseo_twitter-image'] ) ? $meta['_yoast_wpseo_twitter-image'] : '';
		// $yoastMeta['meta_robots_noindex'] 	= ! empty( $meta['_yoast_wpseo_meta-robots-noindex'] ) ? $meta['_yoast_wpseo_meta-robots-noindex'] : '';
		// $yoastMeta['meta_robots_nofollow'] 	= ! empty( $meta['_yoast_wpseo_meta-robots-nofollow'] ) ? $meta['_yoast_wpseo_meta-robots-nofollow'] : '';
		// $yoastMeta['meta_robots_adv'] 		= ! empty( $meta['_yoast_wpseo_meta-robots-adv'] ) ? $meta['_yoast_wpseo_meta-robots-adv'] : '';
		// $yoastMeta['redirect'] 				= ! empty( $meta['_yoast_wpseo_redirect'] ) ? $meta['_yoast_wpseo_redirect'] : '';

		wp_reset_query();

		return $yoastMeta;
		
	} // add_yoast_meta()

	/**
	 * Adds Yoast SEO metadata to the JSON data returned via the REST API for category requests.
	 * 
	 * @since 		1.0.0
	 * @return 		array 		An array of Yoast SEO metadata.
	 */
	public function add_yoast_meta_for_category( $category ) {

		$query['cat'] = $category['id'];

		query_posts( $query );

		the_post();

		$yoastMeta = $this->add_yoast_meta_taxonomy();

		wp_reset_query();

		return $yoastMeta;

	} // add_yoast_meta_for_category()

	/**
	 * Adds Yoast SEO metadata to the JSON data returned via the REST API for tag requests.
	 * 
	 * @since 		1.0.0
	 * @return 		array 		An array of Yoast SEO metadata.
	 */
	public function add_yoast_meta_for_tag( $tag ) {

		$query['tag_id'] = $tag['id'];

		query_posts( $query );

		the_post();

		$yoastMeta = $this->add_yoast_meta_taxonomy();

		wp_reset_query();

		return $yoastMeta;

	} // add_yoast_meta_for_tag()

	/**
	 * Adds Yoast SEO metadata to the JSON data returned via the REST API for taxonomy requests.
	 * 
	 * @since 		1.0.0
	 * @return 		array 		An array of Yoast SEO metadata.
	 */
	public function add_yoast_meta_taxonomy() {

		$frontend = \WPSEO_Frontend::get_instance();
		$frontend->reset();

		$yoastMeta['title'] 	= $frontend->get_taxonomy_title();
		$yoastMeta['metadesc']	= $frontend->metadesc( false );

		return $yoastMeta;

	} // add_yoast_meta_taxonomy()

} // class