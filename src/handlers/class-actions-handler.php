<?php
/**
 * Action handler class
 *
 * @package Recent_Post_Visitors
 * @subpackage Handlers
 */

namespace Recent_Post_Visitors\Handlers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Actions_Handler
 */
class Actions_Handler {

	/**
	 * Class self boot
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup
	 */
	private function setup() {
		add_action( 'template_redirect', array( $this, 'record_visitor_activity' ) );

		add_filter( 'the_content', array( $this, 'render_visitors' ) );
	}

	/**
	 * Records visitors activity
	 */
	public function record_visitor_activity() {

		// Make sure we are single post and user is logged in.
		if ( ! ( is_single() || is_page() ) || ! is_user_logged_in() ) {
			return;
		}

		$post = get_post( get_the_ID() );

		// Post type to be recorded.
		$need_recording = rp_visitors_get_option( 'enabled_post_types' );

		if ( ! in_array( $post->post_type, $need_recording ) ) {
			return;
		}

		rp_visitors_add_post_visitor( $post->ID, get_current_user_id() );
	}

	/**
	 * Render visitors
	 *
	 * @param string $content Content
	 *
	 * @return string
	 */
	public function render_visitors( $content ) {
		$limit    = rp_visitors_get_option( 'no_of_users' );
		$visitors = rp_visitors_get_post_visitors( get_the_ID(), $limit );

		if ( empty( $visitors ) ) {
			return $content;
		}

		$position = rp_visitors_get_option( 'position' );

		ob_start();
		rp_visitors_locate_template( 'recent-visitors.php', true, array( 'user_ids' => $visitors ) );
		$visitors_list = ob_get_clean();

		$new_content = '';
		if ( 'before_content' == $position ) {
			$new_content = $visitors_list . $content;
		} elseif ( 'after_content' == $position ) {
			$new_content = $content . $visitors_list;
		}

		return $new_content;
	}
}

