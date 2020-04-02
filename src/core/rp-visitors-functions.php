<?php
/**
 * Core functions file
 *
 * @package Recent_Post_Visitors
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get setting
 *
 * @param string $setting Setting name.
 *
 * @return mixed
 */
function rp_visitors_get_option( $setting = '' ) {

	$default = array(
		'enabled_post_types' => array( 'post' => 'post' ),
		'position'           => array( 'after_content' => 'after_content' ),
		'no_of_users'        => 5,
	);

	$settings = get_option( 'rp_visitors_settings', $default );

	if ( isset( $settings[ $setting ] ) ) {
		return $settings[ $setting ];
	}

	return null;
}

/**
 * Locate or load a file from theme or fallback to plugin.
 *
 * @param string $template template.
 * @param bool   $load load template?.
 * @param array  $args Array.
 *
 * @return string
 */
function rp_visitors_locate_template( $template, $load = false, $args = array() ) {
	$template_base = 'recent-post-visitors/';

	$located = locate_template( $template_base . $template, false, false );

	$template_path = '';
	if ( is_readable( $located ) ) {
		$template_path = $located;
	} else {
		$template_path = recent_post_visitors()->path . 'templates/recent-visitors.php';
	}

	if ( ! $load ) {
		return $template_path;
	}

	require $template_path;
}

/**
 * Get post visitors
 *
 * @param int      $post_id Post id.
 * @param int|bool $limit No of visitor to get.
 *
 * @return array
 */
function rp_visitors_get_post_visitors( $post_id, $limit = false ) {
	$post_visitors = get_post_meta( $post_id, '_rp_visitors', true );
	$post_visitors = $post_visitors ? $post_visitors : array();

	if ( $limit ) {
		return array_slice( $post_visitors, 0, $limit );
	}

	return $post_visitors;
}

/**
 * Add visitor to post visitors list.
 *
 * @param int $post_id Post id.
 * @param int $visitor_id Visitor id.
 */
function rp_visitors_add_post_visitor( $post_id, $visitor_id ) {
	$post_visitors = rp_visitors_get_post_visitors( $post_id );

	$existed_key = array_search( $visitor_id, $post_visitors );

	if ( false !== $existed_key ) {
		unset( $post_visitors[ $existed_key ] );
	}

	array_unshift( $post_visitors, $visitor_id );

	update_post_meta( $post_id, '_rp_visitors', $post_visitors );
}
