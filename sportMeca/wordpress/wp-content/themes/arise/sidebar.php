<?php
/**
 * The sidebar containing the main Sidebar area.
 *
 * @package Theme Freesia
 * @subpackage Arise
 * @since Arise 1.0
 */
	$arise_settings = arise_get_theme_options();
	global $post;   
	global $arise_content_layout;
	if( $post ) {
		$layout = get_post_meta( $post->ID, 'arise_sidebarlayout', true );
	}
	if( empty( $layout ) || is_archive() || is_search() || is_home() ) {
		$layout = 'default';
	}

if( 'default' == $layout ) { //Settings from customizer
	if(($arise_settings['arise_sidebar_layout_options'] != 'nosidebar') && ($arise_settings['arise_sidebar_layout_options'] != 'fullwidth')){ ?>

<div id="secondary">
<?php }
}else{ // for page/ post
		if(($layout != 'no-sidebar') && ($layout != 'full-width')){ ?>
<div id="secondary">
  <?php }
	}?>
  <?php 
	if( 'default' == $layout ) { //Settings from customizer
		if(($arise_settings['arise_sidebar_layout_options'] != 'nosidebar') && ($arise_settings['arise_sidebar_layout_options'] != 'fullwidth')): ?>
  <?php dynamic_sidebar( 'arise_main_sidebar' ); ?>
</div> <!-- #secondary -->
<?php endif;
	}else{ // for page/post
		if(($layout != 'no-sidebar') && ($layout != 'full-width')){
			dynamic_sidebar( 'arise_main_sidebar' );
			echo '</div><!-- #secondary -->';
		}
	}
?>
