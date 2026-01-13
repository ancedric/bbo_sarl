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
define( 'DB_NAME', 'bbo-db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'XNFhs[Ex{t)tPEV0?z|(dU{B/i[x#apxxawBsK1Q5bFiDKsD?T5nA#_i<oc_@|k)' );
define( 'SECURE_AUTH_KEY',  'Jz:M.L)kAE3?K/_g,V,v!oYQpxOO9ME`n8E/]L5Za`{k6O;+N4jHHGI?(~Y(x05R' );
define( 'LOGGED_IN_KEY',    '[mVDLfk?,OZPKr</x]wDv%9[id/>cYZzAHAL&U1Wb3#.}8QS6s!,j)FqE??0TQMS' );
define( 'NONCE_KEY',        'g@bxZ60?KJbO(T)RahVW3]bfW/!OTL.xl-%7}k Ew@H0|gGv#%L:&Kv g?vpq)~&' );
define( 'AUTH_SALT',        '6X-.F*/Vt=ISI/):xZWJE[(d<_jNKr1Yt1H-MbXG gT]RU2{g8db&:xp;_R+z^h~' );
define( 'SECURE_AUTH_SALT', 'X:P[=Wd--!&6xlz<m`i^<u~LG.D[VR`0Dx [oF6d#r1!xm5siMD2c0R>u9#-iB4F' );
define( 'LOGGED_IN_SALT',   'ky)B ePw0xh&aEUp[A}`)+5k]4>Rl!AW3aE+W[)JDfaMJ$zF,hIJ%gj-,Xf(q1OY' );
define( 'NONCE_SALT',       'tQN5Jlsu3mp9%h>^ZNdNwZ20RMDBw6V|#nTOf]Cc<mCX74}zrqLI4so}!I+C],kp' );
define('JWT_AUTH_SECRET_KEY', '1234567890azerty');
define('JWT_AUTH_CORS_ENABLE', true);
define('APP_WEBHOOK_SECRET', 'azertyuiop1234567890');




/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
