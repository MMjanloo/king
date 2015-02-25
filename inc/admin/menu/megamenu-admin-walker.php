<?php 
class ULT_Admin_Menu_Walker extends Walker_Nav_Menu /* Walker_Nav_Menu_Edit: Fatal Error: Class Not Found */
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {}
	function end_lvl( &$output, $depth = 0, $args = array() ) {}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)', 'king' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)', 'king'), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';

		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item', 'king' ); ?></span></span>
					<span class="item-controls">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
						<span class="item-type show-if-mega-menu-top"><?php echo __('Mega Menu', 'king') ?></span>
						<span class="item-type show-if-mega-menu-column"><?php echo __('Column', 'king') ?></span>
						<span class="item-type hide-if-mega-menu-top hide-if-mega-menu-column"><?php echo esc_html($item->type_label) ?></span>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'king'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>"><?php _e( 'Edit Menu Item', 'king' ); ?></a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if( 'custom' == $item->type ) : ?>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<p class="field-url description description-wide hide-if-mega-menu-column">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL', 'king' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="description description-thin hide-if-mega-menu-column hide-if-mega-menu-item">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label', 'king' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="description description-thin hide-if-mega-menu-column hide-if-mega-menu-item">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute', 'king' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="field-link-target description hide-if-mega-menu-column">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new window/tab', 'king' ); ?>
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="field-css-classes description description-thin hide-if-mega-menu-column">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)', 'king' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="field-xfn description description-thin hide-if-mega-menu-column">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)', 'king' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
<?php # Column ?>
				<p class="description description-wide show-if-mega-menu-column show-if-mega-menu-item">
					<label>
						<span class="hide-if-mega-menu-item"><?php _e('Mega Menu Column Title', 'king') ?></span>
						<span class="hide-if-mega-menu-column"><?php _e('Item Title', 'king') ?></span><br />
						<?php // -------------------------- ?>
						<?php // NOTE this is a post title! ?>
						<?php // -------------------------- ?>
						<input type="text" name="menu-item-title[<?php echo $item_id ?>]" value="<?php echo esc_attr($item->title) ?>" class="widefat mega-menu-title" />
					</label>
					<label class="mega-menu-title-off-label">
						<input type="checkbox" name="<?php echo name_mega_menu_meta($item, 'title-off') ?>" <?php checked(get_mega_menu_meta($item, 'title-off')) ?> class="mega-menu-title-off" />
						Hide
					</label>
				</p>
				<p class="description description-wide show-if-mega-menu-column">
					<label>
						<input type="checkbox" name="<?php echo name_mega_menu_meta($item, 'new-row') ?>" <?php checked(get_mega_menu_meta($item, 'new-row')) ?> class="mega-menu-column-new-row" />
						<?php _e('This column should start a new row', 'king') ?>
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<p class="field-description description description-wide hide-if-mega-menu-column force-show-if-mega-menu-item">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description', 'king' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'king'); ?></span>
					</label>
				</p>
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
<?php # Icon ?>
				<p class="field-mega-menu-icon description description-wide empty">
					<label>
						<?php _e('Icon', 'king') ?><br />
						<a href="#" class="button" data-action="mega-menu-pick-icon">
							<span class="inline-if-empty">Add Icon</span>
							<span class="hide-if-empty">Edit Icon</span>
						</a>&nbsp;
						<span data-action="mega-menu-pick-icon" class="mega-menu-icon-frame hide-if-empty" style="position: relative;">
							<i data-subject="mega-menu-icon-i"></i>
							<a href="#" class="mega-menu-icon-remove dashicons king-x" data-action="mega-menu-remove-icon" title="Remove Icon">&#xf153;</a>
						</span>
						<span class="mega-menu-icon-frame inline-if-empty" data-action="mega-menu-pick-icon"><i class="fa fa-lg fa-eye" style="position: relative; top: -1px;"></i></span>
						<input type="hidden" name="<?php echo name_mega_menu_meta($item, 'icon') ?>" value="<?php echo esc_attr(get_mega_menu_meta($item, 'icon')) ?>" data-subject="mega-menu-icon-input" />
					</label>
				</p>
