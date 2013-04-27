<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package Cryout Creations
 * @subpackage mantra
 * @since mantra 0.5
 */

/* This  retrieves  admin options. */
$mantra_options= mantra_get_theme_options();
foreach ($mantra_options as $key => $value) {
     ${"$key"} = esc_attr($value) ;
}

if (is_page_template() && !is_page_template('template-blog.php') && !is_page_template('template-onecolumn.php') && !is_page_template('template-page-with-intro.php') ) {
?>
	<div id="primary" class="widget-area" role="complementary">
	
	<?php cryout_before_primary_widgets_hook(); ?>
	
			<ul class="xoxo">
				<?php dynamic_sidebar( 'primary-widget-area' ); ?>
			</ul>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
			
		<?php cryout_after_primary_widgets_hook(); ?>
		
		</div><!-- #primary .widget-area -->
		
		<?php 
		if (is_page_template("template-threecolumns-right.php") || is_page_template("template-threecolumns-left.php") || is_page_template("template-threecolumns-center.php")) { ?>

		<div id="secondary" class="widget-area" role="complementary" >
		
		<?php cryout_before_secondary_widgets_hook(); ?>
		
			<ul class="xoxo">
				<?php dynamic_sidebar( 'third-widget-area' ); ?>
			</ul>
			
			<ul class="xoxo">
				<?php dynamic_sidebar( 'fourth-widget-area' ); ?>
			</ul>
			
		<?php cryout_after_secondary_widgets_hook(); ?>
		
		</div><!-- #secondary .widget-area -->

		<?php } // second sidebar
} // if page_template

else 
if ($mantra_side != "1c") { ?>
		<div id="primary" class="widget-area" role="complementary">
		
		<?php cryout_before_primary_widgets_hook(); ?>
		
			<ul class="xoxo">
				<?php dynamic_sidebar( 'primary-widget-area' ) ; ?>
			</ul>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>
			
			<?php cryout_after_primary_widgets_hook(); ?>
			
		</div><!-- #primary .widget-area -->

<?php
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'third-widget-area' ) || is_active_sidebar( 'fourth-widget-area' )) {
	if ( $mantra_side != "2cSr" &&  $mantra_side != "2cSl") { ?>
	
		<div id="secondary" class="widget-area" role="complementary" >
		
		<?php cryout_before_secondary_widgets_hook(); ?>
		
			<ul class="xoxo">
				<?php dynamic_sidebar( 'third-widget-area' ); ?>
			</ul>
			
			<ul class="xoxo">
				<?php dynamic_sidebar( 'fourth-widget-area' ); ?>
			</ul>
			
		<?php cryout_after_secondary_widgets_hook(); ?>	
		
		</div><!-- #secondary .widget-area -->
	<?php }}
 }?> <!-- 1c -->