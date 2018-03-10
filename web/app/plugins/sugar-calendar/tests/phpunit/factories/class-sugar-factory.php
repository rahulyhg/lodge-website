<?php
namespace SugarEventCalendar\Tests;

/**
 * A factory for making WordPress data with a cross-object type API.
 *
 * Tests should use this factory to generate test fixtures.
 */
class Factory extends \WP_UnitTest_Factory {

	/**
	 * @var \SugarEventCalendar\Tests\Factory\Event
	 */
	public $event;

	function __construct() {
		parent::__construct();

		$this->event = new Factory\Event( $this );
	}
}
