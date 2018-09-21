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
define('DB_NAME', 'ieslascholarship');

/** MySQL database username */
define('DB_USER', 'ieslascholarship');

/** MySQL database password */
define('DB_PASSWORD', 'ieslascholarship@123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '9e>U._dzTYOENHAlu}NG{sh Ho,g.eH8[vC|w.E#O1KNq|3-?3-)Fxuu18$7p+$S');
define('SECURE_AUTH_KEY',  '=j_!OgLk!<#?a$nO-[xCoPV*7O9u2/j0jD&SU_jzdP0c[pgWKVkBc:*Wjt1cE L6');
define('LOGGED_IN_KEY',    'x9cn)zU5|lvU39qp#gty,MB+.3V2h|VQO9w`6,4dP8Eq-_Dy_}?_e=WYe)O|_7w>');
define('NONCE_KEY',        'xzQ.F{*xEQu^v^tqFE *{^L3l@GEwM;O9(45_k}-I4J6Fb{K^H?3Z`s0pt2Rum}t');
define('AUTH_SALT',        ':?RcLa<RIXWojg0%D3yKKJC==WOi!Bf3&R{6=m@a9^@JK9%oDv$r6}VMH~TVY]gb');
define('SECURE_AUTH_SALT', 'OSZA=j*|vV<(cjm&:e2y*zo6_CxcP E35jZN<RA/;rBh8!QCC%<73r=H1?Is59y8');
define('LOGGED_IN_SALT',   'us{8w2>8ajhnu)f!cr !>COR;[DCO1?,~!v];A+E<{pUx}&z:bQ(g_[,,uDE10Yq');
define('NONCE_SALT',       '20lF*e),O/}S1LN,9?w[p8jkwUU3Dc3eK*aOR@U+<^_RsV1._@L3D |<+v>~/-<B');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'agri_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

error_reporting(0);
define('DISALLOW_FILE_MODS', true);