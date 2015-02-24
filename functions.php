<?php
// Set content width value based on the theme's design
if ( ! isset( $content_width ) )
	$content_width = 600;


/**
 * Ultimate setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Ultimate supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 *
 * @since Ultimate 1.0
 */
function ultimate_setup() {

	// Add theme support for Automatic Feed Links
	add_theme_support( 'automatic-feed-links' );

	// Add theme support for Post Formats
	add_theme_support( 'post-formats', array( 'status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside' ) );

	// Add theme support for Featured Images
	add_theme_support( 'post-thumbnails' );

	// Add theme support for Custom Background
	$background_args = array(
		'default-color'          => '#e6e6e6',
	);
	add_theme_support( 'custom-background', $background_args );

	// Add theme support for Custom Header
	$header_args = array(
		'default-image'          => '',
		'width'                  => 0,
		'height'                 => 0,
		'flex-width'             => false,
		'flex-height'            => false,
		'uploads'                => false,
		'random-default'         => false,
		'header-text'            => false,
		'default-text-color'     => '',
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => '',
	);
	add_theme_support( 'custom-header', $header_args );

	// Add theme support for HTML5 Semantic Markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Add theme support for document Title tag
	add_theme_support( 'title-tag' );

	// Add theme support for custom CSS in the TinyMCE visual editor
	add_editor_style();

	// Add theme support for Translation
	load_theme_textdomain( 'ultimate', get_template_directory() . '/language' );

	// Declare support for all custom hooks
	add_theme_support( 'ult_hooks', array( 'all' ) );

	// Woocommerce Support
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'ultimate_setup' );


/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Ultimate 1.0
 */
