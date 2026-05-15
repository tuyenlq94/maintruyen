<?php get_header(); ?>

<article <?php post_class() ?> id="<?php the_ID() ?>">

	<?php the_title( '<h1>', '</h1>' ); ?>

</article>

<?php get_footer(); ?>
