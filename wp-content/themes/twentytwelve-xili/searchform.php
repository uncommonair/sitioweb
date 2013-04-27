<?php
/**
 * The template for displaying search forms in Twenty Twelve-xili 
 *
 * to add themedomain
 *
 * @package WordPress
 * @subpackage Twenty Twelve
 * @since Twenty Twelve-xili 1.1.1
 */
 
 $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:', 'twentytwelve') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'twentytwelve') .'" />
	</div>
	</form>';
	
if ( $echo )
	echo apply_filters('get_search_form', $form);
else
	return apply_filters('get_search_form', $form);	
 
?>