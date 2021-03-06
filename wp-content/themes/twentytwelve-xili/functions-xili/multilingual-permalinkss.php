<?php

function xili_trans_slug_qv ( $lang_slug) {
	global $xili_language;
	//error_log ('++++>>>' . $lang_slug );
	
	if ( isset ( $_POST['language_alias'] ) ) $xili_language->xili_settings = get_option('xili_language_settings'); // need update !
	
	$short = ( isset ( $xili_language->xili_settings['lang_features'][$lang_slug]['alias'] ) ) ?  $xili_language->xili_settings['lang_features'][$lang_slug]['alias'] : $lang_slug ;
	
	return $short;
	/*
	if ( isset( $xili_language->langs_slug_shortqv_array[$lang_slug]) && $xili_language->langs_slug_shortqv_array[$lang_slug] != '' ) { // no empty
		
		error_log ('++++>>>----' . $xili_language->langs_slug_shortqv_array[$lang_slug] );
		return $xili_language->langs_slug_shortqv_array[$lang_slug];
	} else {
		return $lang_slug;
	}
	*/
} 
add_filter ( 'alias_rule', 'xili_trans_slug_qv' ) ;

/**
 * ***** new lang permalinks by dev.xiligroup.com  ******
 * @since 20120929
 *
 * only for tests
 */ 
function my_redirect_canonical ( $redirect_url, $requested_url ) {
	//error_log ( '$redirect_url = '.$redirect_url.', $requested_url ='. $requested_url ) ;
	if ( is_front_page() ) {
		//error_log ( '---- no redirect ----' );
		return $requested_url; // to avoid relaunch when page as front... and a second query
	} else {
		return $redirect_url;
	}
}


add_filter ( 'redirect_canonical', 'my_redirect_canonical', 10, 2 );
add_filter ( 'init', 'xl_rules',1 );
add_filter ( 'term_link', 'insert_lang_4cat', 10, 3 );

add_filter ( 'pre_post_link', 'insert_lang_tag_4post', 10, 3 );
add_filter ( 'post_link', 'insert_lang_4post', 10, 3 );

add_filter ( 'home_url', 'insert_lang_tag_4page', 10, 4 );
add_filter ( '_get_page_link', 'insert_lang_4page', 10, 2 );


// xili-language is not yet activated... so need to rewrite flush after install

function xl_rules () {
	$language_xili_settings = get_option('xili_language_settings'); 
	if ( isset( $language_xili_settings['langs_ids_array'] ) ) { // 2.8.1 - to wait first install
	
		
		if ( version_compare( XILILANGUAGE_VER, '2.8.2', '>=') ){ // new query var slug (alias)
			if (isset ( $language_xili_settings['shortqv_slug_array'] )) $language_qvs = array_keys ( $language_xili_settings['shortqv_slug_array'] );
			
			$language_qvs_all = $language_qvs;
			$language_qvs_all[] = $language_xili_settings['lang_undefined']; //2.2.3 for undefined
			
			foreach ( $language_qvs as $slug ) {
				$language_qvs_all[] = $slug . $language_xili_settings['lang_undefined']; //2.2.3 for undefined (§) + lang 
			}
			
		} else {
			
			$language_slugs = array_keys ( $language_xili_settings['langs_ids_array'] );
			$language_slugs_all = $language_slugs ;
			$language_slugs_all[] = $language_xili_settings['lang_undefined']; //2.2.3 for undefined
		
			foreach ( $language_slugs as $slug ) {
				$language_slugs_all[] = $slug . $language_xili_settings['lang_undefined']; //2.2.3 for undefined (§) + lang 
			}
		}
		
		if ( version_compare( XILILANGUAGE_VER, '2.8.2', '>=') ){
			if (isset ( $language_xili_settings['shortqv_slug_array'] )) $language_slugs_list = implode ("|", $language_qvs_all ); 
		
		} else {
				
			$language_slugs_list =  implode ("|", $language_slugs_all ); //'en_us|fr_fr|de_de|es_es'; // get from xili_language plugin - first is ref.
		}
		
		$category_base_option = get_option('category_base');
		$category_base = ($category_base_option) ? $category_base_option : 'category'; // in multisite - flush two times and verify with monkeyman-rewrite-analyzer.1.0 plugin
		
		/* RULES */
		// feed rules for categories - top
		add_rewrite_rule ( '('.$language_slugs_list.')/'.$category_base.'/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$', 'index.php?category_name=$matches[2]&lang=$matches[1]&feed=$matches[3]', 'top');
		add_rewrite_rule ( '('.$language_slugs_list.')/'.$category_base.'/(.+?)/(feed|rdf|rss|rss2|atom)/?$', 'index.php?category_name=$matches[2]&lang=$matches[1]&feed=$matches[3]', 'top');
		
		// categories rules
		add_rewrite_rule ( '('.$language_slugs_list.')/'.$category_base.'/(.+?)(/page/([0-9]{1,}))?/?$', 'index.php?category_name=$matches[2]&paged=$matches[4]&lang=$matches[1]', 'top');
		add_rewrite_rule ( '('.$language_slugs_list.')/'.$category_base.'/(.+?)/?$', 'index.php?category_name=$matches[2]&lang=$matches[1]', 'top');
		
		// post rules
		add_rewrite_rule ( '('.$language_slugs_list.')/(\d+)/(.+?)/?$', 'index.php?p=$matches[2]', 'top');
		// page rules
		add_rewrite_rule ( '('.$language_slugs_list.')/(.+?)/?$', 'index.php?pagename=$matches[2]', 'top');
	
		// home, frontpage rules
		add_rewrite_rule ( '('.$language_slugs_list.')/?$', 'index.php?lang=$matches[1]', 'top');
		
		/* used in permastruct */
		add_rewrite_tag ( '%lang%', '('.$language_slugs_list.')' );
		add_rewrite_tag ( '%language%', '('.$language_slugs_list.')' );
		
		/* xl taxoname as alias of lang query_var not declared as rewrite in taxonomy */
		add_permastruct ( 'language', '%lang%', true, 1 );
		add_permastruct ( 'category', '%lang%/'.$category_base.'/%category%', true, 1 );
		
	}
}

