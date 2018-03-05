<?php
namespace SugarEventCalendar\Feed;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_add_fields_to_rss() {

		// create an event
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

		// set created event as global post for rss feed
		global $post;
		$post = get_post( $event_id );

		ob_start();
		sc_add_fields_to_rss();
		$output = ob_get_clean();

		$control = "<event_day>01</event_day>
<event_month>01</event_month>
<event_year>2017</event_year>
<event_time>2017-01-01</event_time>
<event_end_day>1</event_end_day>
<event_end_month>01</event_end_month>
<event_end_year>2017</event_end_year>
<event_end_time>2017-01-01</event_end_time>
";

		$this->assertEquals( $control, $output );

	}

}