<?php
namespace SugarEventCalendar\Shortcodes;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_events_calendar_shortcode() {

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
		$this->factory->event->create_non_recurring( $args );

		$args = array( 'post_title'               => 'Early Event',
		               'sc_event_date'            => '2017-01-04',
		               'sc_event_time_hour'       => '08',
		               'sc_event_time_minute'     => '00',
		               'sc_event_time_am_pm'      => 'am',
		               'sc_event_end_date'        => '2017-01-04',
		               'sc_event_end_time_hour'   => '09',
		               'sc_event_end_time_minute' => '00',
		               'sc_event_end_time_am_pm'  => 'am',
		);

		// create an second event
		$this->factory->event->create_non_recurring( $args );

		$output = sc_events_calendar_shortcode( array() );

		$doc = new \DOMDocument();
		$doc->loadhtml( $output );

		$outer_div = $doc->getElementById('sc_calendar_wrap');
		$this->assertEquals( 'DOMElement', get_class( $outer_div ) );

		$finder = new \DOMXPath( $doc );
		$classname = "sc_events_calendar";
		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be one calendar
		$this->assertEquals( 1, $nodes->length );

	}

}