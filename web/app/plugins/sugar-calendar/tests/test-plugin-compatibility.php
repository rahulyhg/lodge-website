<?php
namespace SugarEventCalendar\Plugin_Compatibility;

use SugarEventCalendar\Tests\UnitTestCase;

class Tests extends UnitTestCase {

	function test_sc_3rd_party_post_type_supports() {

		ob_start();
		sc_3rd_party_post_type_supports();
		$output = ob_get_clean();

		// there should be no output
		$this->assertEquals( '', $output );

	}

}