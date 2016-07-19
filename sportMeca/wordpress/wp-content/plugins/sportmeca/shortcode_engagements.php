<?php

/*
 * Classe permettant l'affichage de tous les engagements d'un pilote
 * C'est également cette classe qui procure le shortcode à intégrer dans la page "Mes engagements"
 */

class GestionEngagement {
     public function __construct()
    {
        //On ajoute le shortcode
        add_shortcode('engagements', array($this, 'sml_display_engagements_list'));
         
         //On ajoute les hook pour répondre aux requêtes ajax
        add_action('wp_ajax_nopriv_sml_get_engagements', 'sml_get_engagements');
        add_action('wp_ajax_sml_get_engagements', array($this,'sml_get_engagements'), 10, 2);
        add_action('wp_ajax_nopriv_sml_save_engagement', 'sml_save_engagement');
        add_action('wp_ajax_sml_save_engagement', array($this,'sml_save_engagement'), 10, 2);        
        add_action( 'wp_enqueue_scripts', array($this,'my_scripts'), 10, 2);
    }
    
    public function my_scripts(){
        wp_enqueue_script('jquery');   
        wp_register_script('my-ajax-request', plugin_dir_url( __FILE__ ) . '/js/formulairesPilotes.js');        
        wp_enqueue_script( 'my-ajax-request', plugin_dir_url( __FILE__ ) . '/js/formulairesPilotes.js');
        wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }
        
    /**
     * Permet de retourner la liste des engagements
     * @param type $atts Le tableau contenant les paramètres
     * @param type $content Le contenu, entre les balises ouvrantes et fermantes
     */
    public function sml_display_engagements_list($atts, $content){
        //On initialise le tableau atts (au cas ou vide)        
        global $wpdb;
        $atts = shortcode_atts(array('number_engagements' => 0), $atts);
        
        //On récupère l'id de l'utilisateur
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $user_name = $user->user_firstname;
        
        //L'entête de la page, contient une combobox
        $html = array();
        $html[] = "<p> Bienvenue sur la page de gestion de vos engagements <b>{$user_name}</b> ! <br>";
        $html[] = 'Ici, vous pourrez remplir tous les engagements acheté en boutique</p>';
        
        //On recherche dans la base toutes les courses pour lesquelles le pilote est engagé
        $prefix = $wpdb->prefix; //On récupère le préfixe         
        $results  = $wpdb->get_results( //La requête
            "select DISTINCT(wp_pilotes.race_name) from wp_pilotes where wp_pilotes.buyer_id = {$user_id}"
        );
        if(!empty($results)){ //On test si l'utilisateur possède des engagements    
            $html[] = '<div>Selectionnez dans la liste ci-dessous la manifestation pour laquelle vous voulez remplir votre engagement <br> <br>';
            $html[] = '<p><select id="select_race" class="sml_select">'; //La combo contenant les pilotes  
            $html[] = "<option value='empty'> -- Sélectionnez la course</option>";
            foreach($results as $row){
                $html[]="<option value='{$row->race_name}'>{$row->race_name}</option>";
            }        
            $html[] = '</select></p></div>';
        }
        else{ //Le pilote ne possède pas d'engagements, on lui propose d'aller sur la boutique
            $url_boutique = site_url() . '/boutique';
            $html[] = '<p>Désolé, mais nous n\'avons trouvé aucun engagement associé à votre compte. Pour pouvoir acquérir des engagements, ';
            $html[] = 'rendez-vous sur notre boutique : ';
            $html[] = "<i><a href='{$url_boutique}'>Je veux m'engager</a></i>";
        }
        
        //On insère une balise <p>. C'est dans celle ci que le JS viendra intégrer la liste des engagements
        $html[] = '<div id="liste_engagements"></span>';
        
        echo implode('', $html);
    }
    
