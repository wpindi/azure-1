<?php 
	/**
	 * Get footer layout by theme option
	 * Overrides handled by theme_filters by pre-filtering the theme_mod call
	 */
	get_template_part( 'inc/layout-footer', get_theme_mod( 'footer_layout', '1' ) ); 
	
	/**
	 * Include the back to top button markup
	 */
	get_template_part( 'inc/content', 'back-to-top' ); 
	
	wp_footer(); 
?>
</body>
</html>