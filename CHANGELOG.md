# Plugin_Name Boilerplate changes

## v2.0.6
* Bumped required WordPress version in README.txt up to 5.5.1 (required for use of [`wp_get_environment_type()`](https://developer.wordpress.org/reference/functions/wp_get_environment_type/)
* Increased `Phil_Tanner_Admin` version to v1.0.6
  * Removed incorrectly integered `max` argument to input fields.
* Increased `Phil_Tanner_Admin` version to v1.1.0
  * Escaping input IDs using `sanitize_key()` instead of just outputting `$args['name']` directly.
  * Escaping input names using `sanitize_key()` instead of `esc_attr()`.
  * Escaping input description IDs using `sanitize_html_class()` instead of `esc_attr()` against the `args['name']` value for better alignment to DOM ID naming conventions.
  * Addition of new form input methods (SemVer mid-version bump):
    * `print_radio_inputs()`
    * `print_email_input()`
    * `print_date_input()`
    * `print_password_input()`
    * `print_datetime_input()`
    * `print_month_and_year_input()`
    * `print_week_input()`
    * `print_range_input()`
    * `print_tel_input()`
    * `print_hidden_input()`
  * Stopped `print_number_input()` using `%f` for output of numbers, as it was padding trailing zeros. Now just parses value as string put thru `(float)` typecast.



## v2.0.5
### UI improvements
* External links (a href tags with a `target="_blank"` argument) now automatically tagged with external link image.
* Tweaks to required admin fields marked as required, changing colours from `red` to `crimson`.
* Display of Github information in `admin/partials/plugin-name-admin-display.php` moved into dl/dt/dd tags for semantic markup.
* Checking of `WP_ENVIRONMENT_TYPE` before display (should always be defined anyway).

## v2.0.4
* Removed `$plugin_name` and `$plugin_version` from `Plugin_Name_Admin()` and `Plugin_Name_Public()` as they were unused & superfluous.
  * Removed `Plugin_Name_Public::get_plugin_name()` and `Plugin_Name_Public::get_version()` functions as no longer required.
* Changed `admin_notices` to only print messages to users with `manage_options` capabilities (Administrators).
* Moved the content of `admin_page_display` into the `admin/partials/plugin-name-admin-display.php` file.
* Corrected settings URL typo in `Plugin_Name_Admin::plugin_name_action_links()`.
* Moved creation of settings_errors about automatic updates in the activator class for more logical location.
* Corrected the naming of the `plugin-initialisation.sh` file inside itself to avoid recursion.

## v2.0.3
* Changed plugin options property to protected to allow interaction with `Phil_Tanner_Admin()` class.
* Included example `label_for` value in form fields to output labels for settings fields for better accessibility.
* Included an example of how to use `placeholder` argument.
* Automatic highlighting of required fields in plugin settings pages using CSS.
* Tidied up admin tool JavaScript (mishashed copy/paste).
* Fixed activated plugin version option being saved to correctly store value.
* More tabs vs spaces patching.

## v2.0.2
* Changed Namespace to allow find/replace renaming as per Readme.md instructions.
* Patched correct handling of multiple select handling in `Phil_Tanner_Admin()`.
* Example admin page added, with example option values.

## v2.0.1
* Migrated the Plugin list filters into the admin class where I always go looking for them (as they only run in admin area).
* Changed tabs into spaces.

## v2.0.0
* Namespaced the classes
* Added function to retrieve the git repo URL for dynamic updating in plugin description.
* Added shortcodes to the loader
* Included the `Plugin_Name\Phil_Tanner_Admin()` class
  * simplify WordPress admin settings inputs.
  * provide git info
  * fix SSL upload paths
  * print dates & times according to WordPress admin options
* Check for WordFence installation & activation.
* Added jQuery.Dirty to admin forms to prevent page exit while values not saved.
* Activate plugin now saves activated version.
  * Activation function is called automatically if there is a version discrepency.
* `load_dependencies()` now includes all additional classes in the `includes/` folder.
* Plugin description altered, to include link to github repository, and branch name, for git controlled plugins.
* Plugin action links adjusted to include a `Settings` link.

## Changelog 1.0.0
* Inherited from Boilerplate
* (3 July 2015). Flattened the folder structure so there is no .org repo parent folder.
* (4 September 2014). Updating the `README` with Windows symbolic link instructions.
* (3 September 2014). Updating the `README` to describe how to install the Boilerplate.
* (1 September 2014). Initial Release.
