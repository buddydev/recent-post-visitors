<?php
/**
 * Assets Loader
 *
 * @package    BuddyPress_Auto_Friendship_Pro
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2018, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma
 * @since      1.0.0
 */

namespace Recent_Post_Visitors\Bootstrap;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Assets Loader.
 */
class Assets_Loader {

	/**
	 * Data to be send as localized js.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Boot itself
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup
	 */
	public function setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Load plugin assets
	 */
	public function load_assets() {
		$this->register();
		$this->enqueue();
	}

	/**
	 * Register assets.
	 */
	public function register() {
		$this->register_vendors();
		$this->register_core();
	}

	/**
	 * Load assets.
	 */
	public function enqueue() {
		wp_enqueue_style( 'recent_post_visitors_css' );
	}

	/**
	 * Register vendor scripts.
	 */
	private function register_vendors() {}

	/**
	 * Register core assets.
	 */
	private function register_core() {
		$bpafp   = recent_post_visitors();
		$url     = $bpafp->url;
		$version = $bpafp->version;

		wp_register_style(
			'recent_post_visitors_css',
			$url . 'assets/css/recent-post-visitors.css',
			false,
			$version
		);
	}
}
