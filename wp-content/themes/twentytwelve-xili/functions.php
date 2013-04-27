<?php 
// twentytwelve - modifications for twentytwelve-xili
// dev.xiligroup.com - msc - 2012-09-02
// dev.xiligroup.com - msc - 2013-01-10 - options to clone sidebar container
// dev.xiligroup.com - msc - 2013-01-31 - improves search form and filter
// dev.xiligroup.com - msc - 2013-02-06 - improves sidebar cloning
// dev.xiligroup.com - msc - 2013-02-23 - don't clone if option (temporaly) disabled
// dev.xiligroup.com - msc - 2013-03-03 - fixes


define( 'TWENTYTWELVE_XILI_VER', '1.1.3'); // as style.css

function twentytwelve_xilidev_setup () {
	
	load_theme_textdomain( 'twentytwelve', STYLESHEETPATH . '/langs' ); // now use .mo of child
	
	$xili_functionsfolder = get_stylesheet_directory() . '/functions-xili' ; 
	if ( file_exists( $xili_functionsfolder . '/multilingual-functions.php') && class_exists('xili_language') ) {
		require_once ( $xili_functionsfolder . '/multilingual-functions.php' );
	}
	if ( file_exists( $xili_functionsfolder . '/multilingual-permalinks.php') && class_exists('xili_language') ) {
		require_once ( $xili_functionsfolder . '/multilingual-permalinks.php' );
	}
	
}

add_action( 'after_setup_theme', 'twentytwelve_xilidev_setup', 11 );
add_action ( 'wp_head', 'special_head', 11);


/**
 * define when search form is completed by radio buttons to sub-select language when searching
 *
 */
function special_head() {
	
	echo "<!-- Website powered by child-theme twentytwelve-xili v. ".TWENTYTWELVE_XILI_VER." of dev.xiligroup.com -->\n";
	echo '<link rel="shortcut icon" href="' . get_stylesheet_directory_uri() . '/images/favicon.ico" type="image/x-icon"/>'."\n";
	echo '<link rel="apple-touch-icon" href="' . get_stylesheet_directory_uri() . 'images/apple-touch-icon.png"/>'."\n";
	
	// to change search form of widget
	// if ( is_front_page() || is_category() || is_search() )
	if ( is_search() ) {
	 	add_filter('get_search_form', 'my_langs_in_search_form_2012', 10, 1); // in multilingual-functions.php
	}
}


/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
	if ( is_singular() ) {
	echo '&nbsp;-&nbsp;'; global $post;
	if ( xiliml_new_list() ) xiliml_the_other_posts($post->ID,"Read this post in");
	}
}


/**
 * create if option clone of sidebar container by language
 *
 *
 */
function xili_clone_sidebar_container () {
	global $wp_registered_sidebars;
	
	$xili_theme_options = xili_twentytwelve_get_theme_options() ; // 1.1.2 
	
	if ( class_exists ('xili_language' ) ) {
	
		$language_xili_settings = get_option('xili_language_settings');
		$language_slugs_list =  array_keys ( $language_xili_settings['langs_ids_array'] ) ;
		
		foreach ( $language_slugs_list as $slug) {
			
			if ( $slug != 'en_us'  ) {
	
				$language = get_term_by( 'slug', $slug, TAXONAME ); //$language = xiliml_get_language( $slug );
				
				foreach ( $wp_registered_sidebars as $one_key => $one_sidebar ) { 
					$indice = 'sidebar_'.$one_key ;
					if ( false === strpos( $one_key , '_' ) && isset ( $xili_theme_options[$indice] ) ) {	// don't use _ in root sidebar id 	
						register_sidebar( array(
							'name' => sprintf ( __('%1$s in %2$s', 'twentytwelve'),  $one_sidebar['name'],  $language->description ),
							'id' => $one_sidebar['id'].'_'.$slug,
							'description' => $one_sidebar['description'],
							'before_widget' => $one_sidebar['before_widget'],
							'after_widget' => $one_sidebar['after_widget'],
							'before_title' => $one_sidebar['before_title'],
							'after_title' => $one_sidebar['after_title'],
						) );
					}	
				}
			}
		}
	}
}

add_action( 'init', 'xili_clone_sidebar_container', 101);

add_action ( 'init', 'xili_create_menu_locations', 100 );

/**
 * filter to create one menu per language for dashboard
 * detect the default one created by theme ($menu_locations_keys[0])
 * @since 0.9.7
 * @updated 1.0.2
 */


