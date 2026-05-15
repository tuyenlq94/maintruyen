<?php get_header(); ?>

<div class="container">

	<?php if ( have_posts() ) : ?>

		<div class="entries">
		<?php
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content' );
		}
		?>

		</div>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/none' ); ?>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
