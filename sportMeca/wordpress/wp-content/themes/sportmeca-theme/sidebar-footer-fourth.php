<?php
    /* SIDEBAR */
    if ( dynamic_sidebar( 'footer-fourth' ) ){
        /* IF NOT EMPTY */    
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){
		$paramSite = include('paramSite.php'); //On définit le texte
        echo '<div class="widget widget_text">';
        echo '<h5 style="color:seashell">' . __( 'Horaires' , 'cannyon' ) . '</h5>';
        echo '<div class="textwidget">';
        echo __( $paramSite["jours"] , 'cannyon' ) . '<br/>';
        echo sprintf("%s - %s",$paramSite["heureDepart"],$paramSite["heureFin"]);
        echo '</div>';
        echo '</div>';
    }
?>