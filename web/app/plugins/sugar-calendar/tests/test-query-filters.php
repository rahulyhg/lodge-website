<?php
namespace SugarEventCalendar\Query_Filters;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_modify_events_archive() {

		$query = new \WP_Query();

		ob_start();
		sc_modify_events_archive( $query );
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );


	}

}