    /**
     * C'est dans cette fonction que l'on va aller chercher les engagements de l'utilisateur connecté
     * Puis, on va préparer tous les formulaires
     */
    public function sml_get_engagements(){
        if( isset($_POST['race_name'])){
            $race_name = $_POST['race_name'];
            $user = wp_get_current_user();
            $user_id = $user->ID;
            
            //On effectue la requête
            $html = array();
            $i = 0;
            $j = 1;
            $num_pilote = 0;
            $pair = 'pair';
            global $wpdb;
            $results = $wpdb->get_results( //La requête
                "select * from wp_pilotes where wp_pilotes.buyer_id = {$user_id} and wp_pilotes.race_name = '{$race_name}' order by team_key"
            );
            if(!empty($results)){ //Si la requête a retourné des informations 
                $html[] = "<div class='surengagement_impair'>";
                foreach($results as $row){
                    //Si on a pas la même clé que l'engagement précédent, on termine le surengagement pour en commencer un nouveau
                    if($i == 0) $previous_row_key = $row->team_key; //Pour le premier               
                    if($row->team_key != $previous_row_key){
                        $html[] = "</div><div class='surengagement_" . $pair . "'>"; //On ferme le surengagement précédent, on en ouvre un autre  
                        $num_pilote = 1;
                        $pair = ($pair=='pair') ? 'impair' : 'pair'  ;
                    } else {
                        $num_pilote ++;   
                        $html[] = ($i <> 0) ? '<hr>' : '';                        
                    }
                        
                    //Début de l'engagement
                    if(substr($row->eng_type,0,3)=='duo'){
                        $html[] = "<h3>";
                        $html[] = ($num_pilote == 1) ? "Equipage " . substr($row->eng_type,-4) . " - " : ""; 
                        $html[] = "Pilote n°" . $num_pilote . '</h3>'; //Si on est en équipage, on indique que c'est un pilote différent
                    } else {
                        $html[] = "<h3>Pilote solo " . substr($row->eng_type,-4) ."</h3>"; //Si on est en équipage, on indique que c'est un pilote différent
                    }
                    $i++;                    
                    $html[] = "<div class='engagement'>";
                    $html[] = "<form id='form_" . $row->id . "' id_pilote='" . $row->id ."' index='" . $i . "' "
                            .  "eng_type='" . $row->eng_type . "' race_name='" . $race_name . "' team_key='" . $row->team_key . "'>";
                    $numero = ($row->race_number != 0) ? $row->race_number : "non attribué" ;
                    $html[] = "<h4 id=numero_" . $i . " class='race_number' race_number=" . $row->race_number . ">N° de course : " . $numero ."</h4>";
                    $html[] = $this->sml_add_input('Nom *','text','last_name',$i, $row->last_name, '');
                    $html[] = $this->sml_add_input('Prénom *','text','first_name',$i, $row->first_name, '');
                    $html[] = $this->sml_add_input('Date de naissance *','date','birth_date',$i,$row->birth_date, '');;
                    $html[] = $this->sml_add_input('Email *','text','email',$i,$row->email, '');
                    $html[] = $this->sml_add_input('Téléphone *','text','phone_number',$i,$row->phone_number, '');
                    $html[] = $this->sml_add_input('Adresse *', 'text', 'adress', $i, $row->adress, '');
                    $html[] = $this->sml_add_input('Code postal *', 'text', 'cedex', $i, $row->cedex, '');
                    $html[] = $this->sml_add_input('Ville *', 'text', 'city', $i, $row->city, '');
                    $html[] = $this->sml_add_input('Pays *', 'select', 'country', $i, $row->country, '');                    
                    $html[] = $this->sml_add_input("Assureur, numéro d'assurance *", 'text', 'insurance', $i, $row->insurance, '');
                    $html[] = $this->sml_add_input('Numéro de licence *', 'text', 'ffm_licence_number', $i, $row->ffm_licence_number, '');
                    $html[] = $this->sml_add_input('Numéro de CASM *', 'text', 'casm_number', $i, $row->casm_number, '');
                    $html[] = $this->sml_add_input("Club *", 'text', 'team', $i, $row->team, '');
                    $html[] = $this->sml_add_input("Catégorie *", 'select', 'category', $i, $row->category, $row->eng_type);
                    $html[] = $this->sml_add_input('Marque du véhicule *', 'text', 'vh_brand', $i, $row->vh_brand, ''); 
                    $html[] = $this->sml_add_input('Modèle de la moto *', 'text', 'vh_type', $i, $row->vh_type, '');
                    $html[] = $this->sml_add_input('Cylindrée (cm3) *', 'text', 'vh_displacement', $i, $row->vh_displacement, '');
                    $html[] = $this->sml_add_input('Année du véhicule *', 'date', 'vh_year', $i, $row->vh_year, '');
                    $html[] = $this->sml_add_input('Numéro de cadre *', 'text', 'vh_chassis_number', $i, $row->vh_chassis_number, '');
                    $html[] = "<label class='save_status' status='default'></label>";
                    $html[] = "<input type='button' status='true' id='save" . "' style='float: right;' value='Enregistrer' />";
                    $html[] = "</form>";
                    $html[] = "</div>";
                    $html[] = "<div style='clear: both;'></div>";
                    
                    //On récupère la clé de la ligne précédente
                    $previous_row_key = $row->team_key; 
                }
                $html[] = "</div>";//On termine le dernier surengagement
            }
            
            //On ajoute le script
            $dirname = plugins_url( '/js/formulairesPilotes.js', __FILE__ );
            $html[] = "<script src='{$dirname}'></script>"; //On ajoute le js
            
            //On renvois les informations au client            
            echo implode('',$html);
            exit;
        }
    }
    
