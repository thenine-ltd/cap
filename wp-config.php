<?php
define( 'WP_CACHE', true ); // By Speed Optimizer by SiteGround

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dbcb2tamlgyywt' );

/** Database username */
define( 'DB_USER', 'ubkbrc3obdev6' );

/** Database password */
define( 'DB_PASSWORD', 'nblelmajvg7r' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'y7vjy3[>fqW8>%;4=bz8-bnVUaP%N_BEv2@x&MCItoI=!I5SG[[IbB( fV${<:ih' );
define( 'SECURE_AUTH_KEY',   'vy(0PX>pGG[eVR!A6yZmZl0XE>j.+N%n~U6,j2kv}Qlqz?!:@{2l:p[:!1tZtg7Z' );
define( 'LOGGED_IN_KEY',     'fc(iGr.l]Q[D+(L|*<g]k4A>(0JL2e_W[*,KjbP?$2{cdK)//#t7_&E>ur5</LT{' );
define( 'NONCE_KEY',         'kO).2I.4g8Dj6PUg7AnI3t$NY[tYEwzk.Q?{NHphSWmq1F3h: o73$K5G6guH$-H' );
define( 'AUTH_SALT',         'wYK,{xT]z^t)6t0S|0f1|#dS*`=E?b(*(+$<PBbIy[h&a1yFj/B]+%&lf/Np<)?,' );
define( 'SECURE_AUTH_SALT',  'w]K~/@CNY4wD&jjg;_nCfGq*Wsf 1~2eK9Jj0[}M}%wh8+;x1VG2FGBDSU-tZQ$w' );
define( 'LOGGED_IN_SALT',    'TWP?7X@^ITG>db}-{#*w-e:5kyG=ImEQ&[^SiOCe)#QfDLH=[jJKy@s9({c,+7<H' );
define( 'NONCE_SALT',        '<]%~jmt5mUwvVYD>zHenVO>VD*?qw-L4}J(@VQ.e46/7xD2w1SE%7|&SnK.*)K.+' );
define( 'WP_CACHE_KEY_SALT', 'jioI_|*Si:DQ[uR_Fu)*;E.13w|IY|(/Au$^0nB=2etINGF3AdHc%88Wq;0n.pCx' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'pco_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'AS3CF_SETTINGS', serialize( array(
	'provider' => 'aws',
	'access-key-id' => 'AKIA4Z7K5PXJFRUARNGW',
	'secret-access-key' => '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',
) ) );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
