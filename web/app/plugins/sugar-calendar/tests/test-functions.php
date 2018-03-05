<?php
namespace SugarEventCalendar\Sugar_Functions;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_draw_calendar() {

		// This is a blank calendar since no events have been added
		$month = 1;
		$year = 2015;
		$size = 'large';
		$category = null;

		$calendar = sc_draw_calendar( $month, $year, $size, $category);

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_draw_calendar_month() {

		$display_time = time();
		$calendar = sc_draw_calendar_month( $display_time, 'large', null );

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_draw_calendar_week() {

		$display_time = time();
		$calendar = sc_draw_calendar_week( $display_time, 'large', null );

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_draw_calendar_2week() {

		$display_time = time();
		$calendar = sc_draw_calendar_2week( $display_time, 'large', null );

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_draw_calendar_day() {

		$display_time = time();
		$calendar = sc_draw_calendar_day( $display_time, 'large', null );

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_draw_calendar_4day() {

		$display_time = time();
		$calendar = sc_draw_calendar_4day( $display_time, 'large', null );

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$finder = new \DOMXPath( $doc );

		$classname = "calendar";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one table with class calendar
		$this->assertEquals( 1, $nodes->length );

	}

	function test_sc_month_num_to_name() {

		$month_name = sc_month_num_to_name( 1 );
		$this->assertEquals( 'January', $month_name );

		$month_name = sc_month_num_to_name( 2 );
		$this->assertEquals( 'February', $month_name );

		$month_name = sc_month_num_to_name( 3 );
		$this->assertEquals( 'March', $month_name );

		$month_name = sc_month_num_to_name( 4 );
		$this->assertEquals( 'April', $month_name );

		$month_name = sc_month_num_to_name( 5 );
		$this->assertEquals( 'May', $month_name );

		$month_name = sc_month_num_to_name( 6 );
		$this->assertEquals( 'June', $month_name );

		$month_name = sc_month_num_to_name( 7 );
		$this->assertEquals( 'July', $month_name );

		$month_name = sc_month_num_to_name( 8 );
		$this->assertEquals( 'August', $month_name );

		$month_name = sc_month_num_to_name( 9 );
		$this->assertEquals( 'September', $month_name );

		$month_name = sc_month_num_to_name( 10 );
		$this->assertEquals( 'October', $month_name );

		$month_name = sc_month_num_to_name( 11 );
		$this->assertEquals( 'November', $month_name );

		$month_name = sc_month_num_to_name( 12 );
		$this->assertEquals( 'December', $month_name );

	}

	function test_sc_is_calendar_page() {

		$is_calendar_page = sc_is_calendar_page();
		$this->assertFalse( $is_calendar_page );

		$args = array(
			'post_title'   => 'Calendar Page',
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => '[sc_events_calendar]',

		);

		$post_id = wp_insert_post( $args );

		global $post;
		$post = get_post( $post_id );

		$is_calendar_page = sc_is_calendar_page();
		$this->assertTrue( $is_calendar_page );

	}

	function test_sc_get_event_date() {

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

		$date = sc_get_event_date( $event_id, false );
		$this->assertEquals( '1483264800', $date );

		$date = sc_get_event_date( $event_id, true );
		$this->assertEquals( 'January 1, 2017', $date );


	}

	function test_sc_get_formatted_date() {
		$date = sc_get_formatted_date( 0, '1483264800' );
		$this->assertEquals( 'January 1, 2017', $date );

	}

	function test_sc_get_event_time() {

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

		$date = sc_get_event_time( $event_id );

		$this->assertTrue( is_array( $date ) );
		$this->assertEquals( '10:00 am', $date['start'] );
		$this->assertEquals( '11:00 am', $date['end'] );

	}

	function test_sc_get_event_start_time() {

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

		$time = sc_get_event_start_time( $event_id );

		$this->assertEquals( '10:00 am', $time);

	}

	function test_sc_get_event_end_time() {
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

		$time = sc_get_event_end_time( $event_id );

		$this->assertEquals( '11:00 am', $time);
	}

	function test_sc_is_recurring() {
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

		$event_id = $this->factory->event->create_non_recurring( $args );

		$is_recurring = sc_is_recurring( $event_id );
		$this->assertFalse( $is_recurring );

		$args = array(  'post_title'               => 'Test Event',
						'sc_event_date'            => '2017-01-01',
						'sc_event_time_hour'       => '10',
						'sc_event_time_minute'     => '00',
						'sc_event_time_am_pm'      => 'am',
						'sc_event_end_date'        => '2017-01-01',
						'sc_event_end_time_hour'   => '11',
						'sc_event_end_time_minute' => '00',
						'sc_event_end_time_am_pm'  => 'am',
						'sc_event_recurring'       => 'weekly',
						'sc_recur_until'           => '03/31/2017',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		$is_recurring = sc_is_recurring( $event_id );
		$this->assertTrue( $is_recurring );


	}

	function test_sc_get_recurring_period() {
		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		$period = sc_get_recurring_period( $event_id );
		$this->assertEquals( 'weekly', $period );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-02',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-02',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'monthly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		$period = sc_get_recurring_period( $event_id );
		$this->assertEquals( 'monthly', $period );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-03',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-03',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'yearly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		$period = sc_get_recurring_period( $event_id );
		$this->assertEquals( 'yearly', $period );
	}

	function test_sc_get_recurring_stop_date() {

		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		);
		$event_id = $this->factory->event->create_non_recurring( $args );

		$date = sc_get_recurring_stop_date( $event_id );
		$this->assertEmpty( $date );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-03',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-03',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '02/28/2017',
		);
		$event_id = $this->factory->event->create_recurring( $args );
		$date = sc_get_recurring_stop_date( $event_id );
		$this->assertEquals( '1488240000', $date );


	}

	function test_sc_get_recurring_events() {

		// test weekly
		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '08',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '09',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id1 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '09',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '10',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id2 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id3 = $this->factory->event->create_recurring( $args );

		$time = '1483851600';   // 2017-01-08
		$type = 'weekly';
		$category = '';
		$events = sc_get_recurring_events( $time, $type, $category);

		$this->assertTrue(in_array( $event_id1, $events ) );
		$this->assertTrue(in_array( $event_id2, $events ) );
		$this->assertTrue(in_array( $event_id3, $events ) );

		// test monthly
		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-02',
		                'sc_event_time_hour'       => '08',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-02',
		                'sc_event_end_time_hour'   => '09',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'monthly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id1 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-02',
		                'sc_event_time_hour'       => '09',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-02',
		                'sc_event_end_time_hour'   => '10',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'monthly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id2 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-02',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-02',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'monthly',
		                'sc_recur_until'           => '03/31/2017',
		);
		$event_id3 = $this->factory->event->create_recurring( $args );

		$time = '1486011600';   // 2017-02-02
		$type = 'monthly';
		$category = '';
		$events = sc_get_recurring_events( $time, $type, $category);

		$this->assertTrue(in_array( $event_id1, $events ) );
		$this->assertTrue(in_array( $event_id2, $events ) );
		$this->assertTrue(in_array( $event_id3, $events ) );


		// test yearly
		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-03',
		                'sc_event_time_hour'       => '08',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-03',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'yearly',
		                'sc_recur_until'           => '03/31/2019',
		);
		$event_id1 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-03',
		                'sc_event_time_hour'       => '09',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-03',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'yearly',
		                'sc_recur_until'           => '03/31/2019',
		);
		$event_id2 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-03',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-03',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'yearly',
		                'sc_recur_until'           => '03/31/2019',
		);
		$event_id3 = $this->factory->event->create_recurring( $args );