    /**
     * 
     * @param string $title Le titre qu'aura l'entrée
     * @param string $type Le type de l'entrée (selecteur ou input)
     * @param string $id L'id de l'entrée (correspond à l'id de la base)
     * @param string $index L'index de l'engagement, sur la page
     * @param string $value La valeur (si déjà saisie) correspondant à l'engagement, dans la base
     * @param string $nat Si c'est un engagement quad, moto, solo, duo, 85... (utile seulement pour la création du sélecteur de catégorie)
     * @return string
     */
    public function sml_add_input($title, $type, $id, $index, $value, $nat){
        $html = array();
        //L'input
        if($type != 'select'){ 
             //Le titre de l'input
            $html[] = "<label id='titre_" . $id . $index . "' class='titre_"
                    . $id . " sml_titre' for='in_" . $id . $index
                    . "'>" . $title . "</label>";
            //Le logo "ok"
            $html[] = "<label id='valid_" . $id . $index . "' class='valid_"
                    . $id . " sml_valid' for='in_" . $id . $index
                    . "'>" . "</label>";
            $html[] = "<input id='in_" . $id . $index . "' class='in_"
                    . $id . " sml_input' type='" . $type . "' value='" . $value . "'/>";
        } else if($id == 'country'){
            $html[] = "<select id='sel_" . $id . $index . "' class='sel_"
                    . $id . " sml_select' type='" . $type . "'>"
                    . $this->get_country_list($value) . '</select>';            
        } else if($id == 'category'){
            $html[] = "<select id='sel_" . $id . $index . "' class='sel_"
                    . $id . " sml_select' type='" . $type . "'>"
                    . $this->get_category_list($nat, $value) . '</select>';
            
        }
        
        $text = implode('',$html);
        return ($text);
    }
    
    public function sml_add_select_option($val, $text, $val_selected){
        if($val == $val_selected){
            $str = "<option value='" . $val . "' selected='selected'>" . $text ."</option>";
        }
        else {
            $str = "<option value='" . $val . "'>" . $text ."</option>";
        }
        return $str;
    }
    
    /**
     * Permet de renvoyer le contenu d'un selecteur pour 
     * @param type $nat La nature de l'engagement (duo, solo, moto, quad, ...)
     */
    public function get_category_list($nat, $value){
        $html = array();
        if(strpos($nat, '85') !== false){ //Catégorie pour les 85cm3
            $html[] = $this->sml_add_select_option('85','85 cm3',$value);
        } else if(strpos($nat, 'quad') !== false) { //Catégories pour les quads
            $html[] = $this->sml_add_select_option('open','Quad - nationale',$value);
        } else if(strpos($nat, 'solo') !== false) { //Si ni quad ni 85, on prend les solos
            $html[] = $this->sml_add_select_option('-1',' -- Catégorie',$value);
            $html[] = $this->sml_add_select_option('125_250','125 2t & 250 4t',$value);
            $html[] = $this->sml_add_select_option('open','Open',$value);            
            $html[] = $this->sml_add_select_option('veteran','Vétéran',$value);
        } else if(strpos($nat, 'duo') !== false) { //Si ni quad ni 85, on prend les duos
            $html[] = $this->sml_add_select_option('-1',' -- Catégorie',$value);
            $html[] = $this->sml_add_select_option('125_250','125 2t & 250 4t',$value); 
            $html[] = $this->sml_add_select_option('open','Open',$value);            
            $html[] = $this->sml_add_select_option('veteran','Vétéran',$value);
            $html[] = $this->sml_add_select_option('parent_enfant','Parent/enfant',$value);
        }
        
        return implode('',$html);
    }
    
