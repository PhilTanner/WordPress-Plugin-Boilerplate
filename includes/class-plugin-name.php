<?php
namespace Plugin_Name;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
   * - Plugin_Name_i18n. Defines internationalization functionality.
   * - Plugin_Name_Admin. Defines all hooks for the admin area.
   * - Plugin_Name_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-loader.php';

    /**
     * The class responsible for orchestrating the activation of the plugin
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-activator.php';

    /**
     * The class responsible for orchestrating the deactivation of the plugin
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-deactivator.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-name-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-name-public.php';

    $this->loader = new Plugin_Name_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new Plugin_Name_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new Plugin_Name_Admin();

    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin,     'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin,     'enqueue_scripts' );

    $this->loader->add_action( 'admin_menu',            $plugin_admin,     'add_menu_links' );

    $this->loader->add_action( 'admin_init',            $plugin_admin,     'register_options_input_fields' );

    $this->loader->add_action( 'admin_notices',         $plugin_admin,     'admin_notices' );
    $this->loader->add_action( 'upload_dir',            $plugin_admin,     'fix_upload_path_for_ssl' );

    $this->loader->add_filter( 'plugin_row_meta',       $plugin_admin,     'plugin_name_links', 10, 4 );
    $this->loader->add_filter( 'plugin_action_links',   $plugin_admin,     'plugin_name_action_links', 10, 4 );

    $plugin_activator = new Plugin_Name_Activator();
    $this->loader->add_action( 'admin_init',            $plugin_activator, 'check_for_plugin_changes' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new Plugin_Name_Public();

    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

}
