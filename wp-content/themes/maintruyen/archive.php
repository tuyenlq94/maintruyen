<?php get_header(); ?>

	<?php
	if ( have_posts() ) :
		?>
	<div class="post">
			<?php
			while ( have_posts() ) :
				the_post();{

				// nội dung

				}
				?>
			<?php endwhile; ?>
	</div>
		<?php the_posts_pagination(); ?>

		<?php else : ?>
			<?php get_template_part( 'template-parts/content/none' ); ?>
		<?php endif; ?>

<?php get_footer(); ?>
