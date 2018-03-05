<?php
namespace SugarEventCalendar\Table_Columns;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_event_columns() {

		$columns = sc_event_columns( array() );

		$this->assertTrue( is_array( $columns ) );
		$this->assertArrayHasKey( 'cb', $columns );
		$this->assertArrayHasKey( 'title', $columns );
		$this->assertArrayHasKey( 'event_date', $columns );
		$this->assertArrayHasKey( 'event_time', $columns );
		$this->assertArrayHasKey( 'category', $columns );
		$this->assertArrayHasKey( 'date', $columns );
	}

	function test_sc_render_event_columns() {

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

		ob_start();
		sc_render_event_columns( 'event_date', $event_id );
		$output = ob_get_clean();

		$this->assertEquals( 'January 1, 2017', $output );

		ob_start();
		sc_render_event_columns( 'event_time', $event_id );
		$output = ob_get_clean();

		$this->assertEquals( '10:00 am', $output );

		ob_start();
		sc_render_event_columns( 'category', $event_id );
		$output = ob_get_clean();

		$this->assertEquals( '', $output );

	}

	function test_sc_sortable_columns() {

		$columns = sc_sortable_columns( array() );

		$this->assertTrue( is_array( $columns ) );
		$this->assertArrayHasKey( 'event_date', $columns );

	}

	function test_sc_sort_events() {

		$vars = array( 'post_type' => 'sc_event',
		               'orderby'   => 'event_date'
		);

		$vars = sc_sort_events( $vars );
		$this->assertTrue( is_array( $vars ) );
		$this->assertArrayHasKey( 'meta_key', $vars );
		$this->assertEquals( 'meta_value_num', $vars['orderby'] );

	}

	function test_sc_event_list_load() {

		sc_event_list_load();
		$priority = has_filter( 'request', 'sc_sort_events' );

		$this->assertEquals( 10, $priority );

	}

	function test_sc_add_event_filters() {


		// With no events in database output should be empty
		ob_start();
		sc_add_event_filters();
		$output = ob_get_clean();

		$this->assertTrue( empty( $output ) );



		global $typenow;
		$typenow = 'sc_event';

		// Add two
		$term1 = wp_insert_term( 'Birthdays','sc_event_category' );
		$term2 = wp_insert_term( 'Social','sc_event_category' );

		// Add events assigned to categories
		$args = array( 'post_title'               => 'Joe Birthday',
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

		// add terms
		wp_set_object_terms( $event_id, $term1, 'sc_event_category' );
		wp_set_object_terms( $event_id, $term2, 'sc_event_category' );

		// Add events assigned to categories
		$args = array( 'post_title'               => 'Family Dinner',
		               'sc_event_date'            => '2017-01-02',
		               'sc_event_time_hour'       => '07',
		               'sc_event_time_minute'     => '00',
		               'sc_event_time_am_pm'      => 'pm',
		               'sc_event_end_date'        => '2017-01-02',
		               'sc_event_end_time_hour'   => '11',
		               'sc_event_end_time_minute' => '00',
		               'sc_event_end_time_am_pm'  => 'pm',
		);

		// create an event
		$event_id = $this->factory->event->create_non_recurring( $args );
		// add terms
		wp_set_object_terms( $event_id, $term1, 'sc_event_category' );


		ob_start();
		sc_add_event_filters();
		$output = ob_get_clean();

		$doc = new \DOMDocument();
		$doc->loadhtml( $output );

		$outer_select = $doc->getElementById( 'sc_event_category' );

		$this->assertEquals( 'DOMElement', get_class( $outer_select ) );
	}

}