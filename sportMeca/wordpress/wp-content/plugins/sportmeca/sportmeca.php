<?php 
/*
Plugin Name: Sport Méca : gestion
Description: Un plugin permettant l'inscription des pilotes sur notre site web
Version: 0.0.1 20160311
Author: ARDOUIN Damien
*/


class sportMecaPlugin
{
    public function __construct()
    {
        //On include les différents fichiers
        include 'registration.php'; //Page servant à gérer l'inscription des utilisateurs sur le site
        include 'eCommerce.php';
        include 'shortcode_engagements.php';
        
        //On inclus les scripts JS
        //$js_directory = '/js/'; 
        //wp_enqueue_script( 'formulairesPilotes', $js_directory . 'formulairesPilotes.js', 'jquery', '1.0' );
        //wp_enqueue_script( 'formulairesPilotes' ); 
        
        //On gère nos hook
        add_filter('wp_nav_menu_items', array($this,'sml_add_espace_utilisateur'), 10, 2); //Permet l'ajout d'un bouton de connexion/déconnexion
        add_action('template_redirect',array($this,'sml_redirect_user_not_logged_in'), 10, 2); //Permet de rediriger les utilisateurs non connectés lorsqu'ils veulent utiliser les pages woocommerce
        //add_action( 'wp_enqueue_scripts', array($this,'sml_enqueue_scripts'), 10, 2); //Ajout de la bibliothèque JQuery
        
        //On crée les bases si besoin
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pilotes (id INT AUTO_INCREMENT PRIMARY KEY, nom VARCHAR(255) NOT NULL, prénom VARCHAR(255) NOT NULL);");
        
        //On crée les objets dont on a besoin
        new RegistrationPage();
        new GestionECommerce();
        new GestionEngagement();
    }
    
    //Ajoute un bouton de co/déco dans le menu
    public function sml_add_espace_utilisateur($items, $args) {
        //On récupère le bouton pour se connecter/déconnecter
        ob_start();
        wp_loginout('index.php');
        $loginout = ob_get_contents();
        ob_end_clean();

        //On ajoute les éléments voulus dans le menu
        if(is_user_logged_in()){ //On crée un sous menu, comprenant la déconnexion et l'accès aux pages woocommerce
                $items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a href="' . site_url() .'/mon-compte">Mon compte</a>'; //Le menu
                $items .= '<ul class="sub-menu">'; //On prépare le sous menu
                $items .= '<li id="menu-item-79"> <a href="' . site_url() .'/panier">Panier</a></li>';//Le sous menu "panier"
                $items .= '<li id="menu-item-80"> <a href="' . site_url() .'/mon-compte/edit-account/">Paramètres</a></li>';//Le sous menu "paramètres"
                $items .= '<li id="menu-item-81"> <a href="' . site_url() .'/mes-engagements">Mes engagements</a></li>';//Le sous menu "paramètres"
                $items .= '<li>'. $loginout . '</li>'; //Le sous menu de déconnexion
                $items .= '</ul></li>';//On termine le menu
        }
        else{ //On affiche simplement le bouton de connexion
            $items .= '<li>'. $loginout .'</li>'; 
        }    
        return $items;
    }
      
    //Blocage de l'accès au contenu woocommerce pour les non-utilisateurs
    public function sml_redirect_user_not_logged_in(){
       if(!is_user_logged_in() && (is_woocommerce() || is_cart() || is_checkout() || is_page('Mes engagements'))){ //Si on est sur une page woocommerce, non connceté
            auth_redirect();
            exit;
        }
    }
    
    //On inclus JQuery
    function sml_enqueue_scripts() {
        wp_enqueue_script( 'jquery' ); 
    }
}

//On instancie notre objet
new sportMecaPlugin();
?>