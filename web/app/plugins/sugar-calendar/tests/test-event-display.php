<?php
namespace SugarEventCalendar\Event_Display;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	/**
	 * Verify that the content isn't altered when no actions are registered
	 */
	function test_sc_event_content_hooks() {

		$test_content = "The Content";
		$content = sc_event_content_hooks( $test_content );

		$this->assertEquals( $test_content, $content );

	}

	/**
	 * Verify sc_add_event_details returns valid HTML
	 */
	function test_sc_add_event_details() {

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

		// get event details output
		ob_start();
		sc_add_event_details( $event_id );
		$details = ob_get_clean();


		$doc = new \DOMDocument();
		$doc->loadhtml( $details );
		$outer_div = $doc->getElementById('sc_event_details_' . $event_id);

		$this->assertEquals( 'DOMElement', get_class( $outer_div ) );

		// TODO: find a way to grab these elements by class, not id
		/*
		$date_field = $doc->getElementById('sc_event_date');
		$this->assertEquals( 'DOMElement', get_class( $date_field ) );
		$this->assertEquals( 'Date: January 1, 2017', $date_field->nodeValue );

		$time_field = $doc->getElementById('sc_event_start_time');
		$this->assertEquals( 'DOMElement', get_class( $time_field ) );
		$this->assertEquals( 'Time: 10:00 am', $time_field->nodeValue );
		*/

	}
}