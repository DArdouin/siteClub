<?php
	/* SIDEBAR */
    if ( dynamic_sidebar( 'footer-second' ) ){
        /* IF NOT EMPTY */    
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){		
		$paramSite = include('paramSite.php'); //On définit le texte
        echo '<div class="widget widget_text">';
        echo '<h5 style="color:seashell">' . __( 'Addresse' , 'cannyon' ) . '</h5>';
        echo '<div class="textwidget">' . sprintf(__($paramSite["adresse"],'cannyon')) . '</div>';
        echo '</div>';
    }
?>