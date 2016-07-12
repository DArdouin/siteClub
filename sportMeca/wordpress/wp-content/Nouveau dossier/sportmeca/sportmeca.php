<?php 
/*
Plugin Name: Sport Méca : gestion
Description: Un plugin permettant l'inscription des utilisateurs sur notre site web
Version: 0.0.1 20160311
Author: ARDOUIN Damien
*/
include 'registration.php'; //Page servant à gérer l'inscription des utilisateurs sur le site

/*********************************************************************************************
*
//Déclarations
*
*********************************************************************************************/
$registrationForm = new RegistrationForm();

/*********************************************************************************************
*
//Fonctions
*
*********************************************************************************************/

?>


<?php
/*
Exemple d'utilisation d'un hook

add_filter('the_title','zero_modify_page_title',10,2); //On rajoute un hook sur les titre, qui appelle la fonction zero_modify_page_title

//Fonction de modification de titre
function zero_modify_page_title($title){
	return $title;
}*/
?>