function xili_create_menu_locations () {
	$xili_theme_options = xili_twentytwelve_get_theme_options() ; 
	if ( isset ( $xili_theme_options['nav_menus'] ) && $xili_theme_options['nav_menus'] == 'nav_menus' ) {  // ok for automatic insertion of one menu per lang...
		$menu_locations = get_registered_nav_menus() ; 
		$menu_locations_keys =  array_keys( $menu_locations );
		if ( class_exists('xili_language') ) {
			global $xili_language ;
			$default = 'en_us'; // currently the default language of theme in core WP
			$language_xili_settings = get_option('xili_language_settings');
			$language_slugs_list =  array_keys ( $language_xili_settings['langs_ids_array'] ) ;
			$oneloc = $menu_locations_keys[0]; // primary
			foreach ( $language_slugs_list as $slug ) {
				$one_menu_location = $oneloc.'_'.$slug ; 
				if ( $slug != $default ) {
					register_nav_menu ( $one_menu_location,  sprintf( __('%s for %s','twentytwelve'), $menu_locations[$oneloc], $slug ) );
				}
			}
		} 
	}
}


/**
 * filter to avoid modifying theme's header and changes 'virtually' location for each language
 * @since 0.9.7
 */

add_filter ( 'wp_nav_menu_args', 'xili_wp_nav_menu_args' ); // called at line #145 in nav-menu-template.php

function xili_wp_nav_menu_args ( $args ) {
	if ( class_exists('xili_language') ) {
		
		// to avoid switch when temporaly disable
		$xili_theme_options = xili_twentytwelve_get_theme_options() ; 
		$ok =  ( isset ( $xili_theme_options['nav_menus'] ) && $xili_theme_options['nav_menus'] == 'nav_menus' ) ? true : false ;
		
		global $xili_language ;
		$default = 'en_us'; // currently the default language of theme as in core WP
		$slug = the_curlang();
		if ( $default != $slug  && $ok ) { 
			$theme_location = $args['theme_location'];
			if ( has_nav_menu ( $theme_location.'_'.$slug ) ) { // only if a menu is set by webmaster in menus dashboard
				$args['theme_location'] = $theme_location .'_'.$slug ;
			}	
		}
	}	
	return $args;
}


/**
 * dynamic style for flag depending current list 
 *
 * @since 1.0.2 - add #access
 *
 */
function twentytwelve_flags_style () {
	if ( class_exists('xili_language') ) {
		global $xili_language ;
		$language_xili_settings = get_option('xili_language_settings'); 
		if ( !is_array( $language_xili_settings['langs_ids_array'] ) ) { 
			$xili_language->get_lang_slug_ids(); // update array when no lang_perma 110830 thanks to Pierre
			update_option( 'xili_language_settings', $xili_language->xili_settings );
			$language_xili_settings = get_option('xili_language_settings');
		}
	
		$language_slugs_list =  array_keys ( $language_xili_settings['langs_ids_array'] ) ;
		$xili_theme_options = xili_twentytwelve_get_theme_options() ; // see below
		
		if ( !isset( $xili_theme_options['no_flags'] ) || $xili_theme_options['no_flags'] != 'hidden_flags' ) {
		?>
		<style type="text/css">
		<?php 
		
		$path = get_stylesheet_directory_uri();
		
		$ulmenus = array();
		foreach ( $language_slugs_list as $slug ) {
			echo "ul.nav-menu li.menu-separator { margin:0; }\n";
			echo "ul.nav-menu li.lang-{$slug} { background: transparent url('{$path}/images/flags/{$slug}.png') no-repeat center 16px; margin:0;}\n";
			echo "ul.nav-menu li.lang-{$slug}:hover {background:  transparent url('{$path}/images/flags/{$slug}.png') no-repeat center 17px !important;}\n";
			$ulmenus[] = "ul.nav-menu li.lang-{$slug} a";
		} 
			echo implode (', ', $ulmenus ) . " {text-indent:-9999px; width:24px;  }\n";
		?>
		</style>
		<?php
		}
	}
}

add_action( 'wp_head', 'twentytwelve_flags_style' );


/**
 *
 *
 */
function single_lang_dir($post_id) {
	$langdir = ((function_exists('get_cur_post_lang_dir')) ? get_cur_post_lang_dir($post_id) : array());
	if ( isset($langdir['direction']) ) return $langdir['direction'];
} 

function twentytwelve_xili_credits () {
	printf( __("Multilingual child theme of twentytwelve by %s", 'twentytwelve' ),"<a href=\"http://dev.xiligroup.com\">dev.xiligroup</a> - " );
}

add_action ('twentytwelve_credits','twentytwelve_xili_credits');

/**
 * to avoid display of old xiliml_the_other_posts in singular
 * @since 1.1
 */
