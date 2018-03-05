<?php
namespace SugarEventCalendar\Install;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_install() {

		ob_start();
		sc_install();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

}