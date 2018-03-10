<?php
namespace SugarEventCalendar\Scripts;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_load_admin_scripts() {

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
		sc_load_admin_scripts();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

	function test_sc_load_front_end_scripts() {

		ob_start();
		sc_load_front_end_scripts();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

	function test_sc_enqueue_scripts() {

		ob_start();
		sc_enqueue_scripts();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

	function test_sc_enqueue_styles() {


		ob_start();
		sc_enqueue_styles();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

}