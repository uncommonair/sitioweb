<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

function xili_dummy() {
	/*
	$a = esc_attr__( 'esc attr val','twentytwelve');
	//esc_attr_e
	esc_attr_e( 'esc attr echo','twentytwelve');
	//esc_html__
	$a = esc_html__( 'esc html val','twentytwelve');
	//esc_html_e
	esc_html__( 'esc html val echo','twentytwelve');
	
	//esc_attr_x
	$a = esc_attr_x( 'esc attr x val','twentytwelve');
	//esc_html_x
	$a = esc_html_x( 'esc html x val','twentytwelve');
	*/
	$arr = _n_noop( '_n_noop', '_n_noop_x', 'twentytwelve' );
	$arr = _nx_noop( '_nx_noop', '_n_noop_x', 'context_nx', 'twentytwelve' );
}

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'twentytwelve' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>

			<?php // translation
			$category_description = trim(strip_tags(category_description()));
			if ( ! empty( $category_description ) )
				echo '<div class="archive-meta"><p>' . __($category_description,'twentytwelve') . '</p></div>';
			?>
			</header><!-- .archive-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;

			twentytwelve_content_nav( 'nav-below' );
			?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>