# Plugin_Name Boilerplate changes
## v2.0.1
* Migrated the Plugin list filters into the admin class where I always go looking for them (as they only run in admin area).
* Changed tabs into spaces.

## v2.0.0
* Namespaced the classes
* Added function to retrieve the git repo URL for dynamic updating in plugin description.
* Added shortcodes to the loader
* Included the `PluginName\Phil_Tanner_Admin()` class
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
