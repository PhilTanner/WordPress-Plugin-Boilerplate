<?php
namespace PluginName;
/**
 * Fired during plugin uninstall
 *
 * @link       http://example.com
 * @since      2.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      2.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Uninstaller {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    2.0.0
	 */
	public static function uninstall() {

		// checks to make sure Wordpress is the one requesting the uninstall
		if (!defined('WP_UNINSTALL_PLUGIN')) {
			die;
		}

		/*
		 * Delete our internally stored data variables
		 */
		delete_site_option('plugin-name-activated-version');

	}

}