    /**
     * Renvois le contenu d'un selecteur, contenant tous les pays
     * @return string 
     */
    public function get_country_list($value){
        $html = array();
        $html[] = $this->sml_add_select_option("-1"," -- Pays",$value);
        $html[] = $this->sml_add_select_option("AF","Afghanistan",$value);
        $html[] = $this->sml_add_select_option("AX","Åland Islands",$value);
        $html[] = $this->sml_add_select_option("AL","Albania",$value);
        $html[] = $this->sml_add_select_option("DZ","Algeria",$value);
        $html[] = $this->sml_add_select_option("AS","American Samoa",$value);
        $html[] = $this->sml_add_select_option("AD","Andorra",$value);
        $html[] = $this->sml_add_select_option("AO","Angola",$value);
        $html[] = $this->sml_add_select_option("AI","Anguilla",$value);
        $html[] = $this->sml_add_select_option("AQ","Antarctica",$value);
        $html[] = $this->sml_add_select_option("AG","Antigua and Barbuda",$value);
        $html[] = $this->sml_add_select_option("AR","Argentina",$value);
        $html[] = $this->sml_add_select_option("AM","Armenia",$value);
        $html[] = $this->sml_add_select_option("AW","Aruba",$value);
        $html[] = $this->sml_add_select_option("AU","Australia",$value);
        $html[] = $this->sml_add_select_option("AT","Austria",$value);
        $html[] = $this->sml_add_select_option("AZ","Azerbaijan",$value);
        $html[] = $this->sml_add_select_option("BS","Bahamas",$value);
        $html[] = $this->sml_add_select_option("BH","Bahrain",$value);
        $html[] = $this->sml_add_select_option("BD","Bangladesh",$value);
        $html[] = $this->sml_add_select_option("BB","Barbados",$value);
        $html[] = $this->sml_add_select_option("BY","Belarus",$value);
        $html[] = $this->sml_add_select_option("BE","Belgium",$value);
        $html[] = $this->sml_add_select_option("BZ","Belize",$value);
        $html[] = $this->sml_add_select_option("BJ","Benin",$value);
        $html[] = $this->sml_add_select_option("BM","Bermuda",$value);
        $html[] = $this->sml_add_select_option("BT","Bhutan",$value);
        $html[] = $this->sml_add_select_option("BO","Bolivia, Plurinational State of",$value);
        $html[] = $this->sml_add_select_option("BQ","Bonaire, Sint Eustatius and Saba",$value);
        $html[] = $this->sml_add_select_option("BA","Bosnia and Herzegovina",$value);
        $html[] = $this->sml_add_select_option("BW","Botswana",$value);
        $html[] = $this->sml_add_select_option("BV","Bouvet Island",$value);
        $html[] = $this->sml_add_select_option("BR","Brazil",$value);
        $html[] = $this->sml_add_select_option("IO","British Indian Ocean Territory",$value);
        $html[] = $this->sml_add_select_option("BN","Brunei Darussalam",$value);
        $html[] = $this->sml_add_select_option("BG","Bulgaria",$value);
        $html[] = $this->sml_add_select_option("BF","Burkina Faso",$value);
        $html[] = $this->sml_add_select_option("BI","Burundi",$value);
        $html[] = $this->sml_add_select_option("KH","Cambodia",$value);
        $html[] = $this->sml_add_select_option("CM","Cameroon",$value);
        $html[] = $this->sml_add_select_option("CA","Canada",$value);
        $html[] = $this->sml_add_select_option("CV","Cape Verde",$value);
        $html[] = $this->sml_add_select_option("KY","Cayman Islands",$value);
        $html[] = $this->sml_add_select_option("CF","Central African Republic",$value);
        $html[] = $this->sml_add_select_option("TD","Chad",$value);
        $html[] = $this->sml_add_select_option("CL","Chile",$value);
        $html[] = $this->sml_add_select_option("CN","China",$value);
        $html[] = $this->sml_add_select_option("CX","Christmas Island",$value);
        $html[] = $this->sml_add_select_option("CC","Cocos (Keeling) Islands",$value);
        $html[] = $this->sml_add_select_option("CO","Colombia",$value);
        $html[] = $this->sml_add_select_option("KM","Comoros",$value);
        $html[] = $this->sml_add_select_option("CG","Congo",$value);
        $html[] = $this->sml_add_select_option("CD","Congo, the Democratic Republic of the",$value);
        $html[] = $this->sml_add_select_option("CK","Cook Islands",$value);
        $html[] = $this->sml_add_select_option("CR","Costa Rica",$value);
        $html[] = $this->sml_add_select_option("CI","Côte d'Ivoire",$value);
        $html[] = $this->sml_add_select_option("HR","Croatia",$value);
        $html[] = $this->sml_add_select_option("CU","Cuba",$value);
        $html[] = $this->sml_add_select_option("CW","Curaçao",$value);
        $html[] = $this->sml_add_select_option("CY","Cyprus",$value);
        $html[] = $this->sml_add_select_option("CZ","Czech Republic",$value);
        $html[] = $this->sml_add_select_option("DK","Denmark",$value);
        $html[] = $this->sml_add_select_option("DJ","Djibouti",$value);
        $html[] = $this->sml_add_select_option("DM","Dominica",$value);
        $html[] = $this->sml_add_select_option("DO","Dominican Republic",$value);
        $html[] = $this->sml_add_select_option("EC","Ecuador",$value);
        $html[] = $this->sml_add_select_option("EG","Egypt",$value);
        $html[] = $this->sml_add_select_option("SV","El Salvador",$value);
        $html[] = $this->sml_add_select_option("GQ","Equatorial Guinea",$value);
        $html[] = $this->sml_add_select_option("ER","Eritrea",$value);
        $html[] = $this->sml_add_select_option("EE","Estonia",$value);
        $html[] = $this->sml_add_select_option("ET","Ethiopia",$value);
        $html[] = $this->sml_add_select_option("FK","Falkland Islands (Malvinas)",$value);
        $html[] = $this->sml_add_select_option("FO","Faroe Islands",$value);
        $html[] = $this->sml_add_select_option("FJ","Fiji",$value);
        $html[] = $this->sml_add_select_option("FI","Finland",$value);
        $html[] = $this->sml_add_select_option("FR","France",$value);
        $html[] = $this->sml_add_select_option("GF","French Guiana",$value);
        $html[] = $this->sml_add_select_option("PF","French Polynesia",$value);
        $html[] = $this->sml_add_select_option("TF","French Southern Territories",$value);
        $html[] = $this->sml_add_select_option("GA","Gabon",$value);
        $html[] = $this->sml_add_select_option("GM","Gambia",$value);
        $html[] = $this->sml_add_select_option("GE","Georgia",$value);
        $html[] = $this->sml_add_select_option("DE","Germany",$value);
        $html[] = $this->sml_add_select_option("GH","Ghana",$value);
        $html[] = $this->sml_add_select_option("GI","Gibraltar",$value);
        $html[] = $this->sml_add_select_option("GR","Greece",$value);
        $html[] = $this->sml_add_select_option("GL","Greenland",$value);
        $html[] = $this->sml_add_select_option("GD","Grenada",$value);
        $html[] = $this->sml_add_select_option("GP","Guadeloupe",$value);
        $html[] = $this->sml_add_select_option("GU","Guam",$value);
        $html[] = $this->sml_add_select_option("GT","Guatemala",$value);
        $html[] = $this->sml_add_select_option("GG","Guernsey",$value);
        $html[] = $this->sml_add_select_option("GN","Guinea",$value);
        $html[] = $this->sml_add_select_option("GW","Guinea-Bissau",$value);
        $html[] = $this->sml_add_select_option("GY","Guyana",$value);
        $html[] = $this->sml_add_select_option("HT","Haiti",$value);
        $html[] = $this->sml_add_select_option("HM","Heard Island and McDonald Islands",$value);
        $html[] = $this->sml_add_select_option("VA","Holy See (Vatican City State)",$value);
        $html[] = $this->sml_add_select_option("HN","Honduras",$value);
        $html[] = $this->sml_add_select_option("HK","Hong Kong",$value);
        $html[] = $this->sml_add_select_option("HU","Hungary",$value);
        $html[] = $this->sml_add_select_option("IS","Iceland",$value);
        $html[] = $this->sml_add_select_option("IN","India",$value);
        $html[] = $this->sml_add_select_option("ID","Indonesia",$value);
        $html[] = $this->sml_add_select_option("IR","Iran, Islamic Republic of",$value);
        $html[] = $this->sml_add_select_option("IQ","Iraq",$value);
        $html[] = $this->sml_add_select_option("IE","Ireland",$value);
        $html[] = $this->sml_add_select_option("IM","Isle of Man",$value);
        $html[] = $this->sml_add_select_option("IL","Israel",$value);
        $html[] = $this->sml_add_select_option("IT","Italy",$value);
        $html[] = $this->sml_add_select_option("JM","Jamaica",$value);
        $html[] = $this->sml_add_select_option("JP","Japan",$value);
        $html[] = $this->sml_add_select_option("JE","Jersey",$value);
        $html[] = $this->sml_add_select_option("JO","Jordan",$value);
        $html[] = $this->sml_add_select_option("KZ","Kazakhstan",$value);
        $html[] = $this->sml_add_select_option("KE","Kenya",$value);
        $html[] = $this->sml_add_select_option("KI","Kiribati",$value);
        $html[] = $this->sml_add_select_option("KP","Korea, Democratic People's Republic of",$value);
        $html[] = $this->sml_add_select_option("KR","Korea, Republic of",$value);
        $html[] = $this->sml_add_select_option("KW","Kuwait",$value);
        $html[] = $this->sml_add_select_option("KG","Kyrgyzstan",$value);
        $html[] = $this->sml_add_select_option("LA","Lao People's Democratic Republic",$value);
        $html[] = $this->sml_add_select_option("LV","Latvia",$value);
        $html[] = $this->sml_add_select_option("LB","Lebanon",$value);
        $html[] = $this->sml_add_select_option("LS","Lesotho",$value);
        $html[] = $this->sml_add_select_option("LR","Liberia",$value);
        $html[] = $this->sml_add_select_option("LY","Libya",$value);
        $html[] = $this->sml_add_select_option("LI","Liechtenstein",$value);
        $html[] = $this->sml_add_select_option("LT","Lithuania",$value);
        $html[] = $this->sml_add_select_option("LU","Luxembourg",$value);
        $html[] = $this->sml_add_select_option("MO","Macao",$value);
        $html[] = $this->sml_add_select_option("MK","Macedonia, the former Yugoslav Republic of",$value);
        $html[] = $this->sml_add_select_option("MG","Madagascar",$value);
        $html[] = $this->sml_add_select_option("MW","Malawi",$value);
        $html[] = $this->sml_add_select_option("MY","Malaysia",$value);
        $html[] = $this->sml_add_select_option("MV","Maldives",$value);
        $html[] = $this->sml_add_select_option("ML","Mali",$value);
        $html[] = $this->sml_add_select_option("MT","Malta",$value);
        $html[] = $this->sml_add_select_option("MH","Marshall Islands",$value);
        $html[] = $this->sml_add_select_option("MQ","Martinique",$value);
        $html[] = $this->sml_add_select_option("MR","Mauritania",$value);
        $html[] = $this->sml_add_select_option("MU","Mauritius",$value);
        $html[] = $this->sml_add_select_option("YT","Mayotte",$value);
        $html[] = $this->sml_add_select_option("MX","Mexico",$value);
        $html[] = $this->sml_add_select_option("FM","Micronesia, Federated States of",$value);
        $html[] = $this->sml_add_select_option("MD","Moldova, Republic of",$value);
        $html[] = $this->sml_add_select_option("MC","Monaco",$value);
        $html[] = $this->sml_add_select_option("MN","Mongolia",$value);
        $html[] = $this->sml_add_select_option("ME","Montenegro",$value);
        $html[] = $this->sml_add_select_option("MS","Montserrat",$value);
        $html[] = $this->sml_add_select_option("MA","Morocco",$value);
        $html[] = $this->sml_add_select_option("MZ","Mozambique",$value);
        $html[] = $this->sml_add_select_option("MM","Myanmar",$value);
        $html[] = $this->sml_add_select_option("NA","Namibia",$value);
        $html[] = $this->sml_add_select_option("NR","Nauru",$value);
        $html[] = $this->sml_add_select_option("NP","Nepal",$value);
        $html[] = $this->sml_add_select_option("NL","Netherlands",$value);
        $html[] = $this->sml_add_select_option("NC","New Caledonia",$value);
        $html[] = $this->sml_add_select_option("NZ","New Zealand",$value);
        $html[] = $this->sml_add_select_option("NI","Nicaragua",$value);
        $html[] = $this->sml_add_select_option("NE","Niger",$value);
        $html[] = $this->sml_add_select_option("NG","Nigeria",$value);
        $html[] = $this->sml_add_select_option("NU","Niue",$value);
        $html[] = $this->sml_add_select_option("NF","Norfolk Island",$value);
        $html[] = $this->sml_add_select_option("MP","Northern Mariana Islands",$value);
        $html[] = $this->sml_add_select_option("NO","Norway",$value);
        $html[] = $this->sml_add_select_option("OM","Oman",$value);
        $html[] = $this->sml_add_select_option("PK","Pakistan",$value);
        $html[] = $this->sml_add_select_option("PW","Palau",$value);
        $html[] = $this->sml_add_select_option("PS","Palestinian Territory, Occupied",$value);
        $html[] = $this->sml_add_select_option("PA","Panama",$value);
        $html[] = $this->sml_add_select_option("PG","Papua New Guinea",$value);
        $html[] = $this->sml_add_select_option("PY","Paraguay",$value);
        $html[] = $this->sml_add_select_option("PE","Peru",$value);
        $html[] = $this->sml_add_select_option("PH","Philippines",$value);
        $html[] = $this->sml_add_select_option("PN","Pitcairn",$value);
        $html[] = $this->sml_add_select_option("PL","Poland",$value);
        $html[] = $this->sml_add_select_option("PT","Portugal",$value);
        $html[] = $this->sml_add_select_option("PR","Puerto Rico",$value);
        $html[] = $this->sml_add_select_option("QA","Qatar",$value);
        $html[] = $this->sml_add_select_option("RE","Réunion",$value);
        $html[] = $this->sml_add_select_option("RO","Romania",$value);
        $html[] = $this->sml_add_select_option("RU","Russian Federation",$value);
        $html[] = $this->sml_add_select_option("RW","Rwanda",$value);
        $html[] = $this->sml_add_select_option("BL","Saint Barthélemy",$value);
        $html[] = $this->sml_add_select_option("SH","Saint Helena, Ascension and Tristan da Cunha",$value);
        $html[] = $this->sml_add_select_option("KN","Saint Kitts and Nevis",$value);
        $html[] = $this->sml_add_select_option("LC","Saint Lucia",$value);
        $html[] = $this->sml_add_select_option("MF","Saint Martin (French part)",$value);
        $html[] = $this->sml_add_select_option("PM","Saint Pierre and Miquelon",$value);
        $html[] = $this->sml_add_select_option("VC","Saint Vincent and the Grenadines",$value);
        $html[] = $this->sml_add_select_option("WS","Samoa",$value);
        $html[] = $this->sml_add_select_option("SM","San Marino",$value);
        $html[] = $this->sml_add_select_option("ST","Sao Tome and Principe",$value);
        $html[] = $this->sml_add_select_option("SA","Saudi Arabia",$value);
        $html[] = $this->sml_add_select_option("SN","Senegal",$value);
        $html[] = $this->sml_add_select_option("RS","Serbia",$value);
        $html[] = $this->sml_add_select_option("SC","Seychelles",$value);
        $html[] = $this->sml_add_select_option("SL","Sierra Leone",$value);
        $html[] = $this->sml_add_select_option("SG","Singapore",$value);
        $html[] = $this->sml_add_select_option("SX","Sint Maarten (Dutch part)",$value);
        $html[] = $this->sml_add_select_option("SK","Slovakia",$value);
        $html[] = $this->sml_add_select_option("SI","Slovenia",$value);
        $html[] = $this->sml_add_select_option("SB","Solomon Islands",$value);
        $html[] = $this->sml_add_select_option("SO","Somalia",$value);
        $html[] = $this->sml_add_select_option("ZA","South Africa",$value);
        $html[] = $this->sml_add_select_option("GS","South Georgia and the South Sandwich Islands",$value);
        $html[] = $this->sml_add_select_option("SS","South Sudan",$value);
        $html[] = $this->sml_add_select_option("ES","Spain",$value);
        $html[] = $this->sml_add_select_option("LK","Sri Lanka",$value);
        $html[] = $this->sml_add_select_option("SD","Sudan",$value);
        $html[] = $this->sml_add_select_option("SR","Suriname",$value);
        $html[] = $this->sml_add_select_option("SJ","Svalbard and Jan Mayen",$value);
        $html[] = $this->sml_add_select_option("SZ","Swaziland",$value);
        $html[] = $this->sml_add_select_option("SE","Sweden",$value);
        $html[] = $this->sml_add_select_option("CH","Switzerland",$value);
        $html[] = $this->sml_add_select_option("SY","Syrian Arab Republic",$value);
        $html[] = $this->sml_add_select_option("TW","Taiwan, Province of China",$value);
        $html[] = $this->sml_add_select_option("TJ","Tajikistan",$value);
        $html[] = $this->sml_add_select_option("TZ","Tanzania, United Republic of",$value);
        $html[] = $this->sml_add_select_option("TH","Thailand",$value);
        $html[] = $this->sml_add_select_option("TL","Timor-Leste",$value);
        $html[] = $this->sml_add_select_option("TG","Togo",$value);
        $html[] = $this->sml_add_select_option("TK","Tokelau",$value);
        $html[] = $this->sml_add_select_option("TO","Tonga",$value);
        $html[] = $this->sml_add_select_option("TT","Trinidad and Tobago",$value);
        $html[] = $this->sml_add_select_option("TN","Tunisia",$value);
        $html[] = $this->sml_add_select_option("TR","Turkey",$value);
        $html[] = $this->sml_add_select_option("TM","Turkmenistan",$value);
        $html[] = $this->sml_add_select_option("TC","Turks and Caicos Islands",$value);
        $html[] = $this->sml_add_select_option("TV","Tuvalu",$value);
        $html[] = $this->sml_add_select_option("UG","Uganda",$value);
        $html[] = $this->sml_add_select_option("UA","Ukraine",$value);
        $html[] = $this->sml_add_select_option("AE","United Arab Emirates",$value);
        $html[] = $this->sml_add_select_option("GB","United Kingdom",$value);
        $html[] = $this->sml_add_select_option("US","United States",$value);
        $html[] = $this->sml_add_select_option("UM","United States Minor Outlying Islands",$value);
        $html[] = $this->sml_add_select_option("UY","Uruguay",$value);
        $html[] = $this->sml_add_select_option("UZ","Uzbekistan",$value);
        $html[] = $this->sml_add_select_option("VU","Vanuatu",$value);
        $html[] = $this->sml_add_select_option("VE","Venezuela, Bolivarian Republic of",$value);
        $html[] = $this->sml_add_select_option("VN","Viet Nam",$value);
        $html[] = $this->sml_add_select_option("VG","Virgin Islands, British",$value);
        $html[] = $this->sml_add_select_option("VI","Virgin Islands, U.S.",$value);
        $html[] = $this->sml_add_select_option("WF","Wallis and Futuna",$value);
        $html[] = $this->sml_add_select_option("EH","Western Sahara",$value);
        $html[] = $this->sml_add_select_option("YE","Yemen",$value);
        $html[] = $this->sml_add_select_option("ZM","Zambia",$value);
        $html[] = $this->sml_add_select_option("ZW","Zimbabwe",$value); 
        return implode('',$html);
    }
    