function ultimate_scripts_styles() {
	global $wp_styles;
	
	// Loads our main stylesheet.
	wp_register_style( 'ultimate-style', get_stylesheet_uri(), false, '1.0.0', 'all' );
	wp_enqueue_style( 'ultimate-style' );
	wp_register_style( 'ultimate-bootstrap-grid', get_template_directory_uri().'/inc/css/bootstrap-grids.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'ultimate-bootstrap-grid' );
	wp_register_style( 'ultimate-font-icons', get_template_directory_uri().'/inc/css/font-awesome.min.css', false, '4.3.0', 'all' );
	wp_enqueue_style( 'ultimate-font-icons' );
		
	// Loads the Internet Explorer specific stylesheet.
	$wp_styles->add('ultimate-ie', get_template_directory_uri() . '/inc/css/ie.css');
	$wp_styles->add_data('ultimate-ie', 'conditional', 'lte IE 9');
	$wp_styles->enqueue(array('ultimate-ie'));

	// Add global jQuery
	wp_enqueue_script('jquery');

	// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use)
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Bootstrap Javascript
	wp_register_script( 'ultimate-bootstrap-script', get_template_directory_uri() . '/inc/js/jquery.bootstrap.min.js', array( 'jquery' ), '3.3.1', true );
	wp_enqueue_script( 'ultimate-bootstrap-script' );
	
	// Load Masonry Javascript
	$masonry_blog_layout = get_theme_mod('blog_masonry_layout');
	$blog_layout = get_theme_mod('blog_layout');
	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		if ( $masonry_blog_layout ) :
			if ( is_home() || is_front_page() || is_archive() || is_search() ) :
				wp_enqueue_script('jquery-masonry');
				add_action('wp_footer', 'ultimate_masonry_blog');
			endif;
		endif;
	endif;

	// Slick Slider
	wp_register_style( 'ultimate-slick-slider', get_template_directory_uri().'/inc/css/slick/slick.css', false, '1.4.0', 'all' );
	wp_enqueue_style( 'ultimate-slick-slider' );
	wp_register_script( 'ultimate-slick-slider-script', get_template_directory_uri() . '/inc/js/jquery.slick.min.js', array( 'jquery' ), '1.4.0', true );
	wp_enqueue_script( 'ultimate-slick-slider-script' );

    // Justified Grid Gallery
    wp_register_style( 'ultimate-justified-gallery', get_template_directory_uri().'/inc/css/justifiedGallery.min.css', false, '3.5.1', 'all' );
	wp_enqueue_style( 'ultimate-justified-gallery' );
	wp_register_script( 'ultimate-justified-gallery-script', get_template_directory_uri() . '/inc/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.5.1', true );
	wp_enqueue_script( 'ultimate-justified-gallery-script' );

    // Smooth Scroll
    wp_register_script( 'ultimate-smooth-scroll-script', get_template_directory_uri() . '/inc/js/jquery.smoothScroll.min.js', array( 'jquery' ), '1.2.1', true );
	$smooth_scroll = get_theme_mod( 'smooth_scroll' );
   	if($smooth_scroll) {
   		wp_enqueue_script( 'ultimate-smooth-scroll-script' );
	}

	// Lightbox - Colorbox
	wp_register_style( 'ultimate-colorbox', get_template_directory_uri().'/inc/css/colorbox/colorbox.css', false, '1.5.2', 'all' );
	wp_enqueue_style( 'ultimate-colorbox' );
	wp_register_script( 'ultimate-colorbox-script', get_template_directory_uri() . '/inc/js/jquery.colorbox.min.js', array( 'jquery' ), '1.5.2', true );
	wp_enqueue_script( 'ultimate-colorbox-script' );

	// Theme JavaScript	
	wp_register_script( 'ultimate-javascript', get_template_directory_uri() . '/inc/js/functions.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'ultimate-javascript' );
	
}
add_action( 'wp_enqueue_scripts', 'ultimate_scripts_styles' );

/**
 * Ultimate include basic functions.
 *
 */

require_once('inc/admin/customizer/customizer.php');
require_once('inc/admin/customizer/customizer-init.php');
require_once('inc/admin/customizer/customizer-style.php');
require_once('inc/admin/menu/megamenu-admin-walker.php');

require_once('inc/ultimate-theme-hooks.php');
require_once('inc/ultimate-breadcrumbs.php');
require_once('inc/ultimate-menu-walker.php');
require_once('inc/ultimate-pagination.php');
require_once('inc/ultimate-post-gallery.php');
require_once('inc/ultimate-page-meta.php');
require_once('inc/ultimate-widget.php');


/**
 * Register menus in theme 
 *
 * Added two location primary & footer menu
 *
 * @since Ultimate 1.0
 *
 */

if ( ! function_exists( 'ultimate_navigation_menus' ) ) :

	// Register Navigation Menus
	function ultimate_navigation_menus() {

		$locations = array(
			'primary' => __( 'Primary Menu', 'ultimate' ),
			'footer-menu' => __( 'Footer Menu', 'ultimate' ),
		);
		register_nav_menus( $locations );
	}

	// Hook into the 'init' action
	add_action( 'init', 'ultimate_navigation_menus' );

endif;


/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Ultimate 1.0
 */
if ( ! function_exists( 'ultimate_sidebar' ) ) :
	function ultimate_sidebar() {
		register_sidebar( array(
			'name' => __( 'Main Sidebar', 'ultimate' ),
			'id' => 'sidebar-1',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		) );
		register_sidebar( array(
			'name' => __( 'Footer Widget Area 1', 'ultimate' ),
			'id' => 'sidebar-footer-1',
			'description' => __( 'Appears in footer sidebar widget area at first position.', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer Widget Area 2', 'ultimate' ),
			'id' => 'sidebar-footer-2',
			'description' => __( 'Appears in footer sidebar widget area at second position.', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer Widget Area 3', 'ultimate' ),
			'id' => 'sidebar-footer-3',
			'description' => __( 'Appears in footer sidebar widget area at third position.', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer Widget Area 4', 'ultimate' ),
			'id' => 'sidebar-footer-4',
			'description' => __( 'Appears in footer sidebar widget area at fourth position.', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => __( 'Front Page Main Widget Area', 'ultimate' ),
			'id' => 'sidebar-front-main',
			'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => __( 'First Front Page Widget Area', 'ultimate' ),
			'id' => 'sidebar-front-1',
			'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => __( 'Second Front Page Widget Area', 'ultimate' ),
			'id' => 'sidebar-front-2',
			'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => __( 'Third Front Page Widget Area', 'ultimate' ),
			'id' => 'sidebar-front-3',
			'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'ultimate' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	}
	add_action( 'widgets_init', 'ultimate_sidebar' );
endif;



/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Ultimate 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function ultimate_get_font_url() {
	$font_url = '';
	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'ultimate' ) ) {
		$subsets = 'latin,latin-ext';
		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'ultimate' );
		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';
		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}
	return $font_url;
}

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses ultimate_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Ultimate 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function ultimate_mce_css( $mce_css ) {
	$font_url = ultimate_get_font_url();
	if ( empty( $font_url ) )
		return $mce_css;
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';
	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );
	return $mce_css;
}
add_filter( 'mce_css', 'ultimate_mce_css' );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Ultimate 1.0
 */
function ultimate_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'ultimate_page_menu_args' );

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own ultimate_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Ultimate 1.0
 */
if ( ! function_exists( 'ultimate_comment' ) ) :
function ultimate_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'ultimate' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'ultimate' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'ultimate' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'ultimate' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'ultimate' ); ?></p>
			<?php endif; ?>
			<div class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'ultimate' ), '<p class="edit-link">', '</p>' ); ?>
			</div><!-- .comment-content -->
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'ultimate' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Ultimate 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function ultimate_body_class( $classes ) {

	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';

		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) :
		if ( empty( $background_color ) ) :
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) ) :
			$classes[] = 'custom-background-white';
		endif;
	endif;

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'ultimate-fonts', 'queue' ) ) :
		$classes[] = 'custom-font-enabled';
	endif;

	if ( ! is_multi_author() ) :
		$classes[] = 'single-author';
	endif;

	// Site Layout
	$site_layout = get_theme_mod('site_layout');
	if ( $site_layout ) :
		$classes[] = get_theme_mod('site_layout');
	endif;


	// Blog Layout
	$blog_layout = get_theme_mod('blog_layout');
	if ($blog_layout) :
		$classes[] = get_theme_mod('blog_layout');
	endif;

	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		$classes[] = 'blog-grid';
	endif;

	// Enable Masonry Layout
	$masonry_layout = get_theme_mod('blog_masonry_layout');
	if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
		if ($masonry_layout) :
			$classes[] = 'blog-masonry';
		endif;
	endif;

	// Is not singular
	if ( ! is_singular() ) :
		$classes[] = 'not-singular';
	endif;

	// Sidebar Position
	$sidebar_position = get_theme_mod('sidebar_position');
	if ($sidebar_position == 'right-sidebar') :
		$classes[] = 'right-sidebar';
	elseif ($sidebar_position == 'left-sidebar') :
		$classes[] = 'left-sidebar';
	elseif ($sidebar_position == 'no-sidebar') :
		$classes[] = 'no-sidebar';	
	endif;

	// If fixed menu
	$fixed_header = get_theme_mod( 'site_fixed_header' );
	if($fixed_header) :
		$classes[] = 'ult-fixed-menu';
	endif;

	return $classes;
}
add_filter( 'body_class', 'ultimate_body_class' );


