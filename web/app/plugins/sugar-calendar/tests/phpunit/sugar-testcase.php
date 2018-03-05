<?php
namespace SugarEventCalendar\Tests;

require_once dirname( __FILE__ ) . '/factory.php';

class UnitTestCase extends \WP_UnitTestCase {

	function __get( $name ) {
		if ( 'factory' === $name ) {
			return self::sugar();
		}
	}

	protected static function sugar() {
		static $factory = null;
		if ( ! $factory ) {
			$factory = new Factory();
		}

		return $factory;
	}

}