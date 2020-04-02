<?php
/**
 * Plugin Name: Recent Post Visitors
 * Version: 1.0.0
 * Plugin URI: https://buddydev.com/plugins/recent-post-visitors/
 * Description: This plugin records visitors on a single post.
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Requires PHP: 5.3
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  recent-post-visitors
 * Domain Path:  /languages
 *
 * @package recent-post-visitors
 **/

use Recent_Post_Visitors\Bootstrap\Autoloader;
use Recent_Post_Visitors\Bootstrap\Bootstrapper;

// No direct access over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Recent_Post_Visitors
 *
 * @property-read $path     string Absolute path to the plugin directory.
 * @property-read $url      string Absolute url to the plugin directory.
 * @property-read $basename string Plugin base name.
 * @property-read $version  string Plugin version.
 */
class Recent_Post_Visitors {

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Class instance
	 *
	 * @var Recent_Post_Visitors
	 */
	private static $instance = null;

	/**
	 * Plugin absolute directory path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin absolute directory url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Plugin Basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Protected properties. These properties are inaccessible via magic method.
	 *
	 * @var array
	 */
	private $secure_properties = array( 'instance' );

	/**
	 * Recent_Post_Visitors constructor.
	 */
	private function __construct() {
		$this->bootstrap();
	}

	/**
	 * Get Singleton Instance
	 *
	 * @return Recent_Post_Visitors
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap the core.
	 */
	private function bootstrap() {
		$this->path     = plugin_dir_path( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->basename = plugin_basename( __FILE__ );

		// Load autoloader.
		require_once $this->path . 'src/bootstrap/class-autoloader.php';

		$autoloader = new Autoloader( 'Recent_Post_Visitors\\', __DIR__ . '/src/' );

		spl_autoload_register( $autoloader );

		// Drop tables on uninstall.
		// register_uninstall_hook( __FILE__, array( 'Schema', 'drop' ) );.

		Bootstrapper::boot();
	}

	/**
	 * Magic method for accessing property as readonly(It's a lie, references can be updated).
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {

		if ( ! in_array( $name, $this->secure_properties, true ) && property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		return null;
	}
}

/**
 * Helper to access singleton instance
 *
 * @return Recent_Post_Visitors
 */
function recent_post_visitors() {
	return Recent_Post_Visitors::get_instance();
}

recent_post_visitors();