/**
 * Extend the default WordPress post classes.
 *
 * Extends the default WordPress post class to denote: grid layouts
 *
 * @since Ultimate 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function ultimate_post_class( $classes ) {

	global $post;
	$blog_layout = get_theme_mod('blog_layout');

	if ( !is_singular() ) :	
		if ($blog_layout == 'grid-2') {
			$classes[] = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
		} else if ($blog_layout == 'grid-3') {
			$classes[] = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
		} else if ($blog_layout == 'grid-4') {
			$classes[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
		} else {
			$classes[] = '';
		}
	endif;

	return $classes;
}
add_filter( 'post_class', 'ultimate_post_class' );


/**
 * Adjust content width in certain contexts.
 * site_width
 * Adjusts  value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Ultimate 1.0
 */
function ultimate_site_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $site_width;
		$site_width = 960;
	}
}
add_action( 'template_redirect', 'ultimate_site_width' );


/**
 * Include Javascript Snippet For Masonry Blog
 *
 * @since Ultimate 1.0
 */
function ultimate_masonry_blog() { 
?>
	<script type="text/javascript">
		(function($) {
			"use strict";
			function blog_masonry() {
				jQuery('.blog-masonry #content').imagesLoaded(function () {
					jQuery('.blog-masonry #content').masonry({
						columnWidth: '.post',
						itemSelector: '.post',
						transitionDuration: 0
					});
				});
			}
			$(document).ready(function() { blog_masonry(); });
			jQuery(window).load(function(){
				setTimeout(function(){
					jQuery('.blog-masonry #content').masonry('reload');
				},1000);
				
			});			
			//$(window).on('resize',function() { blog_masonry(); });
		})(jQuery);
	</script>
<?php
}