    public function sml_save_engagement(){
        $return = array('success' => false);
        global $wpdb;
        
        // ...Call the database connection settings
        //error_log(get_home_path() . "wp-config.php",0);

        // ...Connect to WP database
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        //On commence par récupérer le contenu du post
        $id = $_POST['id'];
        $last_name = $mysqli->real_escape_string($_POST['last_name']);
        $first_name = $mysqli->real_escape_string($_POST['first_name']);
        $birth_date = $mysqli->real_escape_string($_POST['birth_date']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $phone_number = $mysqli->real_escape_string($_POST['phone_number']);
        $adress = $mysqli->real_escape_string($_POST['adress']);
        $cedex = $mysqli->real_escape_string($_POST['cedex']);
        $city = $mysqli->real_escape_string($_POST['city']);
        $country = $mysqli->real_escape_string($_POST['country']);                    
        $insurance = $mysqli->real_escape_string($_POST['insurance']);
        $ffm_licence_number = $mysqli->real_escape_string($_POST['ffm_licence_number']);
        $casm_number = $mysqli->real_escape_string($_POST['casm_number']);
        $team = $mysqli->real_escape_string($_POST['team']);
        $category = $mysqli->real_escape_string($_POST['category']);
        $vh_brand = $mysqli->real_escape_string($_POST['vh_brand']); 
        $vh_type = $mysqli->real_escape_string($_POST['vh_type']);
        $vh_displacement = $mysqli->real_escape_string($_POST['vh_displacement']);
        $vh_year = $mysqli->real_escape_string($_POST['vh_year']);
        $vh_chassis_number = $mysqli->real_escape_string($_POST['vh_chassis_number']);
        $race_number = $_POST['race_number'];
        $race_name = $_POST['race_name'];
        $team_key = $_POST['team_key'];
        $eng_type = $_POST['eng_type'];
        
        //On regarde le numéro
        if($race_number == 0){
            //Pas de numéro attribué. On regarde s'il n'a pas déjà un coéquipier avec un numéro. 
            //Sinon, on recherche le plus grand numéro de sa catégorie (+1)
            //Si c'est le premier de sa catégorie), on donne le numéro manuellement
            //ATTENTION : il faut regarder dans une même course
            $req1 = $wpdb->get_results( //Le plus grand numéro de son équipage                    
                        "select max(race_number) as max_num_cat from wp_pilotes "
                    .   "where team_key = '{$team_key}' "
                    .   "and race_number != 0 "
                    .   "and race_name = '{$race_name}' " 
            );
            if(empty($req)){ //Si pas de numéro d'équipe, on cherche le plus grand numéro de la catégorie
                $req2 = $wpdb->get_results( //Le plus grand numéro de sa catégorie
                        "select max(race_number)  as max_num_cat from wp_pilotes "
                    .   "where eng_type = '{$eng_type}' and race_name = '{$race_name}' " 
                );
            }  
            error_log("select max(race_number)  as max_num_cat from wp_pilotes "
                    .   "where eng_type = '{$eng_type}' and race_name = '{$race_name}' ",0);
            $max = 0;
            if(!empty($req2)){ //Si la requête nous retourne quelque chose
                error_log("Requête 'plus grand numéro de la catégorie' non vide",0);
                foreach($req2 as $row){ //On recherche la plus grande valeur
                    error_log($row->max_num_cat,0);
                    $max = ($row == null) ? $max : max($max,$row->max_num_cat) ;
                }
            }
           
            if($max == 0){ //Si on a récupéré aucun numéro, c'est qu'on est le premier de la catégorie
                if(strpos($eng_type, '85') !== false){ //Catégorie pour les 85cm3
                    $race_number = 123;
                } else if(strpos($eng_type, 'quad') !== false) { //Catégories pour les quads
                    $race_number = 4;
                } else if(strpos($eng_type, 'solo') !== false) { //Si ni quad ni 85, on prend les solos
                    $race_number = 153;
                } else if(strpos($eng_type, 'duo') !== false) { //Si ni quad ni 85, on prend les duos
                    $race_number = 4;
                }
            } else $race_number = $max + 1; //Sinon, on prend le premier numéro qui suit 
        }
        
                //On fait l'insertion
        $result = $wpdb->update( 
            'wp_pilotes', 
            array( 
                'race_number' => $race_number,
                'last_name' => $last_name,
                'first_name' => $first_name,
                'birth_date' => $birth_date,
                'email' => $email,
                'phone_number' => $phone_number,
                'adress' => $adress,
                'cedex' => $cedex,
                'city' => $city,
                'country' => $country,                    
                'insurance' => $insurance,
                'ffm_licence_number' => $ffm_licence_number,
                'casm_number' => $casm_number,
                'team' => $team,
                'category' => $category,
                'vh_brand' => $vh_brand, 
                'vh_type' => $vh_type,
                'vh_displacement' => $vh_displacement,
                'vh_year' => $vh_year,
                'vh_chassis_number' => $vh_chassis_number,
            ), 
            array( 'id' =>  $id)
        );        
        
        //On prépare la réponse
        if($result !== false) $return['success'] = true;
        $return['race_number'] = $race_number;
	header('Content-Type: application/json');
        echo json_encode($return);

	exit;
    }
} 