<?php # Use as Mega Menu ?>
				<p class="description description-wide show-if-menu-top">
					<label>
						<input type="checkbox" name="<?php echo name_mega_menu_meta($item, 'enabled') ?>" <?php checked(get_mega_menu_meta($item, 'enabled')) ?> class="mega-menu-enabled" />
						Use as Mega Menu
					</label>
				</p>
				<p class="field-move hide-if-no-js description description-wide hide-if-mega-menu-column">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<label>
						<span><?php _e( 'Move', 'king' ); ?></span>
						<a href="#" class="menus-move-up"><?php _e( 'Up one', 'king' ); ?></a>
						<a href="#" class="menus-move-down"><?php _e( 'Down one', 'king' ); ?></a>
						<a href="#" class="menus-move-left"></a>
						<a href="#" class="menus-move-right"></a>
						<a href="#" class="menus-move-top"><?php _e( 'To the top', 'king' ); ?></a>
					</label>
				</p>

<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
				<div class="menu-item-actions description-wide submitbox">
<?php # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ?>
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original hide-if-mega-menu-column">
							<?php printf( __('Original: %s', 'king'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							admin_url( 'nav-menus.php' )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e( 'Remove', 'king' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'king'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}

add_filter('wp_edit_nav_menu_walker', 'ult_admin_filter_wp_edit_nav_menu_walker');
function ult_admin_filter_wp_edit_nav_menu_walker()
	{
		return 'ULT_Admin_Menu_Walker';
	}
/**
 * @internal
 */
function _mega_menu_meta($post, $key, $default = null, $write = false)
{
	static $meta = array();

	$post_id = is_object($post) ? $post->ID : $post;

	if (!isset($meta[$post_id])) {
		$meta[$post_id] = (array) get_post_meta($post_id, 'mega-menu', true);
	}

	if ($write) {
		if (is_array($key)) {
			$meta[$post_id] = array_filter(array_merge($meta[$post_id], $key));
		}
		else {
			$meta[$post_id][$key] = $default;
			$meta[$post_id][$key] = array_filter($meta[$post_id][$key]);
		}
		ult_update_post_meta($post_id, 'mega-menu', $meta[$post_id]);
		return null;
	}

	return isset($meta[$post_id][$key]) ? $meta[$post_id][$key] : $default;
}

function name_mega_menu_meta($post, $key)
{
	$post_id = is_object($post) ? $post->ID : $post;

	return "mega-menu[$post_id][$key]";
}

function request_mega_menu_meta($post)
{
	$post_id = is_object($post) ? $post->ID : $post;

	return (array) @$_POST['mega-menu'][$post_id];
}

function get_mega_menu_meta($post, $key, $default = null)
{
	return _mega_menu_meta($post, $key, $default);
}

function update_mega_menu_meta($post, array $array)
{
	return _mega_menu_meta($post, $array, null, true);
}

/* Enqueue Scripts and Styles */
add_action('admin_enqueue_scripts','ult_megamenu_scripts');
function ult_megamenu_scripts($hook){
	if($hook == "nav-menus.php"){
		$king_admin_url = get_template_directory_uri() . '/inc/admin/assets/';
		wp_enqueue_script('ult-backend-options', $king_admin_url.'js/backend-options.js','','',true);
		wp_enqueue_script('ult-fw-events', $king_admin_url.'js/fw-events.js','','',true);
		wp_enqueue_script('ult-option-types', $king_admin_url.'js/option-types.js','','',true);
		wp_enqueue_script('ult-fw', $king_admin_url.'js/fw.js','','',true);
		wp_enqueue_script('ult-admin', $king_admin_url.'js/admin.js','','',true);
		wp_enqueue_style('ult-admin', $king_admin_url.'css/admin.css');

		wp_localize_script('ult-fw', '_fw_localized', array(
			'SITE_URI' => site_url(),
		));
	}
}

add_action('wp_update_nav_menu_item', 'ult_admin_action_wp_update_nav_menu_item', 10, 3);
function ult_admin_action_wp_update_nav_menu_item($menu_id, $menu_item_db_id, $args)
{
	$flags = array('enabled', 'title-off', 'new-row');
	$meta = request_mega_menu_meta($menu_item_db_id);
	foreach ($flags as $flag) {
		$meta[$flag] = isset($meta[$flag]);
	}
	update_mega_menu_meta($menu_item_db_id, $meta);
}