<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           2.0.7
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 *
 * @since    2.0.0
 */
$plugin_name_plugin_data = get_file_data(
   __FILE__,
   array(
     'Version'    => 'Version',
     'TextDomain' => 'Text Domain',
     'PluginName' => 'Plugin Name'
   )
);
define( 'PLUGIN_NAME_VERSION',     $plugin_name_plugin_data['Version'] );
define( 'PLUGIN_NAME_TEXT_DOMAIN', $plugin_name_plugin_data['TextDomain'] );

/**
 * Couple of quick-access values to working out our root dir, both file system & URL access.
 *
 * @since    2.0.0
 */
define( 'PLUGIN_NAME_PATH',   __DIR__.DIRECTORY_SEPARATOR );
define( 'PLUGIN_NAME_URL',    plugins_url( '/', __FILE__ ) );

/**
  * Work out which environment we're running on.
  *
  * @since    2.0.0
  */
switch( $_SERVER['SERVER_NAME'] ) {
  // Development
  case 'localhost':
  case '127.0.0.1':    // deliberate fall thru
    // https://developer.wordpress.org/apis/wp-config-php/#wp-environment
    if( !defined('WP_ENVIRONMENT_TYPE') ){
      define( 'WP_ENVIRONMENT_TYPE',  'local' );
    }
    // https://developer.wordpress.org/reference/hooks/admin_notices/#comment-5163
    if( !defined('DISABLE_NAG_NOTICES') ){
      define( 'DISABLE_NAG_NOTICES',  true );
    }
    break;
  case 'development.PLUGIN_NAME': // Replace with your development URL
    if( !defined('WP_ENVIRONMENT_TYPE') ){
      define( 'WP_ENVIRONMENT_TYPE',  'development' );
    }
    if( !defined('DISABLE_NAG_NOTICES') ){
      define( 'DISABLE_NAG_NOTICES',  true );
    }
    break;
  case 'staging.PLUGIN_NAME': // Replace with your staging URL
    if( !defined('WP_ENVIRONMENT_TYPE') ){
      define( 'WP_ENVIRONMENT_TYPE',  'staging' );
    }
    if( !defined('DISABLE_NAG_NOTICES') ){
      define( 'DISABLE_NAG_NOTICES',  true );
    }
    break;
  default:
    if( !defined('WP_ENVIRONMENT_TYPE') ){
      define( 'WP_ENVIRONMENT_TYPE',  'production' );
    }
    define( 'FORCE_SSL_ADMIN',        true ); // https://developer.wordpress.org/apis/wp-config-php/#require-ssl-for-admin-and-logins
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
  Plugin_Name\Plugin_Name_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_plugin_name' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
  Plugin_Name\Plugin_Name_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

  $plugin = new Plugin_Name\Plugin_Name();
  $plugin->run();

}
run_plugin_name();
