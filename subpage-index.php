<?php
/*
Plugin Name: Subpage Index
Plugin URI: http://www.acumendevelopment.net
Description: Shows an index of the current page's child pages
Author: Leo Brown
Version: 0.1
Author URI: http://www.acumendevelopment.net
*/
function subpageindex_func($args) {

	global $post;

	$out='';
	$p=get_children(array(
		'post_parent'=>$post->ID,
		'numberposts'=>-1,
		'orderby'=>'title',
		'order'=>'ASC',
		'post_type'=>'page',
		'depth'=>1,
		'post_status'=>'publish'
	));

	$out.= '<dl class="subpageindex">';
	if($p) foreach($p as $subpagePost){

		$out.= "<dt class='subpage_thumb'>";
		$out.= "<a href='".get_permalink($subpagePost)."'>";

		if($img=@wp_get_attachment_url(reset(get_post_meta($subpagePost->ID,'_thumbnail_id')))){
			$out.= "<img class='logo' src='{$img}' />";
		}

		// show parent image
		elseif($ancestors = get_post_ancestors($subpagePost)) foreach($ancestors as $ancestor){
			if($img=@wp_get_attachment_url(reset(get_post_meta($ancestor->ID,'_thumbnail_id')))){
				if('yes' !== @reset(get_post_meta($ancestor,'Prevent Image Inheritance (yes, no)'))){
					$out.= "<img class='logo' src='{$img}' />";
					break;
				}
			}
		}

		$out.= '</a>';
		$out.= '</dt>';

		// get description from AIOSEOP or the excerpt
		if(!($excerpt=@reset(get_post_meta($subpagePost->ID,'_aioseop_description')))){
			$excerpt=$subpagePost->post_excerpt;
		}

		$out.= "<dt class='subpage_title'>";
		$out.= "<a href='".get_permalink($subpagePost)."'>";
		$out.= $subpagePost->post_title;
		$out.= "</a>";
		$out.= "</dt>";

		$out.= "<dd class='subpage_excerpt'>";
		$out.= "<a href='".get_permalink($subpagePost)."'>";
		$out.= $excerpt;
		$out.= '</a>';
		$out.= "</dd>";
		$out.= '</dd>';
	}
	$out.= '</dl>';

	return $out;
}

add_shortcode('subpageindex', 'subpageindex_func');

?>
