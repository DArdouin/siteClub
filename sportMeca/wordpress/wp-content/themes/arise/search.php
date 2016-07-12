<?php
/**
 * The template for displaying search results.
 *
 * @package Theme Freesia
 * @subpackage Arise
 * @since Arise 1.0
 */
get_header();
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

<div id="primary">
	<?php }
	}?>
	<div id="main">
	<?php global $post;
	if( have_posts() ) {
		while( have_posts() ) {
			the_post(); ?>
	<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<article class="post-format">
			<header class="entry-header">
				<h2 class="entry-title"> <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>">
				<?php the_title(); ?> </a> </h2> <!-- .entry-title -->
			</header>
			<div class="entry-content clearfix">
				<?php the_excerpt(); ?>
			</div>
		</article>
	</section>
	<?php
		}
	}
	else { ?>
	<h2 class="entry-title">
		<?php get_search_form(); ?>
		<p>&nbsp; </p>
		<?php _e( 'No Posts Found.', 'arise' ); ?>
	</h2>
	<?php
	} ?>
	</div> <!-- #content -->
	<?php get_template_part( 'navigation', 'none' );
if( 'default' == $layout ) { //Settings from customizer
	if(($arise_settings['arise_sidebar_layout_options'] != 'nosidebar') && ($arise_settings['arise_sidebar_layout_options'] != 'fullwidth')): ?>
</div> <!-- #primary -->
<?php endif;
}
get_sidebar();
get_footer(); ?>
