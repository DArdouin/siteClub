<?php
    /* SIDEBAR */
    if ( dynamic_sidebar( 'footer-third' ) ){
        /* IF NOT EMPTY */    
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){	
		$paramSite = include('paramSite.php'); //On définit le texte
        echo '<div class="widget widget_text">';
        echo '<h5 style="color:seashell">' . __( 'Liens utiles' , 'cannyon' ) . '</h5>';
        echo '<div class="textwidget">';
        echo sprintf( __( 'Facebook : %s' , 'cannyon' ) , $paramSite["facebook"] ) . '<br>';
        echo sprintf( __( 'Email : %s' , 'cannyon' ) ,  ' ' . antispambot( $paramSite["email"] ) . '<br>');
		echo sprintf( __( 'F&eacute;d&eacute;ration : %s' , 'cannyon' ) , $paramSite["fede"] ) . '<br>';
        echo '</div>';
        echo '</div>';
    }
?>