<?php
require_once get_theme_file_path( '/inc/helpers.php' );
require_once get_theme_file_path( '/inc/sanitization.php' );
require_once get_theme_file_path( '/inc/functions.php' );
require_once get_theme_file_path( '/inc/helpers-post-meta.php' );
require_once get_theme_file_path( '/inc/customizer.php' );
require_once get_theme_file_path( '/inc/customizer-styles.php' );

add_action( 'after_setup_theme', 'brittany_light_setup' );
if ( ! function_exists( 'brittany_light_setup' ) ) :
function brittany_light_setup() {

	if ( ! defined( 'BRITTANY_LIGHT_NAME' ) ) {
		define( 'BRITTANY_LIGHT_NAME', 'brittany-light' );
	}

	// Default content width.
	$GLOBALS['content_width'] = 750;

	load_theme_textdomain( 'brittany-light', get_template_directory() . '/languages' );

	/*
	 * Theme supports.
	 */
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_theme_support( 'custom-logo', array(
		'height'      => 90,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	/*
	 * Image sizes.
	 */
	set_post_thumbnail_size( 750, 460, true );
	add_image_size( 'brittany_light_square', 300, 300, true );
	add_image_size( 'brittany_light_about', 360, 220, true );


	/*
	 * Navigation menus.
	 */
	register_nav_menus( array(
		'main_menu' => esc_html__( 'Main Menu', 'brittany-light' ),
	) );


	/*
	 * Default hooks
	 */
	// Prints the inline JS scripts that are registered for printing, and removes them from the queue.
	add_action( 'admin_footer', 'brittany_light_print_inline_js' );
	add_action( 'wp_footer', 'brittany_light_print_inline_js' );

	// Handle the dismissible sample content notice.
	add_action( 'wp_ajax_brittany_light_dismiss_sample_content', 'brittany_light_ajax_dismiss_sample_content' );

	// This provides back-compat for author descriptions on WP < 4.9. Remove by WP 5.1.
	if ( ! has_filter( 'get_the_author_description', 'wpautop' ) ) {
		add_filter( 'get_the_author_description', 'wpautop' );
	}
}
endif;



add_action( 'wp_enqueue_scripts', 'brittany_light_enqueue_scripts' );
function brittany_light_enqueue_scripts() {

	/*
	 * Styles
	 */
	$font_url = '';
	/* translators: If there are characters in your language that are not supported by Work Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Work Sans font: on or off', 'brittany-light' ) ) {
		$font_url = add_query_arg( 'family', 'Work+Sans:400,500,600,700', '//fonts.googleapis.com/css' );
	}
	wp_register_style( 'brittany-light-google-font', esc_url( $font_url ) );

	wp_register_style( 'brittany-light-base', get_theme_file_uri( '/css/base.css' ), array(), brittany_light_asset_version() );
	wp_register_style( 'flexslider', get_theme_file_uri( '/css/flexslider.css' ), array(), brittany_light_asset_version( '2.5.0' ) );
	wp_register_style( 'mmenu', get_theme_file_uri( '/css/mmenu.css' ), array(), brittany_light_asset_version( '5.5.3' ) );
	wp_register_style( 'font-awesome-5', get_theme_file_uri( '/assets/fontawesome/css/all.min.css' ), array(), brittany_light_asset_version( '5.7.2' ) );
	wp_register_style( 'magnific-popup', get_theme_file_uri( '/css/magnific.css' ), array(), brittany_light_asset_version( '1.0.0' ) );
	wp_register_style( 'slick', get_theme_file_uri( '/css/slick.css' ), array(), brittany_light_asset_version( '1.5.7' ) );

	wp_register_style( 'brittany-light-dependencies', false, array(
		'brittany-light-google-font',
		'brittany-light-base',
		'brittany-light-common',
		'flexslider',
		'mmenu',
		'font-awesome-5',
		'magnific-popup',
		'slick',
	), brittany_light_asset_version() );

	if ( is_child_theme() ) {
		wp_enqueue_style( 'brittany-light-style-parent', get_template_directory_uri() . '/style.css', array(
			'brittany-light-dependencies',
		), brittany_light_asset_version() );
	}

	wp_enqueue_style( 'brittany-light-style', get_stylesheet_uri(), array(
		'brittany-light-dependencies',
	), brittany_light_asset_version() );

	/*
	 * Scripts
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 'superfish', get_theme_file_uri( '/js/superfish.js' ), array( 'jquery' ), brittany_light_asset_version( '1.7.5' ), true );
	wp_register_script( 'mmenu-autoheight', get_theme_file_uri( '/js/jquery.mmenu.autoheight.js' ), array( 'jquery' ), brittany_light_asset_version( '5.5.3' ), true );
	wp_register_script( 'mmenu-navbars', get_theme_file_uri( '/js/jquery.mmenu.navbars.js' ), array( 'jquery' ), brittany_light_asset_version( '5.5.3' ), true );
	wp_register_script( 'mmenu-offcanvas', get_theme_file_uri( '/js/jquery.mmenu.offcanvas.js' ), array( 'jquery' ), brittany_light_asset_version( '5.5.3' ), true );
	wp_register_script( 'mmenu-oncanvas', get_theme_file_uri( '/js/jquery.mmenu.oncanvas.js' ), array( 'jquery' ), brittany_light_asset_version( '5.5.3' ), true );
	wp_register_script( 'flexslider', get_theme_file_uri( '/js/jquery.flexslider.js' ), array( 'jquery' ), brittany_light_asset_version( '2.5.0' ), true );
	wp_register_script( 'fitVids', get_theme_file_uri( '/js/jquery.fitvids.js' ), array( 'jquery' ), brittany_light_asset_version( '1.1' ), true );
	wp_register_script( 'slick', get_theme_file_uri( '/js/slick.js' ), array( 'jquery' ), brittany_light_asset_version( '1.5.7' ), true );
	wp_register_script( 'magnific-popup', get_theme_file_uri( '/js/jquery.magnific-popup.js' ), array( 'jquery' ), brittany_light_asset_version( '1.0.0' ), true );
	wp_register_script( 'isotope', get_theme_file_uri( '/js/isotope.js' ), array( 'jquery' ), brittany_light_asset_version( '2.2.2' ), true );

	/*
	 * Enqueue
	 */
	wp_enqueue_script( 'brittany-light-front-scripts', get_theme_file_uri( '/js/scripts.js' ), array(
		'jquery',
		'superfish',
		'mmenu-oncanvas',
		'mmenu-offcanvas',
		'mmenu-navbars',
		'mmenu-autoheight',
		'flexslider',
		'fitVids',
		'slick',
		'magnific-popup',
		'isotope',
	), brittany_light_asset_version(), true );

}

add_action( 'admin_enqueue_scripts', 'brittany_light_admin_enqueue_scripts' );
function brittany_light_admin_enqueue_scripts( $hook ) {
	/*
	 * Styles
	 */


	/*
	 * Scripts
	 */


	/*
	 * Enqueue
	 */
	if ( in_array( $hook, array( 'widgets.php', 'customize.php' ), true ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'brittany-light-post-meta' );
		wp_enqueue_script( 'brittany-light-post-meta' );
	}

}

add_action( 'customize_controls_enqueue_scripts', 'brittany_light_enqueue_customizer_styles' );
function brittany_light_enqueue_customizer_styles() {
	$theme = wp_get_theme();

	wp_register_style( 'brittany-light-customizer-styles', get_template_directory_uri() . '/css/admin/customizer-styles.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'brittany-light-customizer-styles' );
}


add_action( 'widgets_init', 'brittany_light_widgets_init' );
function brittany_light_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html_x( 'Blog', 'widget area', 'brittany-light' ),
		'id'            => 'blog',
		'description'   => esc_html__( 'This is the main sidebar.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Pages', 'widget area', 'brittany-light' ),
		'id'            => 'page',
		'description'   => esc_html__( 'This sidebar appears on your static pages. If empty, the Blog sidebar will be shown instead.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Pre-footer Instagram', 'widget area', 'brittany-light' ),
		'id'            => 'prefooter-widgets',
		'description'   => esc_html__( 'Special site-wide area for the WP Instagram Widget plugin.', 'brittany-light' ),
		'before_widget' => '<section id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Footer - Column 1', 'widget area', 'brittany-light' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'First column on footer. Wide.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Footer - Column 2', 'widget area', 'brittany-light' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Second column on footer. Narrow.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Footer - Column 3', 'widget area', 'brittany-light' ),
		'id'            => 'footer-3',
		'description'   => esc_html__( 'Third column on footer. Narrow.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Footer - Column 4', 'widget area', 'brittany-light' ),
		'id'            => 'footer-4',
		'description'   => esc_html__( 'Fourth column on footer. Wide.', 'brittany-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'brittany_light_load_widgets' );
function brittany_light_load_widgets() {
	require_once get_theme_file_path( '/inc/widgets/about-me.php' );
	require_once get_theme_file_path( '/inc/widgets/latest-posts.php' );
	require_once get_theme_file_path( '/inc/widgets/socials.php' );
}

add_filter( 'excerpt_length', 'brittany_light_excerpt_length' );
function brittany_light_excerpt_length( $length ) {
	return get_theme_mod( 'excerpt_length', 55 );
}


add_filter( 'wp_page_menu', 'brittany_light_wp_page_menu', 10, 2 );
function brittany_light_wp_page_menu( $menu, $args ) {
	preg_match( '#^<div class="(.*?)">(?:.*?)</div>$#', $menu, $matches );
	$menu = preg_replace( '#^<div class=".*?">#', '', $menu, 1 );
	$menu = preg_replace( '#</div>$#', '', $menu, 1 );
	$menu = preg_replace( '#^<ul>#', '<ul class="' . esc_attr( $args['menu_class'] ) . '">', $menu, 1 );
	return $menu;
}


add_filter( 'the_content', 'brittany_light_lightbox_rel', 12 );
add_filter( 'get_comment_text', 'brittany_light_lightbox_rel' );
add_filter( 'wp_get_attachment_link', 'brittany_light_lightbox_rel' );
if ( ! function_exists( 'brittany_light_lightbox_rel' ) ) :
function brittany_light_lightbox_rel( $content ) {
	global $post;
	$pattern     = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 data-lightbox="gal[' . $post->ID . ']"$6>$7</a>';
	$content     = preg_replace( $pattern, $replacement, $content );

	return $content;
}
endif;


//
// Resize logo to half its width
//
add_filter( 'get_custom_logo', 'brittany_light_resize_custom_logo' );
if ( ! function_exists( 'brittany_light_resize_custom_logo' ) ) {
	function brittany_light_resize_custom_logo( $html ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( ! get_theme_mod( 'limit_logo_size' ) || empty( $custom_logo_id ) ) {
			return $html;
		}

		$image_metadata = wp_get_attachment_metadata( $custom_logo_id );

		$html = str_replace( '<img', sprintf( '<img style="max-width: %dpx"', floor( $image_metadata['width'] / 2 ) ), $html );
		return $html;
	}
}


if ( ! function_exists( 'brittany_light_get_social_networks' ) ) :
function brittany_light_get_social_networks() {
	return apply_filters( 'brittany_light_social_networks', array(
		array(
			'name'  => 'facebook',
			'label' => esc_html__( 'Facebook', 'brittany-light' ),
			'icon'  => 'fab fa-facebook',
		),
		array(
			'name'  => 'twitter',
			'label' => esc_html__( 'Twitter', 'brittany-light' ),
			'icon'  => 'fab fa-twitter',
		),
		array(
			'name'  => 'pinterest',
			'label' => esc_html__( 'Pinterest', 'brittany-light' ),
			'icon'  => 'fab fa-pinterest',
		),
		array(
			'name'  => 'instagram',
			'label' => esc_html__( 'Instagram', 'brittany-light' ),
			'icon'  => 'fab fa-instagram',
		),
		array(
			'name'  => 'snapchat',
			'label' => esc_html__( 'Snapchat', 'brittany-light' ),
			'icon'  => 'fab fa-snapchat',
		),
		array(
			'name'  => 'behance',
			'label' => esc_html__( 'Behance', 'brittany-light' ),
			'icon'  => 'fab fa-behance',
		),
		array(
			'name'  => 'dribbble',
			'label' => esc_html__( 'Dribbble', 'brittany-light' ),
			'icon'  => 'fab fa-dribbble',
		),
		array(
			'name'  => 'youtube',
			'label' => esc_html__( 'YouTube', 'brittany-light' ),
			'icon'  => 'fab fa-youtube',
		),
		array(
			'name'  => 'etsy',
			'label' => esc_html__( 'Etsy', 'brittany-light' ),
			'icon'  => 'fab fa-etsy',
		),
		array(
			'name'  => 'flickr',
			'label' => esc_html__( 'Flickr', 'brittany-light' ),
			'icon'  => 'fab fa-flickr',
		),
		array(
			'name'  => 'github',
			'label' => esc_html__( 'GitHub', 'brittany-light' ),
			'icon'  => 'fab fa-github',
		),
		array(
			'name'  => 'linkedin',
			'label' => esc_html__( 'LinkedIn', 'brittany-light' ),
			'icon'  => 'fab fa-linkedin',
		),
		array(
			'name'  => 'medium',
			'label' => esc_html__( 'Medium', 'brittany-light' ),
			'icon'  => 'fab fa-medium',
		),
		array(
			'name'  => 'mixcloud',
			'label' => esc_html__( 'Mixcloud', 'brittany-light' ),
			'icon'  => 'fab fa-mixcloud',
		),
		array(
			'name'  => 'paypal',
			'label' => esc_html__( 'PayPal', 'brittany-light' ),
			'icon'  => 'fab fa-paypal',
		),
		array(
			'name'  => 'skype',
			'label' => esc_html__( 'Skype', 'brittany-light' ),
			'icon'  => 'fab fa-skype',
		),
		array(
			'name'  => 'slack',
			'label' => esc_html__( 'Slack', 'brittany-light' ),
			'icon'  => 'fab fa-slack',
		),
		array(
			'name'  => 'soundcloud',
			'label' => esc_html__( 'Soundcloud', 'brittany-light' ),
			'icon'  => 'fab fa-soundcloud',
		),
		array(
			'name'  => 'spotify',
			'label' => esc_html__( 'Spotify', 'brittany-light' ),
			'icon'  => 'fab fa-spotify',
		),
		array(
			'name'  => 'vimeo',
			'label' => esc_html__( 'Vimeo', 'brittany-light' ),
			'icon'  => 'fab fa-vimeo',
		),
		array(
			'name'  => 'wordpress',
			'label' => esc_html__( 'WordPress', 'brittany-light' ),
			'icon'  => 'fab fa-wordpress',
		),
		array(
			'name'  => 'xbox',
			'label' => esc_html__( 'Xbox Live', 'brittany-light' ),
			'icon'  => 'fab fa-xbox',
		),
		array(
			'name'  => 'playstation',
			'label' => esc_html__( 'PlayStation Network', 'brittany-light' ),
			'icon'  => 'fab fa-playstation',
		),
		array(
			'name'  => 'bloglovin',
			'label' => esc_html__( 'Bloglovin', 'brittany-light' ),
			'icon'  => 'fas fa-heart',
		),
		array(
			'name'  => 'tumblr',
			'label' => esc_html__( 'Tumblr', 'brittany-light' ),
			'icon'  => 'fab fa-tumblr',
		),
		array(
			'name'  => '500px',
			'label' => esc_html__( '500px', 'brittany-light' ),
			'icon'  => 'fab fa-500px',
		),
		array(
			'name'  => 'tripadvisor',
			'label' => esc_html__( 'Trip Advisor', 'brittany-light' ),
			'icon'  => 'fab fa-tripadvisor',
		),
		array(
			'name'  => 'telegram',
			'label' => esc_html__( 'Telegram', 'brittany-light' ),
			'icon'  => 'fab fa-telegram',
		),
	) );
}
endif;



add_action( 'wpiw_before_widget', 'brittany_light_wpiw_before_widget' );
function brittany_light_wpiw_before_widget() {
	?><div data-auto="<?php echo esc_attr( get_theme_mod( 'instagram_auto', 1 ) ); ?>" data-speed="<?php echo esc_attr( get_theme_mod( 'instagram_speed', 300 ) ); ?>"><?php
}
add_action( 'wpiw_after_widget', 'brittany_light_wpiw_after_widget' );
function brittany_light_wpiw_after_widget() {
	?></div><?php
}


add_filter( 'post_class', 'brittany_light_add_no_thumbnail_class', 10, 3 );
function brittany_light_add_no_thumbnail_class( $classes, $class, $post_id ) {
	if ( ! has_post_thumbnail() ) {
		$classes[] = 'entry-no-post-thumbnail';
	}

	return $classes;
}

/**
 * Include a skip to content link at the top of the page so that users can bypass the menu.
 */
add_action( 'wp_body_open', 'brittany_light_skip_link', 5 );
function brittany_light_skip_link() {
	?><div><a class="skip-link sr-only sr-only-focusable" href="#site-content"><?php esc_html_e( 'Skip to the content', 'brittany-light' ); ?></a></div><?php
}


//
// Comment form
//
add_filter( 'comment_form_field_comment', 'brittany_light_comment_form_field_comment' );
function brittany_light_comment_form_field_comment( $field ) {
	return '<div class="row"><div class="col-xs-12">' . $field . '</div></div>';
}

add_action( 'comment_form_before_fields', 'brittany_light_comment_form_before_fields' );
function brittany_light_comment_form_before_fields() {
	echo '<div class="row">';
}

add_action( 'comment_form_after_fields', 'brittany_light_comment_form_after_fields' );
function brittany_light_comment_form_after_fields() {
	echo '</div>';
}

add_filter( 'comment_form_field_author', 'brittany_light_comment_form_field_wrap' );
add_filter( 'comment_form_field_email', 'brittany_light_comment_form_field_wrap' );
add_filter( 'comment_form_field_url', 'brittany_light_comment_form_field_wrap' );
function brittany_light_comment_form_field_wrap( $field ) {
	if ( is_singular( 'product' ) ) {
		return '<div class="col-xs-12">' . trim( $field ) . '</div>' . "\n";
	}

	return '<div class="col-md-4">' . trim( $field ) . '</div>' . "\n";
}

add_filter( 'comment_form_field_cookies', 'brittany_light_comment_form_cookie_wrap' );
function brittany_light_comment_form_cookie_wrap( $field ) {
	return '<div class="col-xs-12">' . trim( $field ) . '</div>' . "\n";
}

add_filter( 'comment_form_submit_field', 'brittany_light_comment_form_submit_field_wrap' );
function brittany_light_comment_form_submit_field_wrap( $field ) {
	return '<div class="row"><div class="col-md-4 col-md-offset-4">' . trim( $field ) . '</div></div>' . "\n";
}

add_filter( 'comment_form_default_fields', 'brittany_light_comment_form_default_fields_placeholder' );
function brittany_light_comment_form_default_fields_placeholder( $fields ) {
	$new_class = 'sr-only';

	foreach ( $fields as $key => $field ) {
		if ( preg_match( '/\<input.*?type="checkbox"/', $field ) ) {
			continue;
		}

		preg_match( '/\<label.*?\>/', $field, $label );
		if ( ! empty( $label[0] ) ) {
			preg_match( '/class=([\'"])(.*?)(\1)/', $label[0], $label_classes );

			$new_field = '';

			if ( ! empty( $label_classes ) && isset( $label_classes[2] ) ) {
				$all_classes    = explode( ' ', $label_classes[2] . ' ' . $new_class );
				$all_classes    = array_filter( array_unique( $all_classes ) );
				$new_class_attr = sprintf( 'class="%s"', esc_attr( implode( ' ', $all_classes ) ) );
				$new_label      = str_replace( $label_classes[0], $new_class_attr, $label[0] );
				$new_field      = str_replace( $label[0], $new_label, $field );
			} else {
				$new_class_attr = sprintf( 'class="%s"', esc_attr( $new_class ) );
				$new_field      = str_replace( '<label', '<label ' . $new_class_attr . ' ', $field );
			}

			preg_match( '#\<label.*?\>(.*?)\</label\>#', $new_field, $label_text );
			if ( ! empty( $label_text[1] ) ) {
				$text      = strip_tags( $label_text[1] );
				$new_field = str_replace( '<input', '<input placeholder="' . esc_attr( $text ) . '" ', $new_field );
			}
			$fields[ $key ] = $new_field;
		}
	}

	return $fields;
}

add_filter( 'comment_form_field_comment', 'brittany_light_comment_form_field_comment_placeholder' );
function brittany_light_comment_form_field_comment_placeholder( $field ) {
	$new_class = 'sr-only';

	preg_match( '/\<label.*?\>/', $field, $label );
	if ( ! empty( $label[0] ) ) {
		preg_match( '/class=([\'"])(.*?)(\1)/', $label[0], $label_classes );

		$new_field = '';

		if ( ! empty( $label_classes ) && isset( $label_classes[2] ) ) {
			$all_classes    = explode( ' ', $label_classes[2] . ' ' . $new_class );
			$all_classes    = array_filter( array_unique( $all_classes ) );
			$new_class_attr = sprintf( 'class="%s"', esc_attr( implode( ' ', $all_classes ) ) );
			$new_label      = str_replace( $label_classes[0], $new_class_attr, $label[0] );
			$new_field      = str_replace( $label[0], $new_label, $field );
		} else {
			$new_class_attr = sprintf( 'class="%s"', esc_attr( $new_class ) );
			$new_field      = str_replace( '<label', '<label ' . $new_class_attr . ' ', $field );
		}

		preg_match( '#\<label.*?\>(.*?)\</label\>#', $new_field, $label_text );
		if ( ! empty( $label_text[1] ) ) {
			$text      = strip_tags( $label_text[1] );
			$new_field = str_replace( '<textarea', '<textarea placeholder="' . esc_attr( $text ) . '" ', $new_field );
		}
		$field = $new_field;
	}

	return $field;
}

add_filter( 'brittany_light_sample_content_url', 'brittany_light_sample_content_url', 3, 10 );
if ( ! function_exists( 'brittany_light_sample_content_url' ) ) :
	function brittany_light_sample_content_url( $url, $base_url, $theme_name ) {
		return sprintf( 'https://www.cssigniter.com/docs/%s/#importing-sample-content', $theme_name );
	}
endif;

function brittany_light_the_post_entry_sticky_label() {
	if ( 'post' !== get_post_type() ) {
		return;
	}

	if ( ! is_singular() && is_sticky() && is_home() && ! is_paged() ) {
		?>
		<span class="entry-meta-item entry-sticky">
			<?php esc_html_e( 'Featured', 'brittany-light' ); ?>
		</span>
		<?php
	}
}


/**
 * Common theme features.
 */
require_once get_theme_file_path( '/common/common.php' );

/**
 * User onboarding.
 */
require_once get_theme_file_path( '/inc/onboarding.php' );
