<?php

class Tahosa_Event_Registration {

	/**
	 * Holds an instance of the object
	 *
	 * @var Tahosa_Lodge_Registration_Admin
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Tahosa_Lodge_Registration_Admin
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	protected static $guest_checkout_option_changed;

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_filter( 'body_class', [ $this, 'ticket_body_class' ] );
		add_filter( 'woocommerce_placeholder_img_src', [ $this, 'custom_woocommerce_placeholder' ] );
		add_filter( 'woocommerce_sale_flash', [ $this, 'custom_sale_text' ] );
		add_filter( 'wocommerce_box_office_input_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_filter( 'wocommerce_box_office_option_field_template_vars', [ $this, 'class_to_ticket_fields' ] );
		add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
		add_filter( 'product_type_options', [ $this, 'ticket_type_option' ], 20 );
		add_filter( 'woocommerce_product_tabs', [ $this, 'woo_remove_product_tabs' ], 98 );
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'force_individual_cart_items' ], 10, 2 );
		add_filter( 'woocommerce_quantity_input_args', [ $this, 'woocommerce_quantity_input_args' ], 10, 2 );
		add_action( 'wfobp_product_status', function() {
			return 'any';
		});
		add_action( 'wp_print_scripts', function() {
			if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
				wp_dequeue_script( 'wc-password-strength-meter' );
			}
		});
		add_action( 'rest_api_init', [ $this, 'register_api_hooks' ] );
		add_action( 'cmb2_admin_init', [ $this, 'product_metaboxes' ] );
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'active_arrowman_discount' ] );
		add_action( 'woocommerce_checkout_fields', [ $this, 'make_checkout_account_fields_required' ] );
		add_action( 'woocommerce_single_product_summary', [ $this, 'woocommerce_template_product_description' ], 20 );

		add_shortcode( 'th_shop_messages', [ $this, 'messages_shortcode' ] );
	}

	public static function is_ticket( $id = null ) {
		if ( null === $id ) {
			$id = get_the_id();
		}
		$ticket      = false;
		$ticket_meta = get_post_meta( $id, '_ticket', true );
		if ( 'yes' === $ticket_meta ) {
			$ticket = true;
		}
		return $ticket;
	}

	public function ticket_body_class( $classes ) {
		global $post;
		$data = [
			'id'        => $post->ID,
			'is_ticket' => $this->is_ticket( $post->ID ),
		];
		if ( $this->is_ticket( $post->ID ) ) {
			$classes[] = 'woocommerce-ticket';
		}
		return $classes;
	}

	/**
	 * Function to return new placeholder image URL.
	 */
	public function custom_woocommerce_placeholder( $image_url ) {
		global $product;
		if ( is_object( $product ) && in_array( 107, $product->get_category_ids() ) ) {
			return 'https://tahosawp.s3.amazonaws.com/uploads/2017/06/cuboree-product.png';
		}
			$image_url = 'https://tahosawp.s3.amazonaws.com/uploads/2017/06/tahosa-og.png';
			return $image_url;
	}

	public function custom_sale_text( $html ) {
		global $post;
		if ( $this->is_ticket( $post->ID ) ) {
			return str_replace( __( 'Sale!', 'woocommerce' ), __( 'Early Discount!', 'woocommerce' ), $html );
		}
		return $html;
	}

	public function class_to_ticket_fields( $vars ) {
		$slug                 = sanitize_title( $vars['label'] );
		$vars['before_field'] = "<p class='form-row ${slug}'>";
		return $vars;
	}

	public function checkout_fields( $fields ) {
		unset( $fields['billing']['billing_company'] );
		unset( $fields['shipping']['billing_company'] );
		return $fields;
	}

	public function register_api_hooks() {
		register_rest_route( 'tahosa-events/v1', '/tickets/', array(
			'methods'  => 'GET',
			'callback' => [ $this, 'ticket_endpoint' ],
		));

		register_rest_route( 'tahosa-events/v1', '/details/(?P<id>\d+)', array(
			'methods'  => 'GET',
			'callback' => [ $this, 'details_endpoint' ],
		));
	}

