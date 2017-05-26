<?php
add_shortcode('tahosa_button', 'tahosa_et_button');
function tahosa_et_button( $atts )
{
	extract( shortcode_atts( array (
		'link' => '#',
		'text' => 'Button Text',
	), $atts ) );
	return '<a class="et_pb_button et_pb_promo_button button_shortcode" href="'.$link.'">'.$text.'</a>';
}
