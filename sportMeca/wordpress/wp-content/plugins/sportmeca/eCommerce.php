<?php 

/**
 * Cette classe permet la gestion des évènements woocommerce
 */

class GestionECommerce {
     public function __construct()
    {
        add_action('woocommerce_payment_complete',array($this,'mysite_payed'));
        add_action( 'woocommerce_order_status_pending', array($this,'mysite_pending'));
        add_action( 'woocommerce_order_status_failed',  array($this,'mysite_failed'));
        add_action( 'woocommerce_order_status_on-hold',  array($this,'mysite_hold'));
        add_action( 'woocommerce_order_status_processing',  array($this,'mysite_processing'));
        add_action( 'woocommerce_order_status_completed',  array($this,'add_pilote')); //A utiliser pour dire que le paiement des engagements a été validé
        add_action( 'woocommerce_order_status_refunded',  array($this,'mysite_refunded'));
        add_action( 'woocommerce_order_status_cancelled',  array($this,'mysite_cancelled'));
    }
    
    public function mysite_payed($order_id){
        error_log("$order_id set PAYED by the user",0);
    }
    
    public function mysite_pending($order_id) {
        error_log("$order_id set to PENDING", 0);
    }
    
    public function mysite_failed($order_id) {
        error_log("$order_id set to FAILED", 0);
    }
    
    public function mysite_hold($order_id) {
        error_log("$order_id set to ON HOLD", 0);
    }
    
    public function mysite_processing($order_id) {
        error_log("$order_id set to PROCESSING", 0);
    }
    
    public function mysite_completed($order_id) {
        error_log("$order_id set to COMPLETED", 0);
    }
    
    public function mysite_refunded($order_id) {
        error_log("$order_id set to REFUNDED", 0);
    }
    
    public function mysite_cancelled($order_id) {
        error_log("$order_id set to CANCELLED", 0);
    }
    
    public function add_pilote($order_id){
        //On récupère le préfixe
        global $wpdb;
        $prefix = $wpdb->prefix;
        
        //On veut la liste des item commandés.      
        $results  = $wpdb->get_results(
            "select buyer_id, product_id, qty, post_excerpt as race_name from wp_posts
            inner join
            (
                    select wp_woocommerce_order_items.order_item_id as idt2, wp_woocommerce_order_itemmeta.meta_value as 'product_id' from wp_posts 
                    inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
                    left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
                    left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
                    where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_product_id' and wp_woocommerce_order_items.order_id = {$order_id}
            ) as t2
            on wp_posts.id = product_id
            inner join
            (
                    select wp_woocommerce_order_items.order_item_id as idt1, wp_postmeta.meta_value as \"buyer_id\", wp_woocommerce_order_itemmeta.meta_value as \"qty\"  from wp_posts 
                    inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
                    left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
                    left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
                    where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_qty'
            ) as t1
            on t1.idt1 = t2.idt2"
        );
        
        //Pour tous les items commandé, on a l'id de l'item, ainsi que la quantité        
        foreach($results as $row){
            //On récupère la catégorie
            $post_categories = get_the_terms ($row->product_id, 'product_cat');
            $categorie = '';            
            foreach($post_categories as $c){ //Parmis la liste de catégories, on récupère la catégorie
                $cat = get_category( $c );           
                if($cat->slug == 'solo-moto') {
                    $categorie = 'solo-moto';                    
                } 
                else if($cat->slug == 'duo-moto'){
                    $categorie = 'duo-moto';
                }
                else if($cat->slug == 'solo-quad'){
                    $categorie = 'solo-quad';
                }
                else if($cat->slug == 'duo-85cm'){
                    $categorie = 'duo-85cm';
                }
            }
            
            //On ajoute une ligne dans le log
            $log = "l'utilisateur n°" . $row->buyer_id . " a commandé " . $row->qty . " exemplaires du produit n°" . $row->product_id 
                    . ". Ce produit est de type : " . $categorie;
            error_log($log,0);
            
            //On répète l'insertion autant de fois que l'on a d'itération de ce produit
            for($i=1;$i<=$row->qty;$i++){
                $key = implode('', getdate()) . $i . 'categorie=' . $categorie . 'id=' . $row->buyer_id . 'product=' . $row->product_id;
                //On insère une ligne dans la base (en fonction de si c'est une moto, un quad, etc)
                if($categorie == 'duo-moto' || $categorie == 'duo-quad'){ //En fonction de la catégorie de l'engagement
                    $wpdb->insert( //Pilote n°1
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key
                        )
                    ); 
                    $wpdb->insert( //Pilote n°2
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key 
                        )
                    );
                } else if ($categorie == 'solo-moto' || $categorie == 'solo-quad'){ //On met un seul engagement
                    $wpdb->insert(  //Pilote n°1
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key                           
                        )
                    );  

                } else if($categorie == 'duo-85cm'){ //En fonction de la catégorie de l'engagement
                    $wpdb->insert( //Pilote n°1
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key
                        )
                    ); 
                    $wpdb->insert( //Pilote n°2
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key 
                        )
                    );$wpdb->insert( //Pilote n°3
                        'wp_pilotes', 
                        array( 
                            'buyer_id' => $row->buyer_id, 
                            'order_id' =>  $order_id,
                            'race_name' => $row->race_name,
                            'eng_type' => $categorie,
                            'team_key' => $key 
                        )
                    );
                }
            }
        }
    }
} 
?>