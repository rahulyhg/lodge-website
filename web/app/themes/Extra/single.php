<?php get_header(); ?>
<div id="main-content">
	<?php
		// load fullwidth page in Product Tour mode
		$product_tour_status = 'off';

		if ( et_fb_is_enabled() ) {
			$current_user = wp_get_current_user();
			$product_tour_settings = et_get_option( 'product_tour_status', array() );
			$product_tour_status = 'on' === et_get_option( 'et_pb_product_tour_global', 'on' ) && ( ! isset( $product_tour_settings[$current_user->ID] ) || 'off' !== $product_tour_settings[$current_user->ID] ) ? 'on' : 'off';
		}

		if ( 'on' === $product_tour_status ) :

			while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
					<?php
						the_content();
					?>
					</div> <!-- .entry-content -->

				</article> <!-- .et_pb_post -->

		<?php endwhile;
		else :
	?>
	<div class="container">
		<div id="content-area" class="<?php extra_sidebar_class(); ?> clearfix">
			<div class="et_pb_extra_column_main">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?>>
					<div class="post-wrap">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="post-content entry-content">
							<?php the_content(); ?>
							<?php
								wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extra' ),
									'after'  => '</div>',
								) );
							?>
						</div>
					</div><!-- /.post-wrap -->
				</article>
				<?php
					endwhile;
				else :
					?>
					<h2><?php esc_html_e( 'Post not found', 'extra' ); ?></h2>
					<?php
				endif;
				?>
			</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->

			<?php get_sidebar(); ?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->
	<?php endif; ?>
</div> <!-- #main-content -->

<?php get_footer();
