<?php
define( 'WP_CACHE', true ); // Added by WP Rocket


//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */


 define('ALLOW_UNFILTERED_UPLOADS', true);

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "subateria_db" );

/** Database username */
define( 'DB_USER', "subateria_user" );

/** Database password */
define( 'DB_PASSWORD', "1R-l,qUv]BOx" );

/** Database hostname */
define( 'DB_HOST', "localhost" );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '-gI_%C!CW7MTXxbUh*D|M7Qw(Twl$eq*Y#Mx.:WE|U4p.MC 9ojKz?]ulw~,S=T5' );
define( 'SECURE_AUTH_KEY',  '0R(w|>-5tNJ}mr<Xcpk3KGX<$S/d E3T4CL@N_^(VQ[YE3YdIdoNM@3YN*@C8j:y' );
define( 'LOGGED_IN_KEY',    'MY%ZXq7sxFVcd+)|=E!AJ5[#W$C*&w}Y|U>(g:ak,u5A <D{s(qK~+<t}[!]bKP0' );
define( 'NONCE_KEY',        'L%[.z6bK+ bxgpO}~N^Y{#} T4G%Dk}QA:hZhm%MA_+dw4crv.Y+oOh?,};b#z2T' );
define( 'AUTH_SALT',        '#<aWciex)`Mqq0ah9k{pV K#KbnFVXgJ4i#Pd;F eV7i!#7bv(l8aUA_&db[S:Q0' );
define( 'SECURE_AUTH_SALT', 'BZ>B2+*JQ6N(F+0* `KL[OWySL=h0^Zn/&^BKU.5_<8+t=ii`?bZx.+7;1wX/43P' );
define( 'LOGGED_IN_SALT',   'EYU  :gLie?4tC:?.C*;;-tP<BI:r5)XVulXPvNUXsZ.&eQj#ey2U({%AZL=l*bI' );
define( 'NONCE_SALT',       'S[Oet-},^d+;}3jZlUuFJD:UN3rsx`aY=]1z716_U t`.T5j{{&.?-Nk}Yx+gXFw' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'sub_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_SITEURL', 'https://importadorasubateria.com/' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
