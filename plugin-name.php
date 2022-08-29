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
 * Version:           2.0.0
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
 * Add some additional links underneath our plugin description,
 * linking back to the editor page
 *
 * @since   2.0.0
 * @access  public
 * @param   array       $links_array            An array of the plugin's metadata
 * @param   string      $plugin_file_name       Path to the plugin file
 * @param   array       $plugin_data            An array of plugin data
 * @param   string      $status                 Status of the plugin
 * @return  array       $links_array
 */
function plugin_name_links( $links_array, $plugin_file_name, $plugin_data, $status ){

  // Check should be unnecessary - but belt & braces etc
  if( basename( __FILE__ ) === basename($plugin_file_name) ){
    // Tack our Github branch onto the Version number (only works when plugin is activated, obviously)
    $git_branch = PluginName\Phil_Tanner_Admin::get_git_branch();
    $git_repo   = PluginName\Phil_Tanner_Admin::get_git_repo_url();
    $git_hash   = PluginName\Phil_Tanner_Admin::get_git_commit_hash();
    $git_date   = PluginName\Phil_Tanner_Admin::get_git_commit_date();

    if(
      $git_branch
      && $git_repo
      && $git_hash
      && $git_date
    ){
      $links_array[0] .= ' ('.sprintf(
        __(
          'Git Branch: '.
          '<a href="%s" target="_blank">%s<span class="dashicons-before dashicons-external"></span></a> '.
          '(Commit: <a href="%s" target="_blank">#%s, %s<span class="dashicons-before dashicons-external"></span></a>)',
          PLUGIN_NAME_TEXT_DOMAIN
        ),
        $git_repo.'/tree/'.$git_branch,
        $git_branch,
        $git_repo.'/commit/'.$git_hash,
        substr( $git_hash, 0, 6),
        PluginName\Phil_Tanner_Admin::print_wp_local_date_from( $git_date, get_option( 'date_format' ) )
      ) .')';
    }
  }

  return $links_array;
}
add_filter( 'plugin_row_meta', 'plugin_name_links', 10, 4 );

/**
 * Add a Settings link to the "plugin deactivate" area.
 *
 * @since   2.0.0
 * @access  public
 * @param   array       $links_array            An array of the plugin's metadata
 * @param   string      $plugin_file_name       Path to the plugin file
 * @param   array       $plugin_data            An array of plugin data
 * @param   string      $status                 Status of the plugin
 * @return  array       $links_array
*/
function plugin_name_action_links( $links_array, $plugin_file_name, $plugin_data, $status ){
  if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
    $settings_url = esc_url(
      add_query_arg(
        'page',
        'plugin_name-menu_admin', // Note - this needs to match the Menu Slug in admin/class-plugin-name-admin.php->add_menu_links()
        admin_url( 'admin.php' )
      )
    );
    // Using array_unshift to put it first in the array (before Deactivate) (unlike plugin_name_links above )
    array_unshift( $links_array, '<a href="'.$settings_url.'">'.__("Settings", PLUGIN_NAME_TEXT_DOMAIN).'</a>' );
  }
  return $links_array;
}
add_filter( 'plugin_action_links', 'plugin_name_action_links', 10, 4 );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
  PluginName\Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
  PluginName\Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
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

  $plugin = new PluginName\Plugin_Name();
  $plugin->run();

}
run_plugin_name();
