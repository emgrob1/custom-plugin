<?php
/*
 * Template Name: Display Page
 */
?>
<div class="entry-content" style="overflow-y: scroll; ">
	<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			

		<?php endwhile; // End of the loop. ?>
		</div>