<?php
    /* SIDEBAR */
    if ( dynamic_sidebar( 'front-page-header-third' ) ){
        /* IF NOT EMPTY */    
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){
        echo '<div class="widget widget_text">';
        echo '<div class="textwidget">';
        echo '<h3>' . __( 'Le tout terrain' , 'cannyon' ) . '</h3>';
        echo '<p>' . __( 'Une histoire de famille, d\'amis, et de super moments passés ensemble. Bienvenue dans notre univers !' , 'cannyon' ) . '</p>';
        echo '</div>';
        echo '</div>';
    }
?>