<?php
namespace SugarEventCalendar\Meta_Boxes;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_add_event_meta_box() {

		ob_start();
		sc_add_event_meta_box();
		$output = ob_get_clean();

		$this->assertTrue( empty( $output ) );

	}

	function test_sc_render_event_config_meta_box() {

		$args = array( 'post_title'               => 'Test Event',
		               'sc_event_date'            => '2017-01-01',
		               'sc_event_time_hour'       => '10',
		               'sc_event_time_minute'     => '00',
		               'sc_event_time_am_pm'      => 'am',
		               'sc_event_end_date'        => '2017-01-01',
		               'sc_event_end_time_hour'   => '11',
		               'sc_event_end_time_minute' => '00',
		               'sc_event_end_time_am_pm'  => 'am',
		);

		// create an event
		$event_id = $this->factory->event->create_non_recurring( $args );

		global $post;
		$post = get_post( $event_id );

		ob_start();
		sc_render_event_config_meta_box();
		$output = ob_get_clean();

		$doc = new \DOMDocument();
		$doc->loadHTML( $output );

		$start_date = $doc->getElementById( 'sc_event_date' );
		$this->assertEquals( 'DOMElement', get_class( $start_date ) );
		$this->assertEquals( '01/01/2017', $start_date->getAttribute( 'value') );

		$end_date = $doc->getElementById( 'sc_event_end_date' );
		$this->assertEquals( 'DOMElement', get_class( $end_date ) );
		$this->assertEquals( '01/01/2017', $end_date->getAttribute( 'value') );

	}

	function test_sc_meta_box_save() {

		// set current user to administrator
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// Create a test event
		$args = array( 'post_title'               => 'Test Event',
		               'sc_event_date'            => '2017-01-01',
		               'sc_event_time_hour'       => '10',
		               'sc_event_time_minute'     => '00',
		               'sc_event_time_am_pm'      => 'am',
		               'sc_event_end_date'        => '2017-01-01',
		               'sc_event_end_time_hour'   => '11',
		               'sc_event_end_time_minute' => '00',
		               'sc_event_end_time_am_pm'  => 'am',
		);

		// create an event
		$event_id = $this->factory->event->create_non_recurring( $args );

		global $post;
		$post = get_post( $event_id );

		// update values for event
		$_POST['sc_meta_box_nonce']        = wp_create_nonce( 'meta-boxes.php' );
		$_POST['sc_event_date']            = '02/02/2017';
		$_POST['sc_event_time_hour']       = '08';
		$_POST['sc_event_time_minute']     = '08';
		$_POST['sc_event_time_am_pm']      = 'pm';
		$_POST['sc_event_end_date']        = '02/02/2017';
		$_POST['sc_event_end_time_hour']   = '10';
		$_POST['sc_event_end_time_minute'] = '10';
		$_POST['sc_event_end_time_am_pm']  = 'pm';

		ob_start();
		sc_meta_box_save( $event_id );
		$output = ob_get_clean();

		// make sure nothing was thrown while running the save
		$this->assertTrue( empty( $output ) );

		// check values for event
		$value = get_post_meta( $event_id, 'sc_event_date', true );
		$this->assertEquals( '1485993600', $value );

		$value = get_post_meta( $event_id, 'sc_event_day', true );
		$this->assertEquals( 'Thu', $value );

		$value = get_post_meta( $event_id, 'sc_event_day_of_week', true );
		$this->assertEquals( '4', $value );

		$value = get_post_meta( $event_id, 'sc_event_day_of_month', true );
		$this->assertEquals( '02', $value );

		$value = get_post_meta( $event_id, 'sc_event_day_of_year', true );
		$this->assertEquals( '32', $value );

		$value = get_post_meta( $event_id, 'sc_event_month', true );
		$this->assertEquals( '02', $value );

		$value = get_post_meta( $event_id, 'sc_event_year', true );
		$this->assertEquals( '2017', $value );

		$value = get_post_meta( $event_id, 'sc_event_time_hour', true );
		$this->assertEquals( '20', $value );

		$value = get_post_meta( $event_id, 'sc_event_time_minute', true );
		$this->assertEquals( '08', $value );

		$value = get_post_meta( $event_id, 'sc_event_time_am_pm', true );
		$this->assertEquals( 'pm', $value );

		$value = get_post_meta( $event_id, 'sc_event_date_time', true );
		$this->assertEquals( '1486066080', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_date', true );
		$this->assertEquals( '1485993600', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_day', true );
		$this->assertEquals( 'Thu', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_day_of_week', true );
		$this->assertEquals( '4', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_day_of_month', true );
		$this->assertEquals( '02', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_day_of_year', true );
		$this->assertEquals( '32', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_month', true );
		$this->assertEquals( '02', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_year', true );
		$this->assertEquals( '2017', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_time_hour', true );
		$this->assertEquals( '22', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_time_minute', true );
		$this->assertEquals( '10', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_time_am_pm', true );
		$this->assertEquals( 'pm', $value );

		$value = get_post_meta( $event_id, 'sc_event_end_date_time', true );
		$this->assertEquals( '1486073400', $value );

	}

}