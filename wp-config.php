<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

define('WP_MEMORY_LIMIT', '32M'); 
/* Multisite */
define('WP_ALLOW_MULTISITE', false);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'HX8^ml2o-b/JNf)[g^WDcX_UdyE[2A!|u*e(d%t%g?[RWnz+ueS_HtevEv&HrT0t');
define('SECURE_AUTH_KEY',  ']i7}7Gjel.mn{:QI9&7?6t WFhtw^r0H8[M8%J4n;!;cIH]* W:.V+4Jsl<PAjHT');
define('LOGGED_IN_KEY',    '}|kF_#8G#6igb#^_)-,74YKs(R6&;%Rv<+^QWHrV_G9`E}iB_PB||~].QjAjWy{U');
define('NONCE_KEY',        'r?{54O#(J<7RE:z_bq,jL(jwXKui;>(9FC7,jlKcGTVS+lNF9vYURtjpdDA,/vbL');
define('AUTH_SALT',        ';7G-Mt P5v?5>PERNDI?^Jb*2sf1~CfFwVWl o@rfO8!wpWZF;Va*~~nnGIx]dPJ');
define('SECURE_AUTH_SALT', 'doiVh#9{wKWAxNYFr`?0D^6}ZeWhW?-x+_,$U-kdD0y0AyB<uRWsOuE *YI_UAe)');
define('LOGGED_IN_SALT',   'Zs*-}b;|m}3>~i!%S4jp4K|a%.>GK#RyBd]lY{A-50(i/^(On:&1D}m1ZM7W4UJK');
define('NONCE_SALT',       '8{0XvJF`TkSdnVo%@i3~Lbxgi7jW+x}`CgQayFzk?rS?l}/:Ds07;bNF:aZpA$!S');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
