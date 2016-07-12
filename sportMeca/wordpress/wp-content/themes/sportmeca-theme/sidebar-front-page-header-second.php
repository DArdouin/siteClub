<?php
    /* SIDEBAR */
    if ( dynamic_sidebar( 'front-page-header-second' ) ){
        /* IF NOT EMPTY */    
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){
        echo '<div class="widget widget_text">';
        echo '<div class="textwidget">';
        echo '<h3>' . __( 'Notre association' , 'cannyon' ) . '</h3>';
        echo '<p>' . __( 'Des membres passionnés de moto, et qui font tout pour partager cela, avec vous.' , 'cannyon' ) . '</p>';
        echo '</div>';
        echo '</div>';
    }
?>