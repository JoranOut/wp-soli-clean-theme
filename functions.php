<?php
/**
 * Soli Clean Theme functions and definitions.
 *
 * @package Soli_Clean_Theme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version constant.
 *
 * @since 1.0.0
 */
define( 'SOLI_CLEAN_THEME_VERSION', '1.0.1' );

/**
 * Theme setup.
 *
 * @since 1.0.0
 */
function soli_admin_theme_setup(): void {
    load_theme_textdomain( 'soli-clean-theme', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'soli_admin_theme_setup' );

/**
 * Enqueue theme styles.
 *
 * @since 1.0.0
 */
function soli_admin_theme_enqueue_styles(): void {
    wp_enqueue_style(
        'soli-clean-theme-style',
        get_stylesheet_uri(),
        array(),
        SOLI_CLEAN_THEME_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'soli_admin_theme_enqueue_styles' );

/**
 * Redirect non-authenticated users to the login page.
 *
 * @since 1.0.0
 */
function soli_admin_theme_require_login(): void {
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( wp_login_url( home_url() ) );
        exit;
    }
}
add_action( 'template_redirect', 'soli_admin_theme_require_login' );

/**
 * Load text domain on login page.
 *
 * @since 1.0.0
 */
function soli_admin_theme_login_init(): void {
    load_textdomain(
        'soli-clean-theme',
        get_template_directory() . '/languages/soli-clean-theme-' . determine_locale() . '.mo'
    );
}
add_action( 'login_init', 'soli_admin_theme_login_init' );

/**
 * Enqueue login page styles.
 *
 * @since 1.0.0
 */
function soli_admin_theme_login_styles(): void {
    wp_enqueue_style(
        'soli-clean-theme-login',
        get_stylesheet_uri(),
        array(),
        SOLI_CLEAN_THEME_VERSION
    );
}
add_action( 'login_enqueue_scripts', 'soli_admin_theme_login_styles' );

/**
 * Add custom heading above the login form.
 *
 * @since 1.0.0
 * @param string|null $message Login message.
 * @return string
 */
function soli_admin_theme_login_message( ?string $message ): string {
    $title    = get_bloginfo( 'name' );
    $subtitle = get_bloginfo( 'description' );

    $custom_header  = '<h1 class="soli-login-title">' . esc_html( $title ) . '</h1>';
    $custom_header .= '<p class="soli-login-subtitle">' . esc_html( $subtitle ) . '</p>';

    return $custom_header . ( $message ?? '' );
}
add_filter( 'login_message', 'soli_admin_theme_login_message' );

/**
 * Change login header URL.
 *
 * @since 1.0.0
 * @return string
 */
function soli_admin_theme_login_header_url(): string {
    return home_url();
}
add_filter( 'login_headerurl', 'soli_admin_theme_login_header_url' );

/**
 * Change login header text.
 *
 * @since 1.0.0
 * @return string
 */
function soli_admin_theme_login_header_text(): string {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'soli_admin_theme_login_header_text' );

/**
 * Remove posts, pages, media, and comments from admin menu.
 *
 * @since 1.0.0
 */
function soli_admin_theme_remove_menus(): void {
    remove_menu_page( 'edit.php' );              // Posts.
    remove_menu_page( 'edit.php?post_type=page' ); // Pages.
    remove_menu_page( 'upload.php' );            // Media.
    remove_menu_page( 'edit-comments.php' );     // Comments.
}
add_action( 'admin_menu', 'soli_admin_theme_remove_menus', 999 );

/**
 * Remove items from admin bar.
 *
 * @since 1.0.0
 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
 */
function soli_admin_theme_remove_admin_bar_items( WP_Admin_Bar $wp_admin_bar ): void {
    $wp_admin_bar->remove_node( 'new-post' );
    $wp_admin_bar->remove_node( 'new-page' );
    $wp_admin_bar->remove_node( 'new-media' );
    $wp_admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'soli_admin_theme_remove_admin_bar_items', 999 );

/**
 * Block access to posts, pages, media, and comments admin pages.
 *
 * @since 1.0.0
 */
function soli_admin_theme_block_admin_pages(): void {
    global $pagenow;

    $blocked_pages = array(
        'edit.php',
        'post.php',
        'post-new.php',
        'upload.php',
        'media-new.php',
        'edit-comments.php',
        'comment.php',
    );

    if ( in_array( $pagenow, $blocked_pages, true ) ) {
        // Allow pages post type check.
        if ( 'edit.php' === $pagenow || 'post-new.php' === $pagenow ) {
            $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
            if ( 'post' === $post_type || 'page' === $post_type ) {
                wp_safe_redirect( admin_url() );
                exit;
            }
        } elseif ( 'post.php' === $pagenow ) {
            $post_id   = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
            $post_type = $post_id ? get_post_type( $post_id ) : 'post';
            if ( 'post' === $post_type || 'page' === $post_type ) {
                wp_safe_redirect( admin_url() );
                exit;
            }
        } else {
            wp_safe_redirect( admin_url() );
            exit;
        }
    }
}
add_action( 'admin_init', 'soli_admin_theme_block_admin_pages' );

/**
 * Disable comment support.
 *
 * @since 1.0.0
 */
function soli_admin_theme_disable_comments(): void {
    // Remove comment support from all post types.
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}
add_action( 'admin_init', 'soli_admin_theme_disable_comments' );

/**
 * Close comments on the front-end.
 *
 * @since 1.0.0
 * @return bool
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Hide existing comments.
 *
 * @since 1.0.0
 * @return array
 */
add_filter( 'comments_array', '__return_empty_array', 10 );

/**
 * Default front-end content.
 *
 * Displays basic user information. Plugins can disable this by filtering
 * 'soli_admin_show_default_content' to false when they provide their own content.
 *
 * @since 1.0.0
 */
function soli_admin_default_content(): void {
	/**
	 * Filters whether to show the default user info content.
	 *
	 * Plugins should return false when they provide their own front-end content.
	 *
	 * @since 1.0.0
	 * @param bool $show Whether to show default content. Default true.
	 */
	if ( ! apply_filters( 'soli_admin_show_default_content', true ) ) {
		return;
	}
	$current_user = wp_get_current_user();
	?>
	<h1><?php esc_html_e( 'Soli Administration', 'soli-clean-theme' ); ?></h1>
	<p class="soli-subtitle"><?php esc_html_e( 'Your account information', 'soli-clean-theme' ); ?></p>

	<div class="soli-user-info">
		<div class="soli-user-field">
			<label><?php esc_html_e( 'Username', 'soli-clean-theme' ); ?></label>
			<span><?php echo esc_html( $current_user->user_login ); ?></span>
		</div>

		<div class="soli-user-field">
			<label><?php esc_html_e( 'Email address', 'soli-clean-theme' ); ?></label>
			<span><?php echo esc_html( $current_user->user_email ); ?></span>
		</div>
	</div>

	<div class="soli-actions">
		<a href="<?php echo esc_url( wp_lostpassword_url( home_url() ) ); ?>" class="soli-btn-primary">
			<?php esc_html_e( 'Reset password', 'soli-clean-theme' ); ?>
		</a>
		<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="soli-btn-secondary">
			<?php esc_html_e( 'Log out', 'soli-clean-theme' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'soli_admin_content', 'soli_admin_default_content' );

/**
 * Initialize GitHub theme updater.
 *
 * @since 1.0.0
 */
function soli_admin_theme_github_updater(): void {
	include_once get_template_directory() . '/updater.php';

	if ( class_exists( 'Soli\CleanTheme\WP_GitHub_Theme_Updater' ) ) {
		$config = array(
			'slug'         => 'wp-soli-clean-theme',
			'api_url'      => 'https://api.github.com/repos/Muziekvereniging-Soli/wp-soli-clean-theme',
			'raw_url'      => 'https://raw.github.com/Muziekvereniging-Soli/wp-soli-clean-theme/main',
			'github_url'   => 'https://github.com/Muziekvereniging-Soli/wp-soli-clean-theme',
			'zip_url'      => 'https://github.com/Muziekvereniging-Soli/wp-soli-clean-theme/releases/latest/download/wp-soli-clean-theme.zip',
			'requires'     => '6.0.0',
			'tested'       => '6.7.0',
			'requires_php' => '8.0',
			'readme'       => 'README.md',
		);

		new \Soli\CleanTheme\WP_GitHub_Theme_Updater( $config );
	}
}
add_action( 'init', 'soli_admin_theme_github_updater' );
