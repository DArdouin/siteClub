<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'sml_user');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'ZeF6beBhT6zdx4jm');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '1m`^a&vgE/+E{XEDW>l,<jxD^lsK7=F%B<:tK|PVq6]?1{^ol>;[>+wS>*!Fy&{[');
define('SECURE_AUTH_KEY',  'dhh?+HjM$kO3BoTp2L3ci(vW?]@}QgW*<Z|z{,D2E|^&RYCCkZE!sR&v-j^&8Ceb');
define('LOGGED_IN_KEY',    '<u)m6hR9iVSt0qR*J0Ph, `#t1*3|pl:$)@_OMQ |FDUG_.rW8k|$0:qUcA|.6;x');
define('NONCE_KEY',        '/6Rwe, KHUR?P#[4GWn,wktvq zvCYlG6GP-;V,BP4>mknF[u+?iz9}5sK<Qt]j7');
define('AUTH_SALT',        'goxYN0{o^z?_B&D-dgb]mi^=i)z8^MVs.Y,q|9{pwELBa&&nV062D-KjoBo2e^OF');
define('SECURE_AUTH_SALT', '=3O=w^kw#!wDlX_M^+Y:+8R|3mnV.-n!g~.-u/.?$bC2+yF9+M8/#T|~q b&-]>:');
define('LOGGED_IN_SALT',   'Y&`L8zJL^VOy,&1FXob9wj=y|tK7?flBazylO<9&)F~<s<q*L_+e:H#H8p,]*9n%');
define('NONCE_SALT',       'cH#6-bbnK/_Mm(I?c-*Hb#OuKrG<3uK?ucLIzbr;iPPK<k<7LYR(BKq*`-aM#{iW');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', true);
// EActiver l'enregistrement de débogage dans le fichier /wp-content/debug.log
define('WP_DEBUG_LOG', true);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
