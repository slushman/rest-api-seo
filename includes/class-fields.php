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

		add_action( 'rest_api_init', array( $this, 'register_fields' ) );

	} // hooks()

	/**
	 * Registers fields for the REST requests.
	 * 
	 * @hooked 		rest_api_init
	 * @since 		1.0.0
	 */
	public function register_fields() {

		// register_rest_field( 'category', 'yoast', array(
		// 	'get_callback' 	=> array( $this, 'add_yoast_meta_for_category' ),
		// 	'schema' 		=> null
		// ));

		register_rest_field( 'page', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta' ),
			'schema' 		=> null
		));

		register_rest_field( 'post', 'yoast', array(
			'get_callback' 	=> array( $this, 'add_yoast_meta' ),
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

		// register_rest_field( 'tag', 'yoast', array(
		// 	'get_callback' 	=> array( $this, 'add_yoast_meta_for_tag' ),
		// 	'schema' 		=> null
		// ));

	} // register_fields()

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

		$yoastMeta['title'] 				= $frontend->get_content_title();
		$yoastMeta['metadesc']				= $frontend->metadesc( false );
		$yoastMeta['canonical'] 			= $frontend->canonical( false );
		$yoastMeta['opengraph_title'] 		= $this->get_social_meta( get_the_ID(), 'opengraph', 'title', $frontend );
		$yoastMeta['opengraph_description'] = $this->get_social_meta( get_the_ID(), 'opengraph', 'description', $frontend );
		$yoastMeta['opengraph_image'] 		= $this->get_social_meta( get_the_ID(), 'opengraph', 'image', $frontend );
		$yoastMeta['twitter_title'] 		= $this->get_social_meta( get_the_ID(), 'twitter', 'title', $frontend );
		$yoastMeta['twitter_description'] 	= $this->get_social_meta( get_the_ID(), 'twitter', 'description', $frontend );
		$yoastMeta['twitter_image'] 		= $this->get_social_meta( get_the_ID(), 'twitter', 'image', $frontend );
		$yoastMeta['meta_robots_noindex'] 	= get_post_meta( get_the_ID(), '_yoast_wpseo_meta-robots-noindex' );
		$yoastMeta['meta_robots_nofollow'] 	= get_post_meta( get_the_ID(), '_yoast_wpseo_meta-robots-nofollow' );
		$yoastMeta['meta_robots_adv'] 		= get_post_meta( get_the_ID(), '_yoast_wpseo_meta-robots-adv'  );

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

		$frontend = \WPSEO_Frontend::get_instance();
		$frontend->reset();

		$queryArgs['cat'] 	= $category['id'];
		$query 				= new \WP_Query( $queryArgs );
		$yoastMeta 			= array();

		if ( $query->have_posts() ) :

			while( $query->have_posts() ) :

				$query->the_post();

				$yoastMeta['title'] 	= $frontend->get_taxonomy_title();
				$yoastMeta['metadesc']	= $frontend->metadesc( false );

			endwhile;

		endif;

		// query_posts( $queryArgs );
		
		// the_post();

		//$yoastMeta['title'] 	= $frontend->get_taxonomy_title();
		//$yoastMeta['metadesc']	= $frontend->metadesc( false );

		//$yoastMeta = $this->add_yoast_meta_taxonomy();

		//wp_reset_query();

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

	/**
	 * Returns the requested field or its fallback.
	 * 
	 * @since 		1.0.0
	 * @param 		int 				$postID 		The post ID.
	 * @param 		string 				$network 		The social network.
	 * 														opengraph or twitter
	 * @param 		string 				$field 			The field name.
	 * 														title, description, or image
	 * @param 		WPSEO_Frontend 		$frontend 		Instance of WPSEO_Frontend
	 * @return 		string 								The meta data.
	 */
	public function get_social_meta( $postID, $network, $field, $frontend ) {

		$metaKey 	= '_yoast_wpseo_' . strtolower( $network ) . '-' . strtolower( $field );
		$metaData 	= get_post_meta( $postID, $metaKey, true );

		if ( is_string( $metaData ) && '' !== $metaData && ! empty( $metaData ) ) {

			return $metaData;

		} 

		if ( 'title' === $field ) {

			$metaData = $frontend->get_content_title();

		} elseif ( 'description' === $field ) {

			$metaData = $frontend->metadesc( false );

		} else {

			$metaData = \WPSEO_Options::get( 'og_default_image' );

		}

		return $metaData;

	} // get_social_meta()

} // class