function xiliml_new_list() {
	if ( class_exists('xili_language') ) {
		global $xili_language;
	
		$xili_theme_options = xili_twentytwelve_get_theme_options() ; // see below
		
		if ( $xili_theme_options['linked_posts'] == 'show_linked' ) {
			if (is_page() && is_front_page() ) {
				return false;
			} else {	
				return true;
			}
		}	

		if ( is_active_widget ( false, false, 'xili_language_widgets' ) ) {
			
			$xili_widgets = get_option('widget_xili_language_widgets', array());
			foreach ( $xili_widgets as $key => $arrprop ) {
				if ( $key != '_multiwidget' ) {
					if ( $arrprop['theoption'] == 'typeonenew' ) {  // widget with option for singular
						if ( is_active_widget( false, 'xili_language_widgets-'.$key, 'xili_language_widgets' ) ) return false ;
					}
				}
			}
		}
		
		if ( XILILANGUAGE_VER > '2.0.0' && isset($xili_language -> xili_settings['navmenu_check_options']) && in_array ('navmenu-1', $xili_language -> xili_settings['navmenu_check_options']['primary']) ) return false ;
		
	}
	return true ;
	
}

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Twenty Twelve 1.0
 */
function xili_twentytwelve_theme_options_add_page() {
	global $xili_theme_page;
	$xili_theme_page = add_theme_page(
		__( 'xili Options', 'twentytwelve' ), // Name of page
		__( 'xili Options', 'twentytwelve' ), // Label in menu
		'edit_theme_options',                  // Capability required
		'xili_theme_options',                       // Menu slug, used to uniquely identify the page
		'xili_theme_options_render_page'            // Function that renders the options page
	);
	add_action('load-'.$xili_theme_page, 'xili_twentytwelve_theme_options_help_page');
}

add_action( 'admin_menu', 'xili_twentytwelve_theme_options_add_page' );


function xili_twentytwelve_theme_options_help_page () {
	global $xili_theme_page;
	$screen = get_current_screen();
	
	if ( $screen->id != $xili_theme_page )
        return;
	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Twenty Twelve, provides the following Theme Options:', 'twentytwelve' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Multilingual Flags style</strong>: Check if you want to hidden flags and see only language names. (no style generated)...', 'twentytwelve' ) . '</li>' .
				'<li>' . __( '<strong>Other posts in other languages links in singular (page or post)</strong>: Check if you want to show links of posts in other languages.', 'twentytwelve' ) . '</li>' .
				'<li>' . __( '<strong>Instancing nav menu for each language</strong>: Check if you want to clone menu location.', 'twentytwelve' ) . '</li>' .
				'<li>' . __( '<strong>Enable instantiation for the registered sidebars</strong>: Check if you want to clone one the sidebars for each language.', 'twentytwelve' ) . '</li>' .
				
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'twentytwelve' ) . '</p>' .
			'<p><strong>' . __( 'For more information:', 'twentytwelve' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">WP Documentation on Theme Options</a>', 'twentytwelve' ) . '</p>' .
			'<p>' . __( '<a href="http://wiki.xiligroup.org" target="_blank">Xili Wiki</a>', 'twentytwelve' ) . '</p>'.
			'<p>' . __( '<a href="http://dev.xiligroup.com/?post_type=forum" target="_blank">Xili Support Forums</a>', 'twentytwelve' ) . '</p>';

	$screen->add_help_tab(  array(
        'id'	=> $xili_theme_page,
        'title'	=> __('Help'),
        'content'	=>	$help	));
}


/**
 * Returns the options array for Twenty Twelve.
 *
 * @since Twenty Twelve 1.0
 *
 * 
 *
 */
