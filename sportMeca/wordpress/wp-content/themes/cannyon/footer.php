        <footer>
            <?php
                $are_active_sidebras =  is_active_sidebar( 'footer-first' ) ||
                                        is_active_sidebar( 'footer-second' ) ||
                                        is_active_sidebar( 'footer-third' ) ||
                                        is_active_sidebar( 'footer-fourth' );
                
                if( $are_active_sidebras || (bool)get_theme_mod( 'mythemes-default-content', true ) ){
            ?>
                    <aside class="mythemes-default-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <?php get_sidebar( 'footer-first' ); ?>
                                </div>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <?php get_sidebar( 'footer-second' ); ?>
                                </div>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <?php get_sidebar( 'footer-third' ); ?>
                                </div>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <?php get_sidebar( 'footer-fourth' ); ?>
                                </div>
                            </div>
                        </div>
                    </aside>
            <?php
                }
            ?>

            <div class="mythemes-dark-mask">
                <div class="container mythemes-social">
                    <div class="row">
                        <?php
                            $vimeo      = esc_url( get_theme_mod( 'mythemes-vimeo', 'http://vimeo.com/#' ) );
                            $twitter    = esc_url( get_theme_mod( 'mythemes-twitter', 'http://twitter.com/#' ) );
                            $skype      = esc_url( get_theme_mod( 'mythemes-skype' ) );
                            $renren     = esc_url( get_theme_mod( 'mythemes-renren' ) );
                            $github     = esc_url( get_theme_mod( 'mythemes-github' ) );
                            $rdio       = esc_url( get_theme_mod( 'mythemes-rdio' ) );
                            $linkedin   = esc_url( get_theme_mod( 'mythemes-linkedin' ) );
                            $behance    = esc_url( get_theme_mod( 'mythemes-behance', 'http://behance.com/#' ) );
                            $dropbox    = esc_url( get_theme_mod( 'mythemes-dropbox' ) );
                            $flickr     = esc_url( get_theme_mod( 'mythemes-flickr', 'http://flickr.com/#' ) );
                            $tumblr     = esc_url( get_theme_mod( 'mythemes-tumblr' ) );
                            $instagram  = esc_url( get_theme_mod( 'mythemes-instagram' ) );
                            $vkontakte  = esc_url( get_theme_mod( 'mythemes-vkontakte' ) );
                            $facebook   = esc_url( get_theme_mod( 'mythemes-facebook', 'http://facebook.com/#' ) );
                            $evernote   = esc_url( get_theme_mod( 'mythemes-evernote' ) );
                            $flattr     = esc_url( get_theme_mod( 'mythemes-flattr' ) );
                            $picasa     = esc_url( get_theme_mod( 'mythemes-picasa' ) );
                            $dribbble   = esc_url( get_theme_mod( 'mythemes-dribbble' ) );
                            $mixi       = esc_url( get_theme_mod( 'mythemes-mixi' ) );
                            $stumbl     = esc_url( get_theme_mod( 'mythemes-stumbleupon' ) );
                            $lastfm     = esc_url( get_theme_mod( 'mythemes-lastfm' ) );
                            $gplus      = esc_url( get_theme_mod( 'mythemes-gplus' ) );
                            $gcircle    = esc_url( get_theme_mod( 'mythemes-google-circles' ) );
                            $youtubep   = esc_url( get_theme_mod( 'mythemes-youtube-play' ) );
                            $youtube    = esc_url( get_theme_mod( 'mythemes-youtube' ) );
                            $pinterest  = esc_url( get_theme_mod( 'mythemes-pinterest', 'http://pinterest.com/#' ) );
                            $smashing   = esc_url( get_theme_mod( 'mythemes-smashing' ) );
                            $soundcloud = esc_url( get_theme_mod( 'mythemes-soundcloud' ) );
                            $rss        = esc_url( get_theme_mod( 'mythemes-rss', esc_url( get_bloginfo('rss2_url') ) ) );

                            
                            if( !(  empty( $vimeo ) && empty( $twitter ) && empty( $skype ) && empty( $renren ) && empty( $github ) && empty( $rdio ) && empty( $linkedin ) && empty( $behance ) &&
                                    empty( $dropbox ) && empty( $flickr ) && empty( $tumblr ) && empty( $instagram ) && empty( $vkontakte ) && empty( $facebook ) && empty( $evernote ) && empty( $flattr ) &&
                                    empty( $picasa ) && empty( $dribbble ) && empty( $mixi ) && empty( $stumbl ) && empty( $lastfm ) && empty( $gplus ) && empty( $gcircle ) && empty( $youtubep ) && empty( $youtube ) &&
                                    empty( $pinterest ) && empty( $smashing ) && empty( $soundcloud ) && empty( $rss ) ) ){
                        ?>
                                <div class="col-lg-12">
                                    <?php
                                        if( !empty( $vimeo ) ){
                                            echo '<a href="' . $vimeo . '" class="mythemes-icon-vimeo" target="_blank"></a>';
                                        }
                                        if( !empty( $twitter ) ){
                                            echo '<a href="' . $twitter . '" class="mythemes-icon-twitter" target="_blank"></a>';
                                        }
                                        if( !empty( $skype ) ){
                                            echo '<a href="' . $skype . '" class="mythemes-icon-skype" target="_blank"></a>';
                                        }
                                        if( !empty( $renren ) ){
                                            echo '<a href="' . $renren . '" class="mythemes-icon-renren" target="_blank"></a>';
                                        }
                                        if( !empty( $github ) ){
                                            echo '<a href="' . $github . '" class="mythemes-icon-github" target="_blank"></a>';
                                        }
                                        if( !empty( $rdio ) ){
                                            echo '<a href="' . $rdio . '" class="mythemes-icon-rdio" target="_blank"></a>';
                                        }
                                        if( !empty( $linkedin ) ){
                                            echo '<a href="' . $linkedin . '" class="mythemes-icon-linkedin" target="_blank"></a>';
                                        }
                                        if( !empty( $behance ) ){
                                            echo '<a href="' . $behance . '" class="mythemes-icon-behance" target="_blank"></a>';
                                        }
                                        if( !empty( $dropbox ) ){
                                            echo '<a href="' . $dropbox . '" class="mythemes-icon-dropbox" target="_blank"></a>';
                                        }
                                        if( !empty( $flickr ) ){
                                            echo '<a href="' . $flickr . '" class="mythemes-icon-flickr" target="_blank"></a>';
                                        }
                                        if( !empty( $tumblr ) ){
                                            echo '<a href="' . $tumblr . '" class="mythemes-icon-tumblr" target="_blank"></a>';
                                        }
                                        if( !empty( $instagram ) ){
                                            echo '<a href="' . $instagram . '" class="mythemes-icon-instagram" target="_blank"></a>';
                                        }
                                        if( !empty( $vkontakte ) ){
                                            echo '<a href="' . $vkontakte . '" class="mythemes-icon-vkontakte" target="_blank"></a>';
                                        }
                                        if( !empty( $facebook ) ){
                                            echo '<a href="' . $facebook . '" class="mythemes-icon-facebook" target="_blank"></a>';
                                        }
                                        if( !empty( $evernote ) ){
                                            echo '<a href="' . $evernote . '" class="mythemes-icon-evernote" target="_blank"></a>';
                                        }
                                        if( !empty( $flattr ) ){
                                            echo '<a href="' . $flattr . '" class="mythemes-icon-flattr" target="_blank"></a>';
                                        }
                                        if( !empty( $picasa ) ){
                                            echo '<a href="' . $picasa . '" class="mythemes-icon-picasa" target="_blank"></a>';
                                        }
                                        if( !empty( $dribbble ) ){
                                            echo '<a href="' . $dribbble . '" class="mythemes-icon-dribbble" target="_blank"></a>';
                                        }
                                        if( !empty( $mixi ) ){
                                            echo '<a href="' . $mixi . '" class="mythemes-icon-mixi" target="_blank"></a>';
                                        }
                                        if( !empty( $stumbl ) ){
                                            echo '<a href="' . $stumbl . '" class="mythemes-icon-stumbleupon" target="_blank"></a>';
                                        }
                                        if( !empty( $lastfm ) ){
                                            echo '<a href="' . $lastfm . '" class="mythemes-icon-lastfm" target="_blank"></a>';
                                        }
                                        if( !empty( $gplus ) ){
                                            echo '<a href="' . $gplus . '" class="mythemes-icon-gplus" target="_blank"></a>';
                                        }
                                        if( !empty( $gcircle ) ){
                                            echo '<a href="' . $gcircle . '" class="mythemes-icon-google-circles" target="_blank"></a>';
                                        }
                                        if( !empty( $youtubep ) ){
                                            echo '<a href="' . $youtubep . '" class="mythemes-icon-youtube-play" target="_blank"></a>';
                                        }
                                        if( !empty( $youtube ) ){
                                            echo '<a href="' . $youtube . '" class="mythemes-icon-youtube" target="_blank"></a>';
                                        }
                                        if( !empty( $pinterest ) ){
                                            echo '<a href="' . $pinterest . '" class="mythemes-icon-pinterest" target="_blank"></a>';
                                        }
                                        if( !empty( $smashing ) ){
                                            echo '<a href="' . $smashing . '" class="mythemes-icon-smashing" target="_blank"></a>';
                                        }
                                        if( !empty( $soundcloud ) ){
                                            echo '<a href="' . $soundcloud . '" class="mythemes-icon-soundcloud" target="_blank"></a>';
                                        }
                                        if( !empty( $rss ) ){
                                            echo '<a href="' . $rss . '" class="mythemes-icon-rss" target="_blank"></a>';
                                        }
                                    ?>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>

                <div class="mythemes-copyright">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <p>
                                    <span class="copyright"><?php echo mythemes_validate_copyright( get_theme_mod( 'mythemes-copyright' , sprintf( __( 'Copyright &copy; %s %s. Powered by %s.' , 'cannyon' ) , date( 'Y' ) , esc_html( get_bloginfo( 'name' ) ) , '<a href="http://wordpress.org/">WordPress</a>' ) ) ); ?></span>
                                    <span><?php echo mythemes_options::get( 'author-link' ); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </footer>

        <?php wp_footer(); ?>

    </body>
</html>