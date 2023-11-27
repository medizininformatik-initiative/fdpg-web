<?php

define('DB_NAME', "root");

define('DB_USER', "root");

define('DB_PASSWORD', "kzq0ugqCgeLfDZExxLUvYWPDxl9oT5wg");

define('DB_HOST', "vr9kdd.stackhero-network.com");

define('DB_CHARSET', 'utf8');

define('DB_COLLATE', '');

/*
define('DB_NAME', getenv(FDPG_DB));

define('DB_USER', getenv(STACKHERO_MARIADB_USER));

define('DB_PASSWORD', getenv(STACKHERO_MARIADB_ROOT_PASSWORD));

define('DB_HOST', getenv(STACKHERO_MARIADB_HOST));

define('DB_CHARSET', 'utf8');

define('DB_COLLATE', '');

*/

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

define( 'AUTH_KEY',         ',fq{9)xS/wv7JX>?~Rbb*n1T{[h,t.Hi_Me>tT_4)<JP1-}60BQV5&&)@6)(=d2r' );

define( 'SECURE_AUTH_KEY',  'QzMPV4{|78e`W}1!uZ5ul)>%6183gu4>B)b|0LbSZTS!1*g2%kLn.%L4v~pe1$Lc' );

define( 'LOGGED_IN_KEY',    'lRb(d8.7F(]$WE6s&QD+I-kThJyn20&IgG}2.>ihq7aM,UQTZH:6z4Bt8{}4q272' );

define( 'NONCE_KEY',        'J,dZv?6=;`aljQ{YJ VP*JDs>x:t>WyG@A`Bm:bX3SZ9wkQSJ@4oHH%q;s80f7-;' );

define( 'AUTH_SALT',        'X5u3z=f[0g(oA7]{#<CCY sWomKF3Mm4CQDEztP.Hvp]|w7n#p}:^+([Q=rpMZ;K' );

define( 'SECURE_AUTH_SALT', 'hJ#u/3/T6QOm_!0LR3rdad&XO=Do~K[+o%0$e/7xJ#HAr(pAHg@X?TA#`E2]Lj$x' );

define( 'LOGGED_IN_SALT',   'A coT_a=|W!5hd3WlzO46CuY`3;yKanc()qhz$Y$<s:nnQ6 k4IX)Ky^dPj.1WUC' );

define( 'NONCE_SALT',       'kxA&1HR^yMmkS)}Vw:|kReWh+i@vxXo!g!az$O+?k{^+w]DTi@G7`4h 0^BA(u6>' );


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = '114wp_';


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

define( 'WP_DEBUG', true );


/* Add any custom values between this line and the "stop editing" line. */




define( 'SCRIPT_DEBUG', true );
define( 'WP_DEBUG_LOG', 'preliveforschenfuergesundheitde_202303130906_debug.log' );
define( 'WP_DEBUG_DISPLAY', true );
define( 'DISALLOW_FILE_EDIT', false );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

