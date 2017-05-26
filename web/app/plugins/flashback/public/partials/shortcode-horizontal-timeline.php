<?php
/**
 * Contents of the [flashback-horizontal] shortcode.
 *
 * @package Flashback
 */

	$atts = shortcode_atts( array(
		'type' 		=> 'flb_timeline_item',
		'order' 	=> 'ASC',
		'orderby' 	=> 'meta_value',
		'posts' 	=> 25,
	), $atts);

	$options = array(
		'post_type'      => $atts['type'],
		'order'          => $atts['order'],
		'orderby'        => $atts['orderby'],
		'posts_per_page' => $atts['posts'],
		'meta_key'       => 'date',
	);

	$query = new WP_Query( $options );
?>

<section class="flb-horizontal-timeline">
	<div class="timeline">
		<div class="events-wrapper">
			<div class="events">
				<ol>
					<?php
					$i_nav = 0;
					while ( $query->have_posts() ) : $query->the_post();
						$date = strtotime( FlashbackField::get( 'date' ) );
						$date_format = FlashbackField::get( 'date_format' );
						if ( empty( $date_format ) ) {
							$date_format = 'F jS, Y';
						}

						if ( 0 === $i_nav ) {
							$class = 'selected';
						} else {
							$class = null;
						}
					?>
					<li><a href="#<?php echo esc_attr( $i_nav ); ?>" data-date="<?php echo esc_attr( date( 'd/m/Y', $date ) ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( date( $date_format, $date ) ); ?></a></li>
					<?php
						$i_nav++;
						endwhile;
					?>
				</ol>

				<span class="filling-line" aria-hidden="true"></span>
			</div> <!-- .events -->
		</div> <!-- .events-wrapper -->

		<ul class="flb-timeline-navigation">
			<li><a href="#0" class="prev inactive">Prev</a></li>
			<li><a href="#0" class="next">Next</a></li>
		</ul> <!-- .flb-timeline-navigation -->
	</div> <!-- .timeline -->

	<div class="events-content">
		<ol>
			<?php
			$i = 0;
			while ( $query->have_posts() ) : $query->the_post();
				$date = strtotime( FlashbackField::get( 'date' ) );
				$date_format = FlashbackField::get( 'date_format' );
				if ( empty( $date_format ) ) {
					$date_format = 'F jS, Y';
				}

				if ( 0 === $i ) {
					$class = 'selected';
				} else {
					$class = null;
				}
			?>
			<li class="<?php echo esc_attr( $class ); ?>" data-date="<?php echo esc_attr( date( 'd/m/Y', $date ) ); ?>">
				<h2><?php the_title(); ?></h2>
				<em><?php echo esc_html( date( $date_format, $date ) ); ?></em>
				<?php the_content(); ?>
			</li>
			<?php
				$i++;
				endwhile;
			?>
		</ol>
	</div> <!-- .events-content -->
</section>
