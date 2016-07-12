<?php
/**
 * The template for displaying the footer.
 *
 * @package Theme Freesia
 * @subpackage Arise
 * @since Arise 1.0
 */
$arise_settings = arise_get_theme_options();
if (!is_page_template('page-templates/arise-corporate.php') ){ 
  if(is_page_template('three-column-blog-template.php') || is_page_template('our-team-template.php') || is_page_template('about-us-template.php') || is_page_template('important-works-template.php') ){

	}else{?>
</div>
<!-- end .container -->
<?php }
} ?>
</div>
<!-- end #content -->
<!-- Footer Start ============================================= -->
<footer id="colophon" class="site-footer clearfix">
	<?php
				$footer_column = $arise_settings['arise_footer_column_section'];
					if( is_active_sidebar( 'arise_footer_1' ) || is_active_sidebar( 'arise_footer_2' ) || is_active_sidebar( 'arise_footer_3' ) || is_active_sidebar( 'arise_footer_4' )) { ?>
	<div class="widget-wrap">
		<div class="container">
			<div class="widget-area clearfix">
			<?php
				if($footer_column == '1' || $footer_column == '2' ||  $footer_column == '3' || $footer_column == '4'){
				echo '<div class="column-'.$footer_column.'">';
					if ( is_active_sidebar( 'arise_footer_1' ) ) :
						dynamic_sidebar( 'arise_footer_1' );
					endif;
				echo '</div><!-- end .column'.$footer_column. '  -->';
				}
				if($footer_column == '2' ||  $footer_column == '3' || $footer_column == '4'){
				echo '<div class="column-'.$footer_column.'">';
					if ( is_active_sidebar( 'arise_footer_2' ) ) :
						dynamic_sidebar( 'arise_footer_2' );
					endif;
				echo '</div><!--end .column'.$footer_column.'  -->';
				}
				if($footer_column == '3' || $footer_column == '4'){
				echo '<div class="column-'.$footer_column.'">';
					if ( is_active_sidebar( 'arise_footer_3' ) ) :
						dynamic_sidebar( 'arise_footer_3' );
					endif;
				echo '</div><!--end .column'.$footer_column.'  -->';
				}
				if($footer_column == '4'){
				echo '<div class="column-'.$footer_column.'">';
					if ( is_active_sidebar( 'arise_footer_4' ) ) :
						dynamic_sidebar( 'arise_footer_4' );
					endif;
				echo '</div><!--end .column'.$footer_column.  '-->';
				}
				?>
			</div> <!-- end .widget-area -->
			<?php if(has_nav_menu('footermenu')):
				$args = array(
					'theme_location' => 'footermenu',
					'container'      => '',
					'items_wrap'     => '<ul>%3$s</ul>',
				);
				echo '<nav id="footer-navigation" role="navigation">';
				wp_nav_menu($args);
				echo '</nav><!-- end #footer-navigation -->';
			endif; ?>
		</div> <!-- end .container -->
	</div> <!-- end .widget-wrap -->
	<?php } ?>
	<div class="site-info">
		<div class="container">
			<?php
			if(has_nav_menu('social-link') && $arise_settings['arise_buttom_social_icons'] == 0):
				do_action('social_links');
			endif;
				do_action('arise_sitegenerator_footer'); ?>
			<div style="clear:both;"></div>
		</div> <!-- end .container -->
	</div> <!-- end .site-info -->
	<?php
		$disable_scroll = $arise_settings['arise_scroll'];
		if($disable_scroll == 0):?>
	<div class="go-to-top"><a title="<?php _e('Go to Top','arise');?>" href="#masthead"></a></div> <!-- end .go-to-top -->
	<?php endif; ?>
</footer> <!-- end #colophon -->
</div> <!-- end #page -->
<?php wp_footer(); ?>
</body>
</html>
