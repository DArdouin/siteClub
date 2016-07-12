<?php
/**
 * Custom functions
 *
 */

 /*Suppression des hook parents*/
 function remove_parent_function(){
	remove_action('arise_site_branding','arise_header_display');
 }
 add_action( 'wp_loaded', 'remove_parent_function' );
 
 /*Gestion de l'affichage du header*/
function sportMeca_header_display(){
	/*On reprend le code de "function.php"*/
	$arise_settings = arise_get_theme_options();
	$header_display = $arise_settings['arise_header_display'];
	$header_logo = $arise_settings['arise-img-upload-header-logo'];
	if ($header_display == 'header_text') { ?>
		<div id="site-branding">
		<?php if(is_home() || is_front_page()){ ?>
		<h1 id="site-title"> <?php }else{?> <h2 id="site-title"> <?php } ?>
			<a href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"> <?php bloginfo('name');?> </a>
		<?php if(is_home() || is_front_page() || is_search()){ ?>
		</h1>  <!-- end .site-title -->
		<?php } else { ?> </h2> <!-- end .site-title --> <?php } 
		$site_description = get_bloginfo( 'description', 'display' );
		if($site_description){?>
		<p id ="site-description"> <?php bloginfo('description');?> </p> <!-- end #site-description -->
		<?php } ?>
		</div> <!-- end #site-branding -->
		<?php
	} elseif ($header_display == 'header_logo') { ?>
		<div id="site-branding"> <a href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"> <img src="<?php echo esc_url($header_logo);?>" id="site-logo" class="sml-site-logo"  alt="<?php echo esc_attr(get_bloginfo('name', 'display'));?>"></a> </div> <!-- end #site-branding -->
		<?php } elseif ($header_display == 'show_both'){ ?>
		<div id="site-branding"> <a href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"> <img src="<?php echo esc_url($header_logo);?>" id="site-logo" class="sml-site-logo" alt="<?php echo esc_attr(get_bloginfo('name', 'display'));?>"></a>
		<?php if(is_home() || is_front_page()){ ?>
		<h1 id="site-title"> <?php }else{?> <h2 id="site-title"> <?php } ?>
			<a href="<?php echo esc_url(home_url('/'));?>" title="<?php echo esc_attr(get_bloginfo('name', 'display'));?>" rel="home"> <?php bloginfo('name');?> </a>
			<?php if(is_home() || is_front_page()){ ?> </h1> <!-- end .site-title -->
		<?php }else{ ?> </h2> <!-- end .site-title -->
		<?php }
		$site_description = get_bloginfo( 'description', 'display' );
			if($site_description){?>
			<p id ="site-description"> <?php bloginfo('description');?> </p>
		<?php } ?>
		</div> <!-- end #site-branding -->
		<?php }
}
add_action('arise_site_branding','sportMeca_header_display',100);
?>