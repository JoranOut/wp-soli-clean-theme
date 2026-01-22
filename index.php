<?php
/**
 * Main template file - User Dashboard.
 *
 * @package Soli_Clean_Theme
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<main class="soli-admin-dashboard">
    <?php
    /**
     * Fires before the main content area.
     *
     * @since 1.0.0
     */
    do_action( 'soli_admin_before_content' );

    /**
     * Fires in the main content area.
     *
     * @since 1.0.0
     */
    do_action( 'soli_admin_content' );

    /**
     * Fires after the main content area.
     *
     * @since 1.0.0
     */
    do_action( 'soli_admin_after_content' );
    ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
