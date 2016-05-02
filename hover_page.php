<?php

/* Template Name: Hover Page
 * 
 */
get_header(); ?>

	<div id="primary" class="content-area">
		
		<div id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'template-parts/content', 'single' ); ?>
				<?php //get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>
			
			
			
		</div><!-- #main -->
	</div><!-- #primary -->
<?php dynamic_sidebar( 'hover_display' ); ?>

<?php get_footer(); ?>
