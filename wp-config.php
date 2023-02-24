<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'virtapp_io');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'fuko09phsurxho');

/** MySQL hostname */
define('DB_HOST', 'kubedb:32341');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '6Y@M8ur.& /y[g0h]8cMZ43,@`87F5bSy%ffr-)x@-1,)I=Bt-lh u0vqnn=}h0J');
define('SECURE_AUTH_KEY',  'rxMUKCiA)>~2+wXbQMzQ7/XKkCQ=khQ.<-P4I)LB$CoU]n?1Le4lH!k{0T6>}[!%');
define('LOGGED_IN_KEY',    'yjLK&@Z4;n`}d[6H EfvZ^[;v}%aM{Fp#AP,hEg)O[Bm}KQWI.!~Aqe@k*nfy#Dz');
define('NONCE_KEY',        'QBclT!ec;;@p}$ih3kV*~w3n3-_^_%VcxS~6$bm/:i6_$_Z|mmHZA5k0J;Yrx^!w');
define('AUTH_SALT',        'c^J[u:2CF|F$Xgt,Kp{sP.a=!Nt>0WV&,2tfxpDthZ~J*3/jr~V=)+s^J$@eb!b2');
define('SECURE_AUTH_SALT', ')}#y6!f91BW#44B^PNa0,V?9zg15UBsD)+;Lsd++EBe1]@</c)y0uq7$O`,M+s2g');
define('LOGGED_IN_SALT',   'auwO0_T5E2U_um`U&]$yj-oQ-?4:X?4bbI_Z @ffKE^;;YOH<P,DlSs1>kjM>=(4');
define('NONCE_SALT',       'ueeMY#Mkn*`3<]|kQO9d1Wzh3a_>Njt3_93_6tQ6eA(u/kUKap]0~?Mryze..G6:');

/**#@-*/
/* Multisite */
/*define('WP_ALLOW_MULTISITE', true);


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define ('WP_MEMORY_LIMIT', '512M');

/* That's all, stop editing! Happy blogging. */

/*define('MULTISITE', true);
/*define('SUBDOMAIN_INSTALL', true);
/*define('DOMAIN_CURRENT_SITE', 'virtapp.io');
/*define('PATH_CURRENT_SITE', '/');
/*define('SITE_ID_CURRENT_SITE', 1);
/*define('BLOG_ID_CURRENT_SITE', 1);


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* Function which remove Plugin Update Notices*/
function disable_plugin_updates( $value ) {
   unset( $value->response['wordpress-https/wordpress-https.php'] );
   unset( $value->response['convertplug/convertplug.php'] );
   unset( $value->response['advanced-custom-fields-pro/acf.php'] );
   unset( $value->response['wp-content-copy-protection/wpccpl.php'] );
 return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

