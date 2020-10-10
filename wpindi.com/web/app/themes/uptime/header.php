<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="//schema.org/WebPage" data-smooth-scroll-offset="<?php echo esc_attr( get_theme_mod( 'smooth_scroll_offset', '0' ) ); ?>">
	
<?php 
	// WP 5.2 new action hook
	do_action( 'wp_body_open' );
	
	get_template_part( 'inc/content', 'preloader' ); 
?>

<div class="navbar-container">
	<?php 
		/**
		 * Get header layout by theme option
		 * Overrides handled by theme_filters by pre-filtering the theme_mod call
		 */
		get_template_part( 'inc/layout-header', get_theme_mod( 'header_layout', 'dark' ) ); 
	?>
</div>