/**
 * Include Scroll To Top Feature
 *
 * @since Ultimate 1.0
 */
function ultimate_scroll_to_top() {
?>
	<script type="text/javascript">
		jQuery(function() {
		  jQuery('a[href*=#]:not([href=#])').click(function() {
		    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		      var target = jQuery(this.hash);
		      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
		      if (target.length) {
		        jQuery('html,body').animate({
		          scrollTop: target.offset().top
		        }, 1000);
		        return false;
		      }
		    }
		  });
		});
	</script>
	<a class="ult-scroll-top" href="#page"><span class="fa fa-angle-up"></span></a>
	<!--End Smooth Scroll-->
<?php
}
$scroll_to_top = get_theme_mod( 'scroll_to_top' );
if($scroll_to_top) {
	add_action('wp_footer', 'ultimate_scroll_to_top');
}

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Ultimate 1.0
 */
function ultimate_customize_preview_js() {
	wp_enqueue_script( 'ultimate-customizer', get_template_directory_uri() . '/inc/admin/assets/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}
add_action( 'customize_preview_init', 'ultimate_customize_preview_js' );

/**
 * Enqueue script for custom customize control.
 */
function custom_customize_enqueue() {
	echo '<style type="text/css">
			li#customize-control-favicon-img .thumbnail-image img {
				max-width: 18px;
				text-align: center;
				margin: 10px auto;
				display: block;
			}
		  </style>';
}
add_action( 'customize_controls_enqueue_scripts', 'custom_customize_enqueue' );






// Temporary

function wpt_register_css() {

    wp_register_style( 'supriya.css', get_template_directory_uri() . '/inc/css/supriya.css' );
    wp_enqueue_style( 'supriya.css' );
}
add_action( 'wp_enqueue_scripts', 'wpt_register_css' );



// Post Meta
if ( ! function_exists( 'ultimate_post_meta' ) ) :
	function ultimate_post_meta() {

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();
		$html = '';

		if( get_theme_mod( 'blog_author_meta' )) :
			$html .= '<span class="post-meta-item">';
			$html .= __('By ','ultimate'); 
			$html .= '<span class="vcard author"><a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" title="Posts by '. get_the_author() .'" rel="author">'. get_the_author() .'</a></span>'; 
			$html .= '</span>'; // .post-meta-item
		endif;

		if( get_theme_mod( 'blog_date_meta' )) :
			$archive_year  = get_the_time('Y');
			$archive_month = get_the_time('m');
			$html .= '<span class="post-meta-item">';
			$html .= '<span class="post-meta-date"><a href="'. get_month_link( $archive_year, $archive_month ) .'">'. get_the_date('d M, Y') .'</a></span>';
			$html .= '</span>'; // .post-meta-item
		endif;

		if( get_theme_mod( 'blog_category_meta' )) :
			$categories_list = get_the_category_list( __( ' ', 'ultimate' ) );		
			if( $categories_list ) :
				$html .=  '<span class="post-meta-item">';
	        	$html .=  '<span class="post-meta-category">'. get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'ultimate' ) ) .'</span>';
	        	$html .=  '</span>'; // .post-meta-item
			endif;
		endif;

		if( get_theme_mod( 'blog_tag_meta' )) :
			$tag_list = get_the_tag_list( __( ' ', 'ultimate' ) );		
			if( $tag_list ) :
				$html .=  '<span class="post-meta-item">';
	        	$html .=  '<span class="post-meta-category">'. get_the_tag_list('',', ', '') .'</span>';
	        	$html .=  '</span>'; // .post-meta-item
			endif;
		endif;

		if( get_theme_mod( 'blog_comment_meta' )) :
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : 
				$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
				if ( $num_comments == 0 ) {
					$comments = __('Leave a Comment', 'ultimate' );
				} elseif ( $num_comments > 1 ) {
					$comments = $num_comments . __(' Comments', 'ultimate' );
				} else {
					$comments = __('1 Comment', 'ultimate' );
				}
				$html .=  '<span class="post-meta-item">';
	            $html .=  '<span class="post-meta-comment"><a href="'. get_comments_link() .'" title="Comment on '. get_the_title() .'">'. $comments .'</a></span>'; 
	            $html .=  '</span>'; // .post-meta-item
	        endif;
		endif;		

		if( get_theme_mod( 'blog_link_meta' )) :
			if ( !is_single() ) :
				$html .=  '<span class="post-meta-item">';
				$html .=  '<span class="post-meta-link"><a href="'. get_the_permalink() .'" rel="bookmark">'.__('Read More...','ultimate') .'</a></span>';
				$html .=  '</span>'; // .post-meta-item
			endif;
		endif;
        
        if( is_user_logged_in() ):
        		$html .=  '<span class="post-meta-item">';
	            $html .=  '<span class="post-meta-edit"><a class="post-edit-link" href="'. get_edit_post_link() .'">'. __( 'Edit', 'ultimate' ) .'</a></span>';
	        	$html .=  '</span>'; // .post-meta-item
		endif;

		if ($html != '') :
			echo '<div class="entry-summary-meta">';
			echo '<div class="post-meta">';
			echo $html;
			echo '</div>';
			echo '</div>';
		endif;
	}
	add_action('ult_entry_bottom', 'ultimate_post_meta', 10, 1);
