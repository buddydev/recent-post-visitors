<?php
/**
 * Template for recent members
 *
 * @package    Recent_post_Visitors
 * @subpackage Core
 * @copyright  Copyright (c) 2020, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh, Ravi Sharma(raviousprime)
 * @since      1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$users = get_users( array( 'include' => $args['user_ids'], 'orderby' => 'include' ) );
?>

<?php if( $users ) : ?>

	<ul class="rp-visitors-recent-members-list">

	<?php foreach ( $users as $user ) : ?>
		<li>
			<?php if ( function_exists( 'buddypress' ) ) : ?>

                <a href="<?php echo bp_core_get_user_domain( $user->ID ); ?>">
	                <?php echo bp_core_fetch_avatar( array( 'item_id' => $user->ID, 'alt' => $user->display_name ) ); ?>
                </a>

            <?php else: ?>
				<?php echo $user->display_name; ?>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>

	</ul>

<?php endif; ?>