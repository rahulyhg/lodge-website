<?php
/**
 * Register post types and taxonomies.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package    Flashback
 */

/**
 * Register post types and taxonomies.
 *
 * @link       http://mckernan.in
 * @since      1.0.0
 * @package    Flashback
 */
class Flashback_Content_Types {
	/**
	 * Constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'post_type_timeline_item' ), 0 );
		add_action( 'init', array( $this, 'taxonomy_flashback_timeline_category' ), 0 );
	}

	/**
	 * Register the Timeline Item post type.
	 *
	 * @since    1.0.0
	 */
	public function post_type_timeline_item() {
		$labels = array(
			'name' 					=> 'Timeline Items',
			'singular_name' 		=> 'Timeline Item',
			'menu_name' 			=> 'Timeline Items',
			'parent_item_colon' 	=> 'Parent Item:',
			'all_items' 			=> 'All Timeline Items',
			'view_item' 			=> 'View Timeline Item',
			'add_new_item' 			=> 'Add Timeline Item',
			'add_new' 				=> 'Add Timeline Item',
			'edit_item' 			=> 'Edit Timeline Item',
			'update_item' 			=> 'Update Timeline Item',
			'search_items' 			=> 'Search Timeline Items',
			'not_found' 			=> 'Not found',
			'not_found_in_trash' 	=> 'Not found in Trash',
		);
		$args = array(
			'label' 				=> 'Timeline Items',
			'description' 			=> 'Timeline Items ',
			'labels' 				=> $labels,
			'supports' 				=> array( 'title', 'thumbnail', 'editor' ),
			'hierarchical' 			=> false,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'show_in_nav_menus' 	=> true,
			'show_in_admin_bar' 	=> true,
			'menu_position' 		=> 5,
			'menu_icon' 			=> 'dashicons-backup',
			'can_export' 			=> true,
			'has_archive'			=> false,
			'exclude_from_search' 	=> true,
			'publicly_queryable' 	=> true,
			'rewrite' 				=> false,
			'capability_type' 		=> 'page',
		);
		register_post_type( 'flb_timeline_item', $args );
	}

	/**
	 * Register the timeline category taxonomy.
	 *
	 * @since    1.0.0
	 */
	public function taxonomy_flashback_timeline_category() {
		$labels = array(
				'name' 							=> _x( 'Timeline Categories', 'Taxonomy General Name', 'flashback' ),
				'singular_name' 				=> _x( 'Timeline Category', 'Taxonomy Singular Name', 'flashback' ),
				'menu_name' 					=> __( 'Timeline Category', 'flashback' ),
				'all_items' 					=> __( 'All Timeline Categories', 'flashback' ),
				'parent_item' 					=> __( 'Parent Timeline Category', 'flashback' ),
				'parent_item_colon' 			=> __( 'Parent Timeline Category:', 'flashback' ),
				'new_item_name' 				=> __( 'New Timeline Category Name', 'flashback' ),
				'add_new_item' 					=> __( 'Add New Timeline Category', 'flashback' ),
				'edit_item' 					=> __( 'Edit Timeline Category', 'flashback' ),
				'update_item' 					=> __( 'Update Timeline Category', 'flashback' ),
				'separate_items_with_commas' 	=> __( 'Separate groups with commas', 'flashback' ),
				'search_items' 					=> __( 'Search Timeline Category', 'flashback' ),
				'add_or_remove_items' 			=> __( 'Add or remove groups', 'flashback' ),
				'choose_from_most_used' 		=> __( 'Choose from the most used groups', 'flashback' ),
				'not_found' 					=> __( 'Not Found', 'flashback' ),
			);
		$args = array(
			'labels' 				=> $labels,
			'hierarchical' 			=> true,
			'public' 				=> true,
			'show_ui' 				=> true,
			'show_admin_column' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud' 		=> false,
		);
		register_taxonomy( 'flb_timeline_category', array( 'flashback_timeline_item' ), $args );
	}
}
new Flashback_Content_Types();
