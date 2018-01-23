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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cfh+home');

/** MySQL database username */
define('DB_USER', 'cfh');

/** MySQL database password */
define('DB_PASSWORD', 'lom52day');

/** MySQL hostname */
define('DB_HOST', 'sql.mit.edu');

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
define('AUTH_KEY',         'yRD%~f+R~*~CYtA/qnOPz8<Ii&|=l2PnvOG7%wQ#8u?.xu$EcSJyV%Q-aM2|,kP5');
define('SECURE_AUTH_KEY',  'mKyk_~Y!l(4T)l;h!CzE9%1sRx:prox;}NJyHw8m^-n{jw$oD{+ofM>[-RK~bp8o');
define('LOGGED_IN_KEY',    '>@^eP,eMYr61gqRn7;g+RKJi-vB}^pI{/M^5P+z*MI~DQ1<3>57=NtoG[(i9G?mF');
define('NONCE_KEY',        'm=a7+^n/7f+k:fT%x=(PImm&FjuMkfdfD<[?``a;g)J,J?Nagv +:bVd1e6-3:Ft');
define('AUTH_SALT',        'hwIJ/J1,#S!<<9JL>e&pWn~2e8i-@7O4Yehm7%aWhH,pj1&uH**Rf+?vZO*az/XX');
define('SECURE_AUTH_SALT', '3_;hk4G0$sR3oCOex>{V>5=,;ff&7]+hgM@P_WUf$_{M!j4b90i)>HVu^$3{Sime');
define('LOGGED_IN_SALT',   '0nV+D+kY7)f$@7;dzA3N>yq[bMI-WJ<U0Xr/&UHNB.wx4.hy:o&VDF`liopiAR?Q');
define('NONCE_SALT',       '!1m/gk+9B3xsfNK-MJ)p[$GLZ+XhtN*gOqEkpG<~Um<<!nDKk8u}Vr6_K1yv~[DG');

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