endif;

// Remove Post Meta From Pages, 404, Grid Blog layout
if ( ! function_exists( 'ultimate_remove_post_meta' ) ) :
	function ultimate_remove_post_meta() {
		$blog_layout = get_theme_mod('blog_layout');
		if($blog_layout == 'grid-2' || $blog_layout == 'grid-3' || $blog_layout == 'grid-4') :
			if (is_search() || is_home() || is_archive()) :
				remove_action('ult_entry_bottom', 'ultimate_post_meta');
			endif;
		endif;

		if (is_page()) :
			remove_action('ult_entry_bottom', 'ultimate_post_meta');
		endif;
	}
	add_action( 'wp', 'ultimate_remove_post_meta' );
endif;


// Custom Excerpt Length
if ( ! function_exists( 'ultimate_excerpt_length' ) ) :
	function ultimate_excerpt_length( $length ) {
		$check_excerpt_length = get_theme_mod( 'post_excerpt_length' );
		$excerpt_length = ($check_excerpt_length != 0) ? $check_excerpt_length : 25;
		return $excerpt_length;
	}
	add_filter( 'excerpt_length', 'ultimate_excerpt_length', 999 );
endif;

// Add new image Size for Medium Image Blog
add_image_size( 'medium-image-blog', 330, 215, true ); // (cropped)
add_filter( 'image_size_names_choose', 'ultimate_image_sizes' );
function ultimate_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'medium-image-blog' => __( 'Medium Blog Image', 'ultimate' ),
    ) );
}



