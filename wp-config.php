<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tribus-lms' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         '-J5+5S^QD*dPv*{.l#pcQ70J@f=arEt/ =_@CB[ix>R|g`kUTZvk4e0g0S{0_qLS' );
define( 'SECURE_AUTH_KEY',  'EfydTO5TEe0Is}E+oR]s38`s>TBjEMy^&v-Rql?Wn&[2 o~O@TX}^iZH-SC~ 0VS' );
define( 'LOGGED_IN_KEY',    'tZD/p-&V 8^?M{E-o(;3.&UnS_Ukwm{Eko/[ab+Xg8)u?L&<[$25C2kec,djfZ!j' );
define( 'NONCE_KEY',        'tM~{ z,9uj@ncF`s_dRQeS$|5Xj:9z!a/}`eEB~/6IfQlK6ab!Cj{/@lTw^Db)yM' );
define( 'AUTH_SALT',        '66e&,mN6UC;ySPG+J#GU Z:6Y1Z:H OBe8U6,mBm7/t_>Y!gfz!W86YkXW%#2 O;' );
define( 'SECURE_AUTH_SALT', 'mUjVZ}SZOy}g,V*GvUVLmY[fB`9kf;63Y1i_@n<wv~#ryTui|03xY,ba9ZI{gfk?' );
define( 'LOGGED_IN_SALT',   '7NY:n,Yp=3.uc:IuEnH**y_4:?@*yzoiDv)iB%X/u#6B!&#dbF$9r9FXYp3LU2w=' );
define( 'NONCE_SALT',       '&=DYC#1zpgldHDr8wxv4MJSX1!{)w6e,rxw7pu48jwi^5cMLIphU.cGZlw.Drmy`' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tri_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
