<?php

/**
 * Before / After Event Content
 *
 * Adds an action to the start and end of event post content
 * that can be hooked to by other functions
 *
 * @access      private
 * @since       1.0
 * @param       $content string the the_content field of the post object
 * @return      $content string the content with any additional data attached
*/

function sc_event_content_hooks($content) {
	global $post;
	if( is_singular( 'sc_event' ) || is_post_type_archive( 'sc_event' ) || is_tax( 'sc_event_category' ) && is_main_query() ) {
		ob_start();
			do_action('sc_before_event_content', $post->ID);
			echo $content;
		 	do_action('sc_after_event_content', $post->ID);
		 $content = ob_get_clean();
	}
	return $content;
}
add_filter('the_content', 'sc_event_content_hooks');
add_filter('the_excerpt', 'sc_event_content_hooks');

function sc_add_event_details($event_id) {
	ob_start(); ?>

	<div class="sc_event_details" id="sc_event_details_<?php echo $event_id; ?>">
		<div class="sc_event_details_inner">
			<?php if( sc_is_recurring( $event_id ) ) { ?>
				<div class="sc_event_date"><?php sc_show_single_recurring_date( $event_id ); ?></div>
			<?php } else { ?>
				<div class="sc_event_date"><?php echo __('Date:', 'pippin_sc') . ' ' . sc_get_event_date( $event_id ); ?></div>
			<?php } ?>
			<?php if ( $start_time = sc_get_event_start_time( $event_id ) ) { ?>
			<div class="sc_event_time">
				<span class="sc_event_start_time"><?php echo __('Time:', 'pippin_sc') . ' ' . $start_time; ?></span>
				<?php if ( $end_time = sc_get_event_end_time($event_id) ) { ?>
				<span class="sc_event_time_sep">&nbsp;<?php _e('to', 'pippin_sc'); ?>&nbsp;</span>
				<span class="sc_event_end_time"><?php echo $end_time; ?></span>
				<?php } ?>
			</div><!--end .sc_event_time-->
			<?php } ?>
		</div><!--end .sc_event_details_inner-->
	</div><!--end .sc_event_details-->

	<?php
	echo ob_get_clean();
}
add_action('sc_before_event_content', 'sc_add_event_details');