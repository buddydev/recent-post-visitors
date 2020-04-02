<?php
/**
 * Bootstrapper. Initializes the plugin.
 *
 * @package    Recent_Post_Visitors
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

namespace Recent_Post_Visitors\Bootstrap;

use Recent_Post_Visitors\Admin\Admin_Settings;
use Recent_Post_Visitors\Handlers\Actions_Handler;

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Bootstrapper.
 */
class Bootstrapper {

	/**
	 * Setup the bootstrapper.
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Bind hooks
	 */
	private function setup() {
		add_action( 'plugins_loaded', array( $this, 'load' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_admin' ), 9996 ); // pt settings 1.0.4.
		add_action( 'init', array( $this, 'load_translations' ) );
	}

	/**
	 * Load core functions/template tags.
	 * These are non auto loadable constructs.
	 */
	public function load() {
		require_once recent_post_visitors()->path . 'src/core/rp-visitors-functions.php';

		Actions_Handler::boot();
		Assets_Loader::boot();
	}

	/**
	 * Load pt-settings framework
	 */
	public function load_admin() {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			require_once recent_post_visitors()->path . 'src/admin/pt-settings/pt-settings-loader.php';
			Admin_Settings::boot();
		}
	}

	/**
	 * Load translations.
	 */
	public function load_translations() {
		load_plugin_textdomain( 'recent-post-visitors', false, basename( dirname( recent_post_visitors()->path ) ) . '/languages' );
	}
}