function xili_theme_options_render_page() {
	global $wp_registered_sidebars;
	?>
	<div class="wrap">
		<?php screen_icon(); 
		$theme = ( function_exists('wp_get_theme')) ? wp_get_theme() : get_current_screen(); // WP 3.4
		?>
		<h2><?php printf( __( '%s Theme Options for multilingual features', 'twentytwelve' ), $theme ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'xili_twentytwelve_options' ); // as in register_settings below
				$options = xili_twentytwelve_get_theme_options();
				$default_options = xili_twentytwelve_get_default_theme_options();
				$no_flags = isset( $options['no_flags'] ) ? $options['no_flags'] : "";
				$linked_posts = isset( $options['linked_posts'] ) ? $options['linked_posts'] : "";
				$nav_menus = isset( $options['nav_menus'] ) ? $options['nav_menus'] : "";
				$menu_locations = get_registered_nav_menus() ;
				$navmenu_count =  0 ;
				foreach ( $menu_locations as $one_key => $one_location ) { 
						if ( false === strpos( $one_key , '_' ) ) $navmenu_count ++ ; // only core nav menu
				}
				
				
			?>
			<table class="form-table">

				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Style of languages in menu', 'twentytwelve' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Flags hidden', 'twentytwelve' ); ?></span></legend>
							<label for="xili_twentytwelve_theme_options[no_flags]" class="selectit"><input name="xili_twentytwelve_theme_options[no_flags]" <?php checked( $no_flags, "hidden_flags" ); ?> type="checkbox" value="hidden_flags" /> <?php _e( 'Flags hidden', 'twentytwelve' ); ?></label>
						</fielset>
					</td>
				</tr>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Show Other Posts links in meta in single', 'twentytwelve' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show linked posts', 'twentytwelve' ); ?></span></legend>
							<label for="xili_twentytwelve_theme_options[linked_posts]" class="selectit"><input name="xili_twentytwelve_theme_options[linked_posts]" <?php checked( $linked_posts, "show_linked" ); ?> type="checkbox" value="show_linked" /> <?php _e( 'Show', 'twentytwelve' ); ?></label>
						</fielset>
					</td>
				</tr>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Instantiation of nav menu for each language', 'twentytwelve' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Instancing nav menus', 'twentytwelve' ); ?></span></legend>
							<label for="xili_twentytwelve_theme_options[nav_menus]" class="selectit"><input name="xili_twentytwelve_theme_options[nav_menus]" <?php checked( $nav_menus, "nav_menus" ); ?> type="checkbox" value="nav_menus" /> <?php _e( 'Instancing', 'twentytwelve' ); ?></label>
						</fielset>
					</td>
				</tr>
				<?php 
				
				if ( $navmenu_count > 1 )  {
				?>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php printf (__( 'Enable (or not) language instantiation for these registered menus<br /> After changes saved, <a href="%s" >go to Nav Menus</a> and save theme locations', 'twentytwelve' ) , 'nav-menus.php'); ?></th>
					<td>
					<?php foreach ( $menu_locations as $one_key => $one_location ) { 
						if ( false === strpos( $one_key , '_' ) ) {
							$indice = 'nav_menu_'.$one_key ;
							$nav_value = isset( $options[$indice] ) ? $options[$indice] : "";
						?>
						<fieldset>
							<label for="xili_twentytwelve_theme_options[<?php echo $indice ?>]" class="selectit"><input name="xili_twentytwelve_theme_options[<?php echo $indice ?>]" <?php checked( $nav_value, "nav_menu" ); ?> type="checkbox" value="nav_menu" /><?php echo $one_location ; ?></label>
						</fielset>
					<?php } } ?>	
					</td>
				</tr>
				
				<?php } ?>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php printf (__( 'Enable (or not) instantiation of the registered sidebars.<br /> After changes saved, <a href="%s" >go to Widget Menus</a> and fill sidebar for each language.', 'twentytwelve' ) , 'widgets.php'); ?></th>
					<td>
					
					<?php foreach ( $wp_registered_sidebars as $one_key => $one_sidebar ) { //error_log( serialize ( $one_sidebar ));
						if ( false === strpos( $one_key , '_' ) ) {
							$indice = 'sidebar_'.$one_key ;
							$sidebar_value = isset( $options[$indice] ) ? $options[$indice] : "";
						?>
						<fieldset>
							<label for="xili_twentytwelve_theme_options[<?php echo $indice ?>]" class="selectit"><input name="xili_twentytwelve_theme_options[<?php echo $indice ?>]" <?php checked( $sidebar_value, "sidebar_clone" ); ?> type="checkbox" value="sidebar_clone" /> <?php echo $one_sidebar['name'] ; ?></label>
						</fielset>
					<?php } } ?>
					
					</td>
				</tr>	
			</table>
			<p><small>twentytwelve-xili v. <?php echo TWENTYTWELVE_XILI_VER; ?> , a multilingual child example by <a href="http://2012.wpmu.xilione.com" target="_blank" >dev.xiligroup.com</a> (©2012-13)</small></p>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

/**
 * Returns the options array for Twenty Twelve.
 *
 * @since Twenty Twelve 1.0
 */
function xili_twentytwelve_get_theme_options() {
	return get_option( 'xili_twentytwelve_theme_options', xili_twentytwelve_get_default_theme_options() );
}

function xili_twentytwelve_get_default_theme_options() {
	return array( 'no_flags' => false , 'linked_posts' => 'show_linked',  'nav_menus' => false) ;
}

function xili_twentytwelve_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === xili_twentytwelve_get_theme_options() )
		add_option( 'xili_twentytwelve_theme_options', xili_twentytwelve_get_default_theme_options() );

	register_setting(
		
		'xili_twentytwelve_options', // prefix xili_ to avoid conflict with parent - thanks to sylvia - Database option, see twentytwelve_get_theme_options()
		'xili_twentytwelve_theme_options'
	);
}
add_action( 'admin_init', 'xili_twentytwelve_theme_options_init' );

?>