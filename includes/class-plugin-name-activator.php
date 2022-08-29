<?php
namespace Plugin_Name;
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Activator {

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate() {
    global $plugin_name_plugin_data;

    // Grab our currently installed version from the DB.
    $installed_version = get_site_option('plugin-name-activated-version', get_site_option('plugin-name-activated_version', 0) );

    // Our currently installed version is less than our version - so do some stuff.
    if( version_compare( $installed_version, PLUGIN_NAME_VERSION, "<" ) ){

      /**
       * Do the things...
       *
       * Remember, if you want to only do some things if migrating from specific
       * versions, you can do some things like:
       * if( $installed_version && version_compare( $installed_version, "2.0.1", "<" ) ){
       *   // Do the things that need to happen if previous version was less than 2.0.1
       * }
       */

      update_site_option( 'plugin-name-activated-version', PLUGIN_NAME_TEXT_DOMAIN );
      add_settings_error(
        PLUGIN_NAME_TEXT_DOMAIN, // Slug of the setting which we're generating the error for
        'plugin-updated', // slug name of the code of this particular error
        $plugin_name_plugin_data['PluginName'] .": ". sprintf( __('Plugin automatically updated to version %s.',PLUGIN_NAME_TEXT_DOMAIN), PLUGIN_NAME_VERSION ),
        'success' // error, success, warning, or info. Default error.
      );
    }

  }


  /**
   * Check if we need to re-activate.
   *
   * Evaluates the value saved to the WordPress Options and compares to the version
   * number set in the main plugin file, to test if we need to carry out an upgrade.
   *
   * @since    2.0.0
   */
  public static function check_for_plugin_changes() {
    global $plugin_name_plugin_data;

    // Only show these messages to WordPress Administrators
    if( current_user_can( "manage_options" ) ) {

      // Grab our currently installed version from the DB.
      $installed_version = get_site_option('plugin-name-activated-version', get_site_option('plugin-name-activated_version', 0) );

      // Our activated version isn't the same as the version we're looking at, we should re-activate.
      if( version_compare( $installed_version, PLUGIN_NAME_VERSION, "<" ) ){
        self::activate();
      }
    }
  }

}
