<?php
/**
 * Plugin Name: Tahosa Dues API
 */

class Tahosa_Dues_API {

	function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api_hooks' ) );
		add_action( 'init', array( $this, 'post_types' ) );
	}

	function register_api_hooks() {
		$namespace = 'dues-api/v1';
		register_rest_route( $namespace, '/get-member/', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'get_members' ),
		) );
	}
	function get_members() {
		if ( ! isset( $_GET['fname'] ) || ! isset( $_GET['lname'] ) || ! isset( $_GET['zip'] )  ) {
			$response = new WP_REST_Response( 'Missing query parameter' );
			$response->header( 'Access-Control-Allow-Origin', apply_filters( 'access_control_allow_origin', '*' ) );
			return $response;
		}
		$query = apply_filters( 'get_members_query', array(
			'post_type'   => 'oad_api_member',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'fname',
					'value' => $_GET['fname'],
					'compare' => 'LIKE',
				),
				array(
					'key' => 'lname',
					'value' => $_GET['lname'],
					'compare' => 'LIKE',
				),
				array(
					'key' => 'zip',
					'value' => $_GET['zip'],
					'compare' => 'LIKE',
				),
			),
		));
		$member = get_posts( $query );
		$meta = get_post_custom( current( $member )->ID );
		$return = [];
		foreach ( $meta as $key => $value) {
			$return[$key] = current( $value );
		}
		if ( empty( $return ) ) {
			$return = 'No member record found';
		}
		$response = new WP_REST_Response( $return );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'access_control_allow_origin', '*' ) );
		return $response;
	}

	public function post_types() {
		$args = array(
			'public' => false,
			'label'  => 'API Member',
		);
    	register_post_type( 'oad_api_member', $args );
	}
}

new Tahosa_Dues_API();