// Retrive & Embed video from post
if ( ! function_exists( 'ultimate_post_video' ) ) :
function ultimate_post_video() {

	global $post;
	if (! $post)
		return false;
	ob_start();
	ob_end_clean();

	$html = '';

	if ( preg_match('/\[(\[?)(video)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/', $post->post_content, $matches)) {
		$html .= do_shortcode($matches[0]);	
	}
	elseif ( preg_match('/<iframe.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)) {
		$html .= '<iframe class="ultiamte-iframe" width="1280" height="720" src="';
		$html .= $matches[1];
		$html .= '" frameborder="0" allowfullscreen></iframe>';
	}
	elseif ( 
			preg_match('#https?://wordpress.tv/.*#i', $post->post_content, $matches) ||
			preg_match('#http://(www\.)?youtube\.com/watch.*#i', $post->post_content, $matches) ||
			preg_match('#https://(www\.)?youtube\.com/watch.*#i', $post->post_content, $matches) ||
			preg_match('#http://(www\.)?youtube\.com/playlist.*#i', $post->post_content, $matches) ||
			preg_match('#https://(www\.)?youtube\.com/playlist.*#i', $post->post_content, $matches) ||
			preg_match('#http://youtu\.be/.*#i', $post->post_content, $matches) ||
			preg_match('#https://youtu\.be/.*#i', $post->post_content, $matches) ||
			preg_match('#http://blip.tv/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(.+\.)?vimeo\.com/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?dailymotion\.com/.*#i', $post->post_content, $matches) ||
			preg_match('#http://dai.ly/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?funnyordie\.com/videos/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.)?hulu\.com/watch/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://(www\.|embed\.)?ted\.com/talks/.*#i', $post->post_content, $matches) ||
			preg_match('#https?://vine.co/v/.*#i', $post->post_content, $matches) 
		) {
			$embedurl = $matches[0];
			if (!empty($embedurl)) {
			       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
			}
			$html .= '<div class="blog-oembed">';
			$html .= $var;
			$html .= '</div>';
	}
	else {
		return false;
	}
	return $html;
}
endif;


// Embed Audio Post
if ( ! function_exists( 'ultimate_post_audio' ) ) :
	function ultimate_post_audio() { // for audio post type - grab

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();

		$html = '';

		if ( preg_match( '/<iframe.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ) {
			$html .= '<iframe class="ultiamte-audio-iframe" width="100%" height="350" src="';
			$html .= $matches[1];
			$html .= '" scrolling="no" frameborder="no"></iframe>';
		}
		elseif ( 
				preg_match('#https?://(www\.)?mixcloud\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(www\.)?rdio\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://rd\.io/x/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(www\.)?soundcloud\.com/.*#i', $post->post_content, $matches) ||
				preg_match('#https?://(open|play)\.spotify\.com/.*#i', $post->post_content, $matches)
			) {
				$embedurl = $matches[0];
				if (!empty($embedurl)) {
				       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
				}
				$html .= '<div class="blog-oembed">';
				$html .= $var;
				$html .= '</div>';
		}
		else {
			return false;
		}
		return $html;
	}

endif;


// Embed social data - Twitter
if ( ! function_exists( 'ultimate_post_social' ) ) :
	function ultimate_post_social() { // for social media embeds

		global $post;
		if (! $post)
			return false;
		ob_start();
		ob_end_clean();

		$html = '';

		if ( preg_match('#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i', $post->post_content, $matches) ) {
				$embedurl = $matches[0];
				if (!empty($embedurl)) {
				       $var = apply_filters('the_content', "[embed]" . $embedurl . "[/embed]");
				}
				$html .= '<div class="blog-oembed">';
				$html .= $var;
				$html .= '</div>';
		}
		else {
			return false;
		}
		return $html;
	}

endif;


// Sidebar Position
if ( ! function_exists( 'ultimate_sidebar_position' ) ) :
	function ultimate_sidebar_position() {

		$sidebar_pos = get_theme_mod('sidebar_position');
		if ($sidebar_pos != 'no-sidebar') :

			if ( !is_page_template( 'page-templates/ultimate-full-width.php' ) && !is_page_template( 'page-templates/front-page.php' )) :
				add_action('ult_content_after','get_sidebar', 10, 1);
			endif;

		endif;
	}
	add_action( 'wp', 'ultimate_sidebar_position' );
endif;

// Fevicom Image
if ( ! function_exists( 'ultimate_favicon' ) ) :
	function ultimate_favicon() {
		$favicom_image = get_theme_mod( 'favicon-img' );
		if ($favicom_image)
		echo '<link rel="icon" href="'. get_theme_mod( 'favicon-img' ) .'" type="image/x-png"/>';
	}
	add_action('ult_head_bottom', 'ultimate_favicon');
endif;

// Custom CSS
if ( ! function_exists( 'ultimate_custom_css' ) ) :
	function ultimate_custom_css() {
		$custom_css = get_theme_mod( 'custom_css' );
		if ($custom_css)
		echo '<style type="text/css">'. $custom_css .'</style>';
	}
	add_action('wp_head', 'ultimate_custom_css');
endif;

// Custom Script
if ( ! function_exists( 'ultimate_custom_script' ) ) :
	function ultimate_custom_script() {
		$custom_script = get_theme_mod( 'custom_script' );
		if ($custom_script)
		echo $custom_script;
	}
	add_action('wp_footer', 'ultimate_custom_script');
endif;

// Next / Previous post link on single page
if ( ! function_exists( 'ultimate_single_post_navigation' ) ) :
	function ultimate_single_post_navigation() { ?>
		<?php if(is_attachment()) : ?>
			<nav class="nav-single clear">
			<h3 class="assistive-text"><?php _e( 'Image navigation', 'ultimate' ); ?></h3>			
			<span class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr; Previous</span>', 'ultimate' ) ); ?></span>
			<span class="nav-next"><?php next_image_link( false, __( '<span class="meta-nav">Next &rarr;</span>', 'ultimate' ) ); ?></span>
			</nav><!-- .nav-single -->
		<?php elseif(is_single()) : ?>
			<nav class="nav-single clear">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'ultimate' ); ?></h3>
			<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'ultimate' ) . '</span> %title' ); ?></span>
			<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'ultimate' ) . '</span>' ); ?></span>
			</nav><!-- .nav-single -->
		<?php endif; ?>
		<?php
	} 
	add_action('ult_entry_after', 'ultimate_single_post_navigation');
