<?php
namespace SugarEventCalendar\Taxonomies;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_setup_event_taxonomies() {

		ob_start();
		sc_setup_event_taxonomies();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

}