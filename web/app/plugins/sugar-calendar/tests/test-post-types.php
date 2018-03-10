<?php
namespace SugarEventCalendar\Post_Types;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_setup_post_types() {

		ob_start();
		sc_setup_post_types();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

}