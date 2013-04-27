<?php
/**
 * The sidebar containing the front page widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * The front page widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
$options = xili_twentytwelve_get_theme_options(); 
$curlang = ( the_curlang() == 'en_us' || the_curlang() == "" ) ? '' : '_'.the_curlang()  ;

$curlang2 = ( $curlang != '' && !isset( $options['sidebar_'.'sidebar-2'] ) ) ? '' : $curlang ; //display default  - no clone
$curlang3 = ( $curlang != '' && !isset( $options['sidebar_'.'sidebar-3'] ) ) ? '' : $curlang ; //display default  - no clone 

if ( ! is_active_sidebar( 'sidebar-2'. $curlang2 ) && ! is_active_sidebar( 'sidebar-3'. $curlang3 ) )
	return;
// If we get this far, we have widgets. Let do this.
?>
<div id="secondary" class="widget-area" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-2'. $curlang2 ) ) : ?>
	<div class="first front-widgets">
		<?php dynamic_sidebar( 'sidebar-2'. $curlang2 ); ?>
	</div><!-- .first -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-3'. $curlang3 ) ) : ?>
	<div class="second front-widgets">
		<?php dynamic_sidebar( 'sidebar-3'. $curlang3 ); ?>
	</div><!-- .second -->
	<?php endif; ?>
</div><!-- #secondary -->