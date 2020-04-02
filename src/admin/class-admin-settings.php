<?php
/**
 * Admin Settings Pages Helper.
 *
 * @package    Recent_Post_Visitors
 * @subpackage Admin
 * @copyright  Copyright (c) 2018, BuddyDev.Com
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Ravi Sharma, Brajesh Singh
 * @since      1.0.0
 */

namespace Recent_Post_Visitors\Admin;

use \Press_Themes\PT_Settings\Page;

// Exit if file accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Settings
 */
class Admin_Settings {

	/**
	 * Admin Menu slug
	 *
	 * @var string
	 */
	private $menu_slug;

	/**
	 * Used to keep a reference of the Page, It will be used in rendering the view.
	 *
	 * @var \Press_Themes\PT_Settings\Page
	 */
	private $page;

	/**
	 * Boot settings
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup settings
	 */
	public function setup() {

		$this->menu_slug = 'bp_skeleton';

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	/**
	 * Show/render the setting page
	 */
	public function render() {
		$this->page->render();
	}

	/**
	 * Is it the setting page?
	 *
	 * @return bool
	 */
	private function needs_loading() {

		global $pagenow;

		// We need to load on options.php otherwise settings won't be reistered.
		if ( 'options.php' === $pagenow ) {
			return true;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->menu_slug ) {
			return true;
		}

		return false;
	}

	/**
	 * Initialize the admin settings panel and fields
	 */
	public function init() {

		if ( ! $this->needs_loading() ) {
			return;
		}

		$page = new Page( 'rp_visitors_settings', __( 'Recent Post Visitors', 'bp-skeleton' ) );

		// General settings tab.
		$general = $page->add_panel( 'general', _x( 'General', 'Admin settings panel title', 'bp-skeleton' ) );

		$section_general = $general->add_section( 'settings', _x( 'General Settings', 'Admin settings section title', 'bp-skeleton' ) );

		$fields = array(
			array(
				'name'    => 'enabled_post_types',
				'label'   => _x( 'Select Post Types', 'Admin settings', 'bp-skeleton' ),
				'type'    => 'multicheck',
				'options' => $this->get_posttypes(),
				'default' => array( 'post' => 'post' ),
			),
			array(
				'name'    => 'position',
				'label'   => _x( 'Where to show recent visitors', 'Admin settings', 'bp-skeleton' ),
				'type'    => 'radio',
				'options' => array(
					'before_content' => __( 'Before content', 'recent-post-visitors' ),
					'after_content'  => __( 'Before content', 'recent-post-visitors' ),
				),
				'default' => 'after_content',
			),
		);

		$section_general->add_fields( $fields );

		do_action( 'rp_visitors_admin_settings_page', $page );

		$this->page = $page;

		// allow enabling options.
		$page->init();
	}

	/**
	 * Add Menu
	 */
	public function add_menu() {

		add_options_page(
			_x( 'Recent_Post_Visitors', 'Admin settings page title', 'bp-skeleton' ),
			_x( 'Recent_Post_Visitors', 'Admin settings menu label', 'bp-skeleton' ),
			'manage_options',
			$this->menu_slug,
			array( $this, 'render' )
		);
	}

	/**
	 * Get roles
	 *
	 * @return array
	 */
	private function get_posttypes() {
		$post_types = array();

		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type => $detail ) {
			$post_types[ $post_type ] = $detail->label;
		}

		return $post_types;
	}
}
