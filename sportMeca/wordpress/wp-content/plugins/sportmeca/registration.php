<?php 
/**
 * Classe permettant de customiser la page de connexion, en ajoutant certains champs (nom, prénom)
 */
class RegistrationPage
{
    public function __construct()
    {
        //On gère les hook
        add_action( 'register_form', array($this,'myplugin_register_form'));
        add_filter( 'registration_errors', array($this,'myplugin_registration_errors'), 10, 3 );
        add_action( 'user_register', array($this,'myplugin_user_register' ));
    }

    //Cette fonction permet de customiser le formulaire d'inscription
    public function myplugin_register_form() {
     $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
     $last = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';   
         ?>
         <p>
             <label for="last_name"><?php _e( 'Nom', 'mydomain' ) ?><br />
                 <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
         </p>
         <p>
             <label for="first_name"><?php _e( 'Prénom', 'mydomain' ) ?><br />
                 <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
         </p>
         <?php
     }

     //Fonction permettant de valider ou non la saisie
     public function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {   
         //On test le prénom
         if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
             $errors->add( 'first_name_error', __( '<strong>ERREUR</strong>: Merci de saisir votre nom.', 'mydomain' ) );
         }
         //On test le nom
         if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
             $errors->add( 'last_name_error', __( '<strong>ERREUR</strong>: Merci de saisir votre prénom.', 'mydomain' ) );
         }
         return $errors;
     }

     //Fonction permettant l'ajout des donné dans le méta user
     public function myplugin_user_register( $user_id ) {
         //On ajoute le prénom
         if ( ! empty( $_POST['first_name'] ) ) {
             update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
         }
         //On ajoute le nom
         if ( ! empty( $_POST['last_name'] ) ) {
             update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
         }
     }
}
?>