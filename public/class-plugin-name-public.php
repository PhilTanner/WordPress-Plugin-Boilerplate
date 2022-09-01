<?php
namespace Plugin_Name;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Public {

  /**
   * The user entered options of this plugin.
   *
   * @since    2.0.3
   * @access   protected
   * @var      array    $options    A named array of options & values entered in the admin page.
   */
  protected $options;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   */
  public function __construct( ) {

    $this->options   = array_merge(
      // Repeat this next line for all option values you might use
      (array)get_site_option( 'plugin-name-options', array() ),
      array(),
    );
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style( 'plugin-name-public', plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), PLUGIN_NAME_VERSION, 'all' );

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script( 'plugin-name-public', plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), PLUGIN_NAME_VERSION, false );

  }

}