	public function ticket_endpoint() {
		$args   = [
			'post_type'      => 'event_ticket',
			'posts_per_page' => 1000,
		];
		$query  = new WP_Query( $args );
		$events = [
			'cuboree-staff'   => [
				'name'        => 'Cuboree Staff',
				'total'       => 0,
				'by-reg-type' => [],
			],
			'cuboree'         => [
				'name'        => 'Cuboree',
				'total'       => 0,
				'by-reg-type' => [],
			],
			'fall-fellowship' => [
				'name' => 'Fall Fellowship',
				'total' => 0,
				'by-chapter' => [
					'kodiak' => 0,
					'medicine-bear' => 0,
					'medicine-pipe' => 0,
					'running-antelope' => 0,
					'spirit-eagle' => 0,
					'white-buffalo' => 0,
					'white-eagle' => 0,
				],
				'by-age' => [
					'youth' => 0,
					'adult' => 0,
				],
				'by-level' => [
					'ordeal' => 0,
					'brotherhood' => 0,
					'vigil' => 0,
				],
				'by-reg-type' => [],
			],
			'lld' => [
				'name' => 'LLD',
				'total' => 0,
			],
			'noac' => [
				'name' => 'NOAC',
				'total' => 0,
			],
			'banquet' => [
				'name' => 'Lodge Banquet 2018',
				'total' => 0,
			],
			'active-arrowman' => [
				'name' => 'Active Arrowman 2018',
				'total' => 0,
			],
			'2018-lodge-dues' => [
				'name' => 'Active Arrowman 2018',
				'total' => 0,
			]
		];
		foreach ( $query->posts as $ticket ) {
			$post_title = sanitize_title( $ticket->post_title );
			foreach ( $events as $key => $value ) {
				if ( false !== strpos( $post_title, $key ) ) {
					$events[$key]['total']++;
					$post_title = str_replace( $key . '-', '', $post_title );
					$event = $key;
					break;
				}
			}
			if ( isset( $events[$event]['by-reg-type'][ $post_title ] ) && '' !== $event ) {
				$events[$event]['by-reg-type'][ $post_title ]++;
			} else {
				$events[$event]['by-reg-type'][ $post_title ] = 1;
			}

			if ( 'fall-fellowship' === $event ) {
				$chapter = sanitize_title( get_post_meta( $ticket->ID, 'b0d31d24234398f1bb20d045ce19501c', true ) );
				$level = sanitize_title( get_post_meta( $ticket->ID, 'ef4e5bbc80fc1d0acdfb84f27f591657', true ) );
				$age = sanitize_title( get_post_meta( $ticket->ID, '47c172cd25856ce5759c249a929c276e', true ) );
				$events[$event]['by-chapter'][$chapter]++;
				$events[$event]['by-age'][$age]++;
				$events[$event]['by-level'][$level]++;
			}
		}
		$response = new WP_REST_Response( $events );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'spe_access_control_allow_origin', '*' ) );
		return $response;
	}

	public function details_endpoint( $data ) {
		if ( ! is_user_logged_in()) {
			$response = new WP_REST_Response( 'unauthorized' );
			return $response;
		}
		$args = [
			'post_type'      => 'event_ticket',
			'posts_per_page' => 200,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'cache_results'  => false,
			'meta_query' => array(
				array(
					'key' => '_product',
					'value' => $data['id'],
					'compare' => 'IN',
				),
			),
		];
		$tickets = get_posts($args);
		$output = [];
		foreach ( $tickets as $ticket ) {
			$output[] = [
				'ticket_id'     => $ticket->ID,
				'ticket_status' => get_post_status( $ticket_id ),
				'ticket_name'   => $ticket->post_title,
				'ticket_url'    => $ticket_url,
				'purchase_date' => $purchase_time,
				'order_id'      => $order_id,
				'order_status'  => wc_get_order_status_name( get_post_status( $order_id ) ),
				'user_id'       => $user_id,
			];
		}
		$response = new WP_REST_Response( $query->posts );
		$response->header( 'Access-Control-Allow-Origin', apply_filters( 'spe_access_control_allow_origin', '*' ) );
		return $response;
	}

	/**
	 * Add 'Ticket' option to products.
	 *
	 * @param  array  $options Default options
	 * @return array           Modified options
	 */
	public function ticket_type_option( $options = array() ) {
		$options['ticket'] = array(
			'id'            => '_ticket',
			'wrapper_class' => 'show_if_simple show_if_variable hide_if_deposit',
			'label'         => __( 'Ticket', 'woocommerce-box-office' ),
			'description'   => __( 'Each ticket purchased will have attendee details added to it.', 'woocommerce-box-office' ),
			'default'       => 'no',
		);

		return $options;
	}

	public function logged_in_required() {
		$login_required = false;
		if ( ! empty( WC()->cart->cart_contents ) ) {
			foreach ( WC()->cart->cart_contents as $cart_item ) {
				$login_meta = get_post_meta( $cart_item['product_id'], '_tahosareg_logged_in', true );
				if ( $login_meta ) {
					$login_required = true;
					break;
				}
			}
		}
		return $login_required;
	}

	public function product_metaboxes() {
		$cmb = new_cmb2_box([
			'id'           => 'tahosa_product_fields',
			'title'        => __( 'Product Customizations', 'tahosalodge' ),
			'object_types' => [ 'product' ],
			'context'      => 'normal',
			'priority'     => 'core',
			'show_names'   => true,
		]);

		$prefix = '_tahosareg_';

		$cmb->add_field([
			'name' => 'Force Logged In User',
			'id'   => $prefix . 'logged_in',
			'type' => 'checkbox',
		]);

		$cmb->add_field([
			'name' => 'Active Arrowman Event',
			'id'   => $prefix . 'active_arrowman',
			'type' => 'checkbox',
		]);
	}

	public function make_checkout_account_fields_required( $checkout_fields ) {

		if ( $this->logged_in_required() && ! is_user_logged_in() ) {

			$account_fields = array(
				'account_username',
				'account_password',
				'account_password-2',
			);

			foreach ( $account_fields as $account_field ) {
				if ( isset( $checkout_fields['account'][ $account_field ] ) ) {
					$checkout_fields['account'][ $account_field ]['required'] = true;
				}
			}
		}

		return $checkout_fields;
	}

	public function active_arrowman_discount( $cart ) {
		if ( ! empty( $cart->cart_contents ) ) {
			$aa_count = 0;
			$aa_eligible = [];

			foreach ( $cart->cart_contents as $cart_item ) {
				$product = wc_get_product( $cart_item['product_id']);
				$is_aa = $product->get_slug() === 'active-arrowman-2018' ? true : false;
				if ( $is_aa ) {
					$aa_count++;
				}
				$aa_event = get_post_meta( $cart_item['product_id'], '_tahosareg_active_arrowman', true );
				if ($aa_event) {
					$aa_eligible[] = $cart_item['key'];
				}
			}

			foreach ( $aa_eligible as $key ) {
				if ($aa_count < 1) {
					return;
				}
				$price_change = $cart->cart_contents[$key]['data']->set_price(0);
				$aa_count--;
			}
		}
	}

	function woocommerce_template_product_description() {
		wc_get_template( 'single-product/tabs/description.php' );
	}

	function woo_remove_product_tabs( $tabs ) {
	  unset( $tabs['description'] );        // Remove the description tab
	  unset( $tabs['reviews'] );            // Remove the reviews tab
	  return $tabs;
	}

	public function force_individual_cart_items( $cart_item_data, $product_id ) {
	  $unique_cart_item_key = md5( microtime() . rand() );
	  $cart_item_data['unique_key'] = $unique_cart_item_key;

	  return $cart_item_data;
	}

	public function woocommerce_quantity_input_args( $args, $product ) {
		$slug = $product->get_data()['slug'];
		$aa_event = get_post_meta( $product->get_id(), '_tahosareg_active_arrowman', true );

		if ( false !== strpos( 'active-arrowman', $slug ) || $aa_event ) {
			$args['input_value'] 	= 1;	// Starting value (we only want to affect product pages, not cart)
			$args['max_value'] 	= 1; 	// Maximum value
			$args['min_value'] 	= 1;   	// Minimum value
		}
		return $args;
	}

	public function messages_shortcode() {
		if (is_admin) {
			return 'Hi admin! This shortcode will display properly on the front end.';
		}
		return do_shortcode('[shop_messages]');
	}
}
