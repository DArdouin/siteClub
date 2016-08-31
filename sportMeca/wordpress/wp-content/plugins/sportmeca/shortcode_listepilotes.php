<?php


/*
 * Classe permettant l'affichage de la liste des pilotes, pour une course
 */

class AffichageEngagements {
     public function __construct()
    {
        //On ajoute le shortcode
        add_shortcode('pilotes_inscris', array($this, 'sml_display_pilots_list'));
    }
    
    /**
     * Permet de retourner la liste des pilotes inscrits
     * @param type $atts Le tableau contenant les paramètres
     * @param type $content Le contenu, entre les balises ouvrantes et fermantes
     */
    public function sml_display_pilots_list($atts, $content){
        //On initialise le tableau atts (au cas ou vide)        
        global $wpdb;
        
        //On récupère l'id de l'utilisateur
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $user_name = $user->user_firstname;
        
        //L'entête de la page, contient une combobox
        $html = array();
        
        //On va chercher la liste des pilotes, avec leur catégorie
            //Les solo moto
        
            //Les duo moto
        
            //Les solo quad
        
            //Les 85
        
        $html[] = '<div class="button_list">'
        .   $this->get_switch("Test1","solo_moto")
        .   $this->get_switch("Test2","duo_moto")
        .   '</div>';   
        
        echo implode('', $html);
    }
    
    /**
     * Permet de renvoyer le code nécessaire à la création d'une checkbox de type "switch"
     * @param type $label
     * @param type $race_type
     * @return type
     */
    public function get_switch($label,$race_type){
        $html[] = '<label class="switch">'
        .   "<input id='in_{$label}' class='switch-input' type='checkbox' />"
        .   '<span class="switch-label" data-on="On" data-off="Off"></span>' 
        .   '<span class="switch-handle"></span> '
        .   '</label>';
        
        return implode('',$html);
    }
}