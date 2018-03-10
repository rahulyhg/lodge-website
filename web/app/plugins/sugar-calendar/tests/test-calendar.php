<?php
namespace SugarEventCalendar\Sugar_Calendar;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	/**
	 * Test that sc_get_events_calendar outputs valid HTML
	 */
	function test_sc_get_events_calendar() {

		$size        = 'large';
		$category    = null;
		$type        = 'month';
		$today_year  = 2015;
		$_POST['sc_nonce'] = wp_create_nonce('sc_calendar_nonce');
		$_POST['sc_year']  = '2015';
		$_POST['sc_month'] = '02';

		ob_start();
		$calendar = sc_get_events_calendar( $size, $category, $type, $today_year );
		ob_end_clean();

		$doc = new \DOMDocument();
		$doc->loadhtml( $calendar );
		$head_element = $doc->getElementById('sc_events_calendar_head');

		$this->assertEquals( 'DOMElement', get_class( $head_element ) );

		$calendar_title = $doc->getElementById('sc_calendar_title');

		$this->assertEquals( 'DOMElement', get_class( $calendar_title ) );

		$this->assertEquals( 'February 2015', $calendar_title->nodeValue );

	}

	/**
	 * Function is deprecated
	 */
	function test_sc_calendar_next_prev() {


	}

	/**
	 * Verify that the Next/Prev buttons are created correctly.
	 */
	function test_sc_get_next_prev() {

		$size              = 'large';
		$category          = null;
		$type              = 'month';
		$_POST['sc_nonce'] = wp_create_nonce('sc_calendar_nonce');
		$_POST['sc_year']  = '2015';
		$_POST['sc_month'] = '02';

		$display_time = mktime( 0, 0, 0, 2, 1, 2015 );
		ob_start();
		sc_get_next_prev($display_time, $size, $category, $type);
		$next_prev = ob_get_clean();

		$doc = new \DOMDocument();
		$doc->loadhtml( $next_prev );
		$wrap = $doc->getElementById('sc_event_nav_wrap');

		$this->assertEquals( 'DOMElement', get_class( $wrap ) );

		$form = $doc->getElementById('sc_event_nav_prev');

		$this->assertEquals( 'DOMElement', get_class( $form ) );
	}

}