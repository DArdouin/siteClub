<?php
    /* SIDEBAR */
    if ( dynamic_sidebar( 'front-page-header-first' ) ){
        /* IF NOT EMPTY */
    }

    else if( (bool)get_theme_mod( 'mythemes-default-content', true ) ){
        echo '<div class="widget widget_text">';
        echo '<div class="textwidget">';
        echo '<h3>' . __( 'Un site web' , 'cannyon' ) . '</h3>';
        echo '<p>' . __( 'Réalisé pour rester en contact avec vous, et pour simplifier les procédures d\'inscription' , 'cannyon' ) . '</p>';
        echo '</div>';
        echo '</div>';
    }
?>