endif;

// Header Text on Archive Pages
if ( ! function_exists( 'ultimate_archive_header_text' ) ) :
	function ultimate_archive_header_text() { ?>
		<?php if(is_archive()) : ?>

			<?php if(is_date()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'ultimate' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'ultimate' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ultimate' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'ultimate' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ultimate' ) ) . '</span>' );
						else :
							_e( 'Archives', 'ultimate' );
						endif;
					?></h1>
				</header><!-- .archive-header -->

			<?php elseif(is_category()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'ultimate' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
					<?php if ( category_description() ) : // Show an optional category description ?>
						<div class="archive-meta"><?php echo category_description(); ?></div>
					<?php endif; ?>
				</header><!-- .archive-header -->

			<?php elseif(is_tag()) : ?>

					<header class="archive-header">
						<h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', 'ultimate' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
						<?php if ( tag_description() ) : // Show an optional tag description ?>
							<div class="archive-meta"><?php echo tag_description(); ?></div>
						<?php endif; ?>
					</header><!-- .archive-header -->

			<?php elseif(is_author()) : ?>

				<header class="archive-header">
					<h1 class="archive-title"><?php printf( __( 'Author Archives: %s', 'ultimate' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
				</header><!-- .archive-header -->

				<?php
				// If a user has filled out their description, show a bio on their entries.
				if ( get_the_author_meta( 'description' ) ) : ?>
					<div class="author-info">
						<div class="author-avatar">
							<?php
								$author_bio_avatar_size = apply_filters( 'ultimate_author_bio_avatar_size', 68 );
								echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
							?>
						</div><!-- .author-avatar -->
						<div class="author-description">
							<h2><?php printf( __( 'About %s', 'ultimate' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- .author-description	-->
					</div><!-- .author-info -->
				<?php endif; ?>

			<?php endif; ?>

		<?php endif; ?>

		<?php
	}	
	add_action('ult_content_top', 'ultimate_archive_header_text');
endif;

// Pagination Position 
if ( ! function_exists( 'ultimate_pagination_position' ) ) :
	function ultimate_pagination_position() { ?>
		<?php if(is_archive() || is_search() || is_home()) : ?>
			<?php ultimate_pagination(); ?>
		<?php endif;
	}	
	add_action('ult_content_bottom', 'ultimate_pagination_position');
endif;

// Header Layout
if ( ! function_exists( 'ultimate_header_layout' ) ) :
	function ultimate_header_layout() { 
		$header_layout = get_theme_mod('header_layout');
		if($header_layout == 'header_2'){
			get_header('style2');
		} 
		else if($header_layout == 'header_3'){
			get_header('style3');
		} 
		else {
			get_header('style1');
		}
	}	
	add_action('ult_header_bottom', 'ultimate_header_layout');
endif;

// Title & Breadcrumb Bar
if ( ! function_exists( 'ultimate_title_breadcrumb_bar' ) ) :
	function ultimate_title_breadcrumb_bar() { ?>

		<?php
			global $post;
			$meta_value = get_post_meta( $post->ID, 'meta-breadcrumb', true );
			if($meta_value != 'false') :
				if(!is_home()) : ?>

					<div class="ultimate-page-header">
						<div class="ultimate-row">
							<div class="ultimate-container imd-pagetitle-container">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left ultimate-title">
									<?php
										if(is_404()){
											$title = '404 - Page Not Found!';
										} elseif(is_search()){
											$title = 'Search Results -';
										} elseif(is_archive()){
											$title = 'Archives';
										} else {
											if( is_home() && get_option('page_for_posts') ) {
												$blog_page_id = get_option('page_for_posts');
												$title = get_page($blog_page_id)->post_title;
											} else {
												$title = $post->post_title;
											}
										}
										echo '<div class="ultimate-breadcrumb-title">';
										echo '<h3>'.$title.'</h3>';
										echo '</div>';
									?>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right ultimate-breadcrumb">
									<?php
										if( function_exists('ultimate_breadcrumb')) {
											ultimate_breadcrumb();
										}
									?>
								</div>
							</div><!-- .ultimate-container --> 
						</div><!-- .ultimate-row --> 
					</div><!-- .ultimate-page-header --> 

				<?php endif; ?>
			<?php endif; ?>
		<?php 
	}	
	add_action('ult_header_after', 'ultimate_title_breadcrumb_bar');
endif;


// Author Bio
if ( ! function_exists( 'ultimate_author_bio' ) ) :
	function ultimate_author_bio() { ?>
		<?php if( is_single() ) : ?>		
			<footer class="entry-meta">
				<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
					<div class="author-info">
						<div class="author-avatar">
							<?php
							/** This filter is documented in author.php */
							$author_bio_avatar_size = apply_filters( 'ultimate_author_bio_avatar_size', 68 );
							echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
							?>
						</div><!-- .author-avatar -->
						<div class="author-description">
							<h2><?php printf( __( 'About %s', 'ultimate' ), get_the_author() ); ?></h2>
							<p><?php the_author_meta( 'description' ); ?></p>
							<div class="author-link">
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
									<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'ultimate' ), get_the_author() ); ?>
								</a>
							</div><!-- .author-link	-->
						</div><!-- .author-description -->
					</div><!-- .author-info -->
				<?php endif; ?>
			</footer><!-- .entry-meta -->
		<?php endif; ?>	
	<?php 
	}	
	add_action('ult_entry_bottom', 'ultimate_author_bio', 20, 1);
endif;

// Custom Search Form
if ( ! function_exists( 'ultimate_search_form' ) ) :
	function ultimate_search_form( $form ) {
		$value_placeholder = __( "type here..." , "ultimate" );
		$placeholder = __( "'type here...'" , "ultimate" );
		$empty_placeholder = __( "''" , "ultimate" );
		$form = '<form action="' . home_url( "/" ) . '" method="get" id="searchform">
				<fieldset>
				<div id="searchbox">
				<input class="input" name="s" type="text" id="s" value="'.  $value_placeholder .'" onfocus="if (this.value == '. $placeholder .') {this.value = '. $empty_placeholder .' }" onblur="if (this.value == '. $empty_placeholder .') {this.value = '. $placeholder .'}">
				<button type="submit" id="searchsubmit" class="ultimate-bkg ultimate-bkg-dark-hover"><i class="fa fa-search"></i></button>
				</div>
				</fieldset>
				</form>';
		return $form;
	}
	add_filter( 'get_search_form', 'ultimate_search_form' );
endif;


// Ultiamte Front Page Bottom Sidebar
if ( ! function_exists( 'ultimate_front_page_bottom_sidebar' ) ) :
	function ultimate_front_page_bottom_sidebar() {
		if (is_page_template( 'page-templates/front-page.php' )) {
			get_sidebar('front');
		}
	}
	add_action('ult_content_after', 'ultimate_front_page_bottom_sidebar', 20, 1);
endif;



// Ultiamte Front Page Content Sidebar
if ( ! function_exists( 'ultimate_front_page_content_sidebar' ) ) :
	function ultimate_front_page_content_sidebar() { 
		?>
		<?php if ( is_active_sidebar( 'sidebar-front-main' ) ) : ?>
			<div class="frontpage-main-widget-area clear">
				<?php dynamic_sidebar( 'sidebar-front-main' ); ?>
			</div><!-- .first -->
		<?php endif; ?>
		<?php
	}
	add_action('ult_entry_after', 'ultimate_front_page_content_sidebar', 10, 1);
endif;


?>