/**
 * fill permastruct of terms links
 * 
 * 
 */
function insert_lang_4cat ( $termlink, $term, $taxonomy ) {
	global $xili_language;
	
	if ( version_compare( XILILANGUAGE_VER, '2.8.2', '>=') ){
		$the_lang = $xili_language->lang_slug_qv_trans ( the_curlang() );
	} else {
		
		$the_lang = the_curlang() ;
	}
	
	if ( 'category' == $taxonomy ) {
		
		$termlink = str_replace("%lang%", $the_lang , $termlink);
	}
	if ( 'language' == $taxonomy ) {
		
		$termlink = str_replace("%lang%", $the_lang  , $termlink);
	}
	return $termlink ;
}

/**
 * fill permastruct of post links
 * 
 * 
 */

function insert_lang_tag_4post ( $permalink, $post, $leavename ) {
	return "/%lang%".$permalink;
	
}

function insert_lang_4post ( $permalink, $post, $leavename ) {
	
	global $xili_language;
	
	$post_lang_slug = $xili_language->get_post_language ( $post->ID );
	
	$permalink = ( version_compare( XILILANGUAGE_VER, '2.8.2', '>=') ) ? str_replace("%lang%", $xili_language->lang_slug_qv_trans ( $post_lang_slug ) , $permalink) : str_replace("%lang%", $post_lang_slug  , $permalink);
	
	return $permalink;
}

/**
 * fill permastruct of page links
 * 
 * 
 */
function insert_lang_tag_4page ( $url, $path, $orig_scheme, $blog_id ) {
	
	$category_base_option = get_option('category_base');
		$category_base = ($category_base_option) ? $category_base_option : 'category'; // à centraliser si class - ajouter "date"
	
	if ( $path !='' && $path !='/' && false === strpos( $path, '%lang' ) && false === strpos( $path, $category_base ) && false === strpos( $path, 'date' )  ) {
		$url = str_replace( $path, '%lang%/'.$path,  $url ) ;
	}
	return $url;
}

function insert_lang_4page ( $permalink, $post_id ) {
	
	global $xili_language; 
	
	$post_lang_slug = $xili_language->get_post_language ( $post_id );
	
	$permalink = ( version_compare( XILILANGUAGE_VER, '2.8.2', '>=') ) ? str_replace("%lang%", $xili_language->lang_slug_qv_trans ( $post_lang_slug ) , $permalink) : str_replace("%lang%", $post_lang_slug  , $permalink);
	
	return $permalink;
}

// end

?>