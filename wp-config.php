<?php
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'css-website' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'arSk71xJwyzbv4K3BAmp1D6ieXJCSZFFNHVinXvQ2WryZclcafL2i7w9VIPacBRI' );
define( 'SECURE_AUTH_KEY',  'zfuVcHvo9sgMhISiXAVGAR1jCaYgSQbNggZ6GE4lAgnnlH9trIO9tG2KQL3Ecd4Z' );
define( 'LOGGED_IN_KEY',    'eAXAYahhKBX2W7UB5NweX0YoEKtutkjkAEFZRJO53A9clnQ4dBxEAWYw2AyTKlB6' );
define( 'NONCE_KEY',        't9Zlyd5qFcyvzFVOkJUYq5hlwxQbbbLqyIGaTB3frf8BW7a4NsQZQvYH1N2Ro691' );
define( 'AUTH_SALT',        'yxkJ03Ewkxb2m8LUkCxS0DD1yFjZD2TTsmGMXMjOQ9u0r17tH4UWyfw7LQnRAcJN' );
define( 'SECURE_AUTH_SALT', 'Lg2plsBrcuoVerxu89ngtGm7fkT7TGGh1WurAHlQfrXYCXyml4yNUrPUvCe3xdS6' );
define( 'LOGGED_IN_SALT',   '5uJiJN61epLCpr1WhSSKemQLsoOXvWtPNZVEpsYgFPZw4dCCNBa6ZIcNYGVAxobg' );
define( 'NONCE_SALT',       'IPdXvgQTQytQ4P4fo7tiBENQuYkb7rCm9nAyAxIZ70Kl4TkbzvTB8Z0z0wrO7wJm' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
