<?php
	get_header();
	the_post();
	
	// Reading Progress Counter
	get_template_part( 'inc/content-post', 'progress' );
	
	// Posts Specific Hero Area with Option Switch
	get_template_part( 'inc/content-post-single-hero', get_theme_mod( 'post_single_hero_layout', 'basic' ) );
	
	// Standard Post Content
	get_template_part( 'inc/content' );
	
	// Standard Comments and Sharing
	comments_template();
	
	// Related Posts
	get_template_part( 'inc/content-post', 'related' );
	
	get_footer();