<?php
/**
 * ***** Functions to improve xili-language *****
 * ** selection for twentytwelve-xili child of twentytwelve **
 */
 
/**
 * ***** BreadCrump ******
 * @since 20101111
 *
 * can be adapted with two end params
 */
function xiliml_adjacent_join_filter($join, $in_same_cat, $excluded_categories) {
	global $post, $wpdb;
	$curlang = xiliml_get_lang_object_of_post( $post->ID );
	// in join p is $wpdb->posts AS p in get_adjacent_post of lin_template.php
	if ($curlang) { // only when language is defined !
		$join .= " LEFT JOIN $wpdb->term_relationships as xtr ON (p.ID = xtr.object_id) LEFT JOIN $wpdb->term_taxonomy as xtt ON (xtr.term_taxonomy_id = xtt.term_taxonomy_id) ";
	}	
return $join;
}

function xiliml_adjacent_where_filter($where, $in_same_cat, $excluded_categories) {
	global $post;
	$curlang = xiliml_get_lang_object_of_post( $post->ID );
	if ( $curlang ) {
		$wherereqtag = $curlang->term_id; 
		$where .= " AND xtt.taxonomy = '".TAXONAME."' ";
		$where .= " AND xtt.term_id = $wherereqtag "; 
	}
	return $where;
}

if ( class_exists('xili_language') ) {
	
	add_filter('get_next_post_join','xiliml_adjacent_join_filter',10,3);
	add_filter('get_previous_post_join','xiliml_adjacent_join_filter',10,3);
	
	add_filter('get_next_post_where','xiliml_adjacent_where_filter',10,3);
	add_filter('get_previous_post_where','xiliml_adjacent_where_filter',10,3);
	
}


/**
 * add search other languages in form - see functions.php when fired
 *
 */
function my_langs_in_search_form_2012 ( $the_form ) {
	
	$form = str_replace ( '</form>', '', $the_form ) . '<div class="xili-s-radio">' . xiliml_langinsearchform ( $before='<span class="radio-lang">', $after='</span>', false) . '</div>';
	$form .= '</form>';
	return $form ;
}



/*special flags in list*/
function xiliml_infunc_the_other_posts($post_ID, $before = "Read This post in", $separator = ", ", $type = "display") {
			$outputarr = array();
			$listlanguages = get_terms(TAXONAME, array('hide_empty' => false));
			$post_lang = get_cur_language($post_ID); // to be used in multilingual loop since 1.1
			//$post_lang = $langpost['lang']; //print_r($langpost);
			$xili_theme_options = xili_twentytwelve_get_theme_options() ; // see below
		
			$show_flag = ( !isset ( $xili_theme_options['no_flags'] ) || $xili_theme_options['no_flags'] != 'hidden_flags' ) ? true : false ;
			foreach ($listlanguages as $language) {
				$otherpost = get_post_meta($post_ID, 'lang-'.$language->slug, true);
				
				if ( $type == "display" ) {
					if ('' != $otherpost && $language->slug != $post_lang ) {
						if ( $show_flag ) {
							$flag =  ' <img src="'.get_bloginfo('stylesheet_directory').'/images/flags/'.$language->slug.'.png" alt="" />' ; 
							$class = 'class="text-lang" ';
						} else { 
							$flag = '';
							$class = '';	
						}
						
						$outputarr[] = '<a href="'.get_permalink($otherpost).'" ><span '.$class.'>'.__($language->description,the_theme_domain()) .'</span>'. $flag . '</a>';
						
					}
				} elseif ($type == "array") { // here don't exclude cur lang
					if ('' != $otherpost)
						$outputarr[$language->slug] = $otherpost;
				}
			}
			
			if ( $type == "display" ) {
				$output = "";
				if (!empty($outputarr))
					$output =  (($before !="") ? __($before,the_theme_domain())." " : "" ).implode ($separator, $outputarr);
				if ('' != $output) { echo $output;}	
			} elseif ($type == "array") {
				if (!empty($outputarr)) {
					$outputarr[$post_ID] = $post_lang; 
					// add a key with curid to give his lang (empty if undefined)
					return $outputarr;
				} else {
					return false;	
				}
			}	
						
}
add_filter('xiliml_the_other_posts','xiliml_infunc_the_other_posts',10,4); // 1.1 090917

/**
 * this part for language like khmer without set_locale on server
 * to be active, the item  Server Entities Charset: must be set to "no_locale" for the target language (here km_kh)
 *
 */

