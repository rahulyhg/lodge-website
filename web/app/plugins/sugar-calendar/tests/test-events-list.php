<?php
namespace SugarEventCalendar\Events_List;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_get_events_list() {

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


		// There should not be any upcoming events
		$list = sc_get_events_list( 'upcoming' );
		$this->assertEquals( '', $list );

		$show = array(
			'date'       => 1,
			'time'       => 1,
			'categories' => 1,
		);
		// Get the two past events created above
		$list = sc_get_events_list( 'past', null, 5, $show );

		$doc = new \DOMDocument();
		$doc->loadhtml( $list );
		$finder = new \DOMXPath( $doc );

		$classname = "sc_event";

		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

		// there should be two events in the list
		$this->assertEquals( 2, $nodes->length );

	}

}