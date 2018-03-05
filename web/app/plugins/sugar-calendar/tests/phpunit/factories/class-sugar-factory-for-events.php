<?php
namespace SugarEventCalendar\Tests\Factory;

class Event extends \WP_UnitTest_Factory_For_Thing {

	function __construct( $factory = null ) {
		parent::__construct( $factory );
	}

	/**
	 * Create a non-recurring event
	 *
	 * @param $args
	 * @return int $post_id ID of created post
	 */
	function create_non_recurring( $args ) {

		// Remove hooked action because we don't want sc_meta_box_save()
		// to fire when the factory saves an event
		remove_action('save_post', 'sc_meta_box_save');


		// add post
		$post_id = wp_insert_post( array(
			'post_title'  => $args['post_title'],
			'post_type'   => 'sc_event',
			'post_status' => 'publish',
		) );

		// add calendar meta
		$date  = $args['sc_event_date'];
		$day   = date( 'd', strtotime( $date ) );
		$month = date( 'm', strtotime( $date ) );
		$year  = date( 'Y', strtotime( $date ) );

		update_post_meta( $post_id, 'sc_event_date', strtotime( $date ) );
		update_post_meta( $post_id, 'sc_event_day', date( 'D', strtotime( $date ) ) );
		update_post_meta( $post_id, 'sc_event_day_of_week', date( 'w', strtotime( $date ) ) );
		update_post_meta( $post_id, 'sc_event_day_of_month', $day );
		update_post_meta( $post_id, 'sc_event_day_of_year', date( 'z', strtotime( $date ) ) );
		update_post_meta( $post_id, 'sc_event_month', $month );
		update_post_meta( $post_id, 'sc_event_year', $year );

		$hour    = $args['sc_event_time_hour'];
		$minutes = $args['sc_event_time_minute'];
		$am_pm   = $args['sc_event_time_am_pm'];

		if ( $am_pm == 'pm' && $hour < 12 ) {
			$hour += 12;
		} elseif ( $am_pm == 'am' && $hour >= 12 ) {
			$hour -= 12;
		}

		update_post_meta( $post_id, 'sc_event_time_hour', $hour );
		update_post_meta( $post_id, 'sc_event_time_minute', $minutes );
		update_post_meta( $post_id, 'sc_event_time_am_pm', $am_pm );

		$final_date_time = mktime( intval( $hour ), intval( $minutes ), 0, $month, $day, $year );
		update_post_meta( $post_id, 'sc_event_date_time', $final_date_time );

		// set end date
		$sc_event_end_date = $args['sc_event_date'];

		$end_date  = $sc_event_end_date;
		$end_day   = date( 'j', strtotime( $end_date ) );
		$end_month = date( 'm', strtotime( $end_date ) );
		$end_year  = date( 'Y', strtotime( $end_date ) );

		update_post_meta( $post_id, 'sc_event_end_date', strtotime( $end_date ) );
		update_post_meta( $post_id, 'sc_event_end_day', date( 'D', strtotime( $end_date ) ) );
		update_post_meta( $post_id, 'sc_event_end_day_of_week', date( 'w', strtotime( $end_date ) ) );
		update_post_meta( $post_id, 'sc_event_end_day_of_month', $end_day );
		update_post_meta( $post_id, 'sc_event_end_day_of_year', date( 'z', strtotime( $end_date ) ) );
		update_post_meta( $post_id, 'sc_event_end_month', $end_month );
		update_post_meta( $post_id, 'sc_event_end_year', $end_year );

		$end_hour    = $args['sc_event_end_time_hour'];
		$end_minutes = $args['sc_event_end_time_minute'];
		$end_am_pm   = $args['sc_event_end_time_am_pm'];

		if ( $end_am_pm == 'pm' && $end_hour < 12 ) {
			$end_hour += 12;
		} elseif ( $end_am_pm == 'am' && $end_hour >= 12 ) {
			$end_hour -= 12;
		}

		update_post_meta( $post_id, 'sc_event_end_time_hour', $end_hour );
		update_post_meta( $post_id, 'sc_event_end_time_minute', $end_minutes );
		update_post_meta( $post_id, 'sc_event_end_time_am_pm', $end_am_pm );

		$final_end_date_time = mktime( intval( $end_hour ), intval( $end_minutes ), 0, $end_month, $end_day, $end_year );
		update_post_meta( $post_id, 'sc_event_end_date_time', $final_end_date_time );

		return $post_id;
	}

	/**
	 * Create recurring event
	 *
	 * @param $args
	 * @return int $post_id ID of created post
	 */
	function create_recurring( $args ) {

		$post_id = $this->create_non_recurring( $args );

		$recurring   = $args['sc_event_recurring'];
		$recur_until = $args['sc_recur_until'];
		update_post_meta($post_id, 'sc_event_recurring', $recurring);
		update_post_meta($post_id, 'sc_recur_until', strtotime( $recur_until ) );

		$recurrences = sc_calculate_recurring( $post_id );
		update_post_meta($post_id, 'sc_all_recurring', $recurrences);

		return $post_id;
	}

	function create_object( $args ) {
	}

	/**
	 * Stub out update for IDE type hinting purposes.
	 *
	 * @param int $event_id
	 * @param array $args
	 *
	 * @return bool
	 */
	function update_object( $event_id, $args ){

		return true;
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param int $event_id
	 *
	 * @return \WP_Post|false
	 */
	function get_object_by_id( $event_id ) {
		return get_post( $event_id);
	}

}