/* inspired part copied from Nathan Author URI: http://www.sbbic.org/ (xili team don't read khmer ;-) ) */
function xili_translate_date ( $slug, $text ) {
	switch ($slug) {
		
		case 'hu_hu': // examples of texts kept in WP hu_HU.po kit - not able to verify - just for demo Hungarian - Magyar
		// Date Format: F j, Y is translated in Y. F j.  l
		// here with no_locale - not needed on internal 10.6.8 server when set UTF-8 on Charset or MAMP
			$text = str_replace('January', 'január', $text);
			$text = str_replace('February', 'február', $text);
			$text = str_replace('March', 'március', $text);
			$text = str_replace('April', 'április', $text);
			$text = str_replace('May', 'május', $text);
			$text = str_replace('June', 'június', $text);
			$text = str_replace('July', 'július', $text);
			$text = str_replace('August', 'augusztus', $text);
			$text = str_replace('September', 'szeptember', $text);
			$text = str_replace('October', 'október', $text); 
			$text = str_replace('November', 'november', $text); 
			$text = str_replace('December', 'december', $text);
			$text = str_replace('Jan', 'jan', $text);
			$text = str_replace('Feb', 'feb', $text);
			$text = str_replace('Mar', 'márc', $text);
			$text = str_replace('Apr', 'ápr', $text);
			$text = str_replace('May', 'máj', $text);
			$text = str_replace('Jun', 'jún', $text);
			$text = str_replace('Jul', 'júl', $text);
			$text = str_replace('Aug', 'auj', $text);
			$text = str_replace('Sep', 'szept', $text);
			$text = str_replace('Oct', 'okt', $text); 
			$text = str_replace('Nov', 'nov', $text); 
			$text = str_replace('Dec', 'dec', $text);
			
			$text = str_replace('Saturday', 'szombat', $text);
			$text = str_replace('Sunday', 'vasárnap', $text);
			$text = str_replace('Monday', 'hétfő', $text);
			$text = str_replace('Tuesday', 'kedd', $text);
			$text = str_replace('Wednesday', 'szerda', $text);
			$text = str_replace('Thursday', 'csütörtök', $text);
			$text = str_replace('Friday', 'péntek', $text);
			$text = str_replace('Sat', 'Szo', $text);
			$text = str_replace('Sun', 'Vas', $text);
			$text = str_replace('Mon', 'Hét', $text);
			$text = str_replace('Tues', 'Ked', $text);
			$text = str_replace('Tue', 'Ked', $text);
			$text = str_replace('Wed', 'Sze', $text);
			$text = str_replace('Thurs', 'Csü', $text);
			$text = str_replace('Thu', 'Csü', $text);
			$text = str_replace('Fri', 'Pén', $text);
			
			$text = str_replace('am', 'de.', $text); 
			$text = str_replace('pm', 'du.', $text); 
			$text = str_replace('AM', 'DE.', $text); 
			$text = str_replace('PM', 'DU.', $text); 
			
			$text = str_replace('th', '', $text); 
			$text = str_replace('st', '', $text);
			$text = str_replace('rd', '', $text);
		    break;
		
		case 'km_kh':
			$text = str_replace('1', '១', $text);
			$text = str_replace('2', '២', $text);
			$text = str_replace('3', '៣', $text);
			$text = str_replace('4', '៤', $text);
			$text = str_replace('5', '៥', $text);
			$text = str_replace('6', '៦', $text);
			$text = str_replace('7', '៧', $text);
			$text = str_replace('8', '៨', $text);
			$text = str_replace('9', '៩', $text);
			$text = str_replace('0', '៩', $text); 
									
			$text = str_replace('January', 'មករា', $text);
			$text = str_replace('February', 'កុម្ភៈ', $text);
			$text = str_replace('March', 'មីនា', $text);
			$text = str_replace('April', 'មេសា', $text);
			$text = str_replace('May', 'ឧសភា', $text);
			$text = str_replace('June', 'មិថុនា', $text);
			$text = str_replace('July', 'កក្កដា', $text);
			$text = str_replace('August', 'សីហា', $text);
			$text = str_replace('September', 'កញ្ញា', $text);
			$text = str_replace('October', 'តុលា', $text); 
			$text = str_replace('November', 'វិច្ឆិកា', $text); 
			$text = str_replace('December', 'ធ្នូ', $text);
			$text = str_replace('Jan', 'មករា', $text);
			$text = str_replace('Feb', 'កុម្ភៈ', $text);
			$text = str_replace('Mar', 'មីនា', $text);
			$text = str_replace('Apr', 'មេសា', $text);
			$text = str_replace('May', 'ឧសភា', $text);
			$text = str_replace('Jun', 'មិថុនា', $text);
			$text = str_replace('Jul', 'កក្កដា', $text);
			$text = str_replace('Aug', 'កញ្ញា', $text);
			$text = str_replace('Sep', 'កញ្ញា', $text);
			$text = str_replace('Oct', 'តុលា', $text); 
			$text = str_replace('Nov', 'វិច្ឆិកា', $text); 
			$text = str_replace('Dec', 'ធ្នូ', $text);
			
			$text = str_replace('Saturday', 'ថ្ងៃសុក្រ', $text);
			$text = str_replace('Sunday', 'ថ្ងៃអាទិត្យ', $text);
			$text = str_replace('Monday', 'ថ្ងៃចន្ទ', $text);
			$text = str_replace('Tuesday', 'ថ្ងៃអង្គារ', $text);
			$text = str_replace('Wednesday', 'ថ្ងៃពុធ', $text);
			$text = str_replace('Thursday', 'ថ្ងៃព្រហស្បតិ៍', $text);
			$text = str_replace('Friday', 'ថ្ងៃសុក្រ', $text);
			$text = str_replace('Sat', 'ស', $text);
			$text = str_replace('Sun', 'អា', $text);
			$text = str_replace('Mon', 'ច', $text);
			$text = str_replace('Tues', 'អ', $text);
			$text = str_replace('Tue', 'អ', $text);
			$text = str_replace('Wed', 'អ', $text);
			$text = str_replace('Thurs', 'ព្រ', $text);
			$text = str_replace('Thu', 'ព្រ', $text);
			$text = str_replace('Fri', 'សុ', $text);
			
			$text = str_replace('th', '', $text); 
			$text = str_replace('st', '', $text);
			$text = str_replace('rd', '', $text);

		break;
		default:
	
	}
	
	return $text;
}



?>