		$time = '1514977200';   // 2018-01-03 11:00:00
		$type = 'yearly';
		$category = '';
		$events = sc_get_recurring_events( $time, $type, $category);
		$this->assertTrue(in_array( $event_id1, $events ) );
		$this->assertTrue(in_array( $event_id2, $events ) );
		$this->assertTrue(in_array( $event_id3, $events ) );

	}

	function test_sc_show_single_recurring_date() {

		// Single non-recurring event
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
		sc_show_single_recurring_date( $event_id );
		$output= ob_get_clean();

		$this->assertEquals( '', $output );

		// Weekly recurring event with no end date
		$args = array(  'post_title'               => 'Friday Standup',
		                'sc_event_date'            => '2017-01-06',
		                'sc_event_time_hour'       => '08',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-06',
		                'sc_event_end_time_hour'   => '09',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
						'sc_recur_until'           => '',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		ob_start();
		sc_show_single_recurring_date( $event_id );
		$output= ob_get_clean();

		$this->assertEquals( 'Starts January 6, 2017 then every Friday', $output );

		// Weekly recurring event with an end date
		$args = array(  'post_title'               => 'Test Event',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '03/31/2019',
		);
		$event_id = $this->factory->event->create_recurring( $args );

		ob_start();
		sc_show_single_recurring_date( $event_id );
		$output= ob_get_clean();

		$this->assertEquals( 'Starts January 1, 2017 then every Sunday until March 31, 2019', $output );

	}

	function test_sc_get_date_format() {
		$format = sc_get_date_format();
		$this->assertEquals( 'F j, Y', $format );
	}

	function test_sc_get_time_format() {
		$format = sc_get_time_format();
		$this->assertEquals( 'g:i a', $format );
	}

	function test_sc_get_week_start_day() {
		$day = sc_get_week_start_day();
		$this->assertEquals( '0', $day );
	}

	function test_sc_update_recurring_events() {

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

		// create a non-recurring event
		$event_id = $this->factory->event->create_non_recurring( $args );

		// add partial meta to make it recurring
		update_post_meta( $event_id, 'sc_event_recurring', 'weekly' );
		update_post_meta( $event_id, 'sc_recur_until', strtotime( '01/31/2017' ) );

		sc_update_recurring_events( $event_id );

		$recurrences = get_post_meta( $event_id, 'sc_all_recurring', true );

		$this->assertTrue( is_array( $recurrences ) );
		$this->assertEquals( 5, count( $recurrences ) );
		$this->assertTrue(in_array( '1483264800', $recurrences ) );
		$this->assertTrue(in_array( '1483869600', $recurrences ) );
		$this->assertTrue(in_array( '1484474400', $recurrences ) );
		$this->assertTrue(in_array( '1485079200', $recurrences ) );
		$this->assertTrue(in_array( '1485684000', $recurrences ) );

	}

	function test_sc_calculate_recurring() {

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

		// create a non-recurring event
		$event_id = $this->factory->event->create_non_recurring( $args );

		// add partial meta to make it recurring
		update_post_meta( $event_id, 'sc_event_recurring', 'weekly' );
		update_post_meta( $event_id, 'sc_recur_until', strtotime( '01/31/2017' ) );

		$recurrences = sc_calculate_recurring( $event_id );

		$this->assertTrue( is_array( $recurrences ) );
		$this->assertEquals( 5, count( $recurrences ) );
		$this->assertTrue(in_array( '1483264800', $recurrences ) );
		$this->assertTrue(in_array( '1483869600', $recurrences ) );
		$this->assertTrue(in_array( '1484474400', $recurrences ) );
		$this->assertTrue(in_array( '1485079200', $recurrences ) );
		$this->assertTrue(in_array( '1485684000', $recurrences ) );

	}

	function test_sc_get_all_events() {

		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		);
		$event_id1 = $this->factory->event->create_non_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-02',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-02',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '01/31/2017',
		);
		$event_id2 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-15',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-15',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		);
		$event_id3 = $this->factory->event->create_non_recurring( $args );

		$events = sc_get_all_events();
		$this->assertTrue( is_array( $events ) );
		$this->assertEquals( 7, count( $events ) );
		$this->assertTrue(isset( $events['1483264800'] ) );
		$this->assertTrue(isset( $events['1483351200'] ) );
		$this->assertTrue(isset( $events['1483956000'] ) );
		$this->assertTrue(isset( $events['1484474400'] ) );
		$this->assertTrue(isset( $events['1484560800'] ) );
		$this->assertTrue(isset( $events['1485165600'] ) );
		$this->assertTrue(isset( $events['1485770400'] ) );

	}

	function test_sc_order_events_by_time() {

	}

	function test_sc_get_events_for_day() {

		$args = array(  'post_title'               => 'Test Event 1',
		                'sc_event_date'            => '2017-01-01',
		                'sc_event_time_hour'       => '08',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-01',
		                'sc_event_end_time_hour'   => '09',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '01/31/2017',
		);
		$event_id1 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 2',
		                'sc_event_date'            => '2017-01-08',
		                'sc_event_time_hour'       => '10',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-08',
		                'sc_event_end_time_hour'   => '11',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		                'sc_event_recurring'       => 'weekly',
		                'sc_recur_until'           => '01/31/2017',
		);
		$event_id2 = $this->factory->event->create_recurring( $args );

		$args = array(  'post_title'               => 'Test Event 3',
		                'sc_event_date'            => '2017-01-15',
		                'sc_event_time_hour'       => '11',
		                'sc_event_time_minute'     => '00',
		                'sc_event_time_am_pm'      => 'am',
		                'sc_event_end_date'        => '2017-01-15',
		                'sc_event_end_time_hour'   => '12',
		                'sc_event_end_time_minute' => '00',
		                'sc_event_end_time_am_pm'  => 'am',
		);
		$event_id3 = $this->factory->event->create_non_recurring( $args );

		$display_day   = '15';
		$display_month = '01';
		$display_year  = '2017';
		$category      = '';

		$events = sc_get_events_for_day( $display_day, $display_month, $display_year, $category );

		$this->assertTrue( is_array( $events ) );
		$this->assertEquals( 3, count( $events ) );
		$key = '0800' . $event_id1;
		$this->assertTrue(isset( $events[$key] ) );
		$key = '1000' . $event_id2;
		$this->assertTrue(isset( $events[$key] ) );
		$key = '1100' . $event_id3;
		$this->assertTrue(isset( $events[$key] ) );

	}

}

