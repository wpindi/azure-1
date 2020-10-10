<?php

/**
 * WordPress' missing is_blog_page() function.  Determines if the currently viewed page is
 * one of the blog pages, including the blog home page, archive, category/tag, author, or single
 * post pages.
 *
 * @see http://core.trac.wordpress.org/browser/tags/3.4.1/wp-includes/query.php#L1572
 *
 * @return bool
 */
function tommusrhodus_is_blog_page(){ 
    global $post;
    $post_type = get_post_type( $post );
    return ( ( is_home() || is_archive() || is_single() ) && ( $post_type == 'post' ) ) ? true : false ;
}

function tommusrhodus_page_title( $suptitle = false, $title = false, $subtitle = false ){
	
	// Escape early if none of the atts have values
	if( false == $suptitle && false == $title && false == $subtitle ){
		return false;
	}
	
	$output = '
		<div class="row justify-content-center text-center section-intro">
		    <div class="col-12 col-md-9 col-lg-8">
	';

		if( $suptitle ){
    		$output .= '<span class="title-decorative">'. wp_kses_post( $suptitle ) .'</span>';
		}
		
		if( $title ){
			$output .= '<h2 class="display-4">'. wp_kses_post( $title ) .'</h2>';
		}
		
		if( $subtitle ){
			$output .= '<span class="lead">'. wp_kses_post( $subtitle ) .'</span>';
		}
					
	$output .= '
		    </div><!--end of col-->
		</div><!--end of row-->
	';
	
	return $output;
	
}

/**
 * tommusrhodus_update_comment_upvotes()
 * 
 * Update the comment upvote count from an AJAX call.
 * Returns the updated count number.
 * 
 * @param $comment_id -- ID of the comment object
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_update_comment_upvotes' ) )){
	function tommusrhodus_update_comment_upvotes(){

		if(!( isset( $_POST['comment_id'] ) )){
			wp_die();
		}
		
		$comment_id = $_POST['comment_id'];
		$count      = get_comment_meta( $comment_id, 'tommusrhodus_comment_upvotes', 1 ) + 1;
		
		update_comment_meta( $comment_id, 'tommusrhodus_comment_upvotes', $count );
		
		wp_die( $count );
		
	}
	add_action( 'wp_ajax_tommusrhodus_update_comment_upvotes', 'tommusrhodus_update_comment_upvotes' );
	add_action( 'wp_ajax_nopriv_tommusrhodus_update_comment_upvotes', 'tommusrhodus_update_comment_upvotes' );
}

/**
 * tommusrhodus_update_docs_upvotes()
 * 
 * Update the post upvote count from an AJAX call.
 * Returns the updated count number.
 * 
 * @param $docs_id -- ID of the docs object
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_update_docs_upvotes' ) )){
	function tommusrhodus_update_docs_upvotes(){

		if(!( isset( $_POST['docs_id'] ) )){
			wp_die();
		}
		
		$docs_id = $_POST['docs_id'];
		$count   = get_post_meta( $docs_id, 'tommusrhodus_docs_upvotes', 1 ) + 1;
		
		update_post_meta( $docs_id, 'tommusrhodus_docs_upvotes', $count );
		
		wp_die( $count );
		
	}
	add_action( 'wp_ajax_tommusrhodus_update_docs_upvotes', 'tommusrhodus_update_docs_upvotes' );
	add_action( 'wp_ajax_nopriv_tommusrhodus_update_docs_upvotes', 'tommusrhodus_update_docs_upvotes' );
}

/**
 * tommusrhodus_update_docs_downvotes()
 * 
 * Update the post upvote count from an AJAX call.
 * Returns the updated count number.
 * 
 * @param $docs_id -- ID of the docs object
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_update_docs_downvotes' ) )){
	function tommusrhodus_update_docs_downvotes(){

		if(!( isset( $_POST['docs_id'] ) )){
			wp_die();
		}
		
		$docs_id = $_POST['docs_id'];
		$count   = get_post_meta( $docs_id, 'tommusrhodus_docs_downvotes', 1 ) + 1;
		
		update_post_meta( $docs_id, 'tommusrhodus_docs_downvotes', $count );
		
		wp_die( $count );
		
	}
	add_action( 'wp_ajax_tommusrhodus_update_docs_downvotes', 'tommusrhodus_update_docs_downvotes' );
	add_action( 'wp_ajax_nopriv_tommusrhodus_update_docs_downvotes', 'tommusrhodus_update_docs_downvotes' );
}

/**
 * tommusrhodus_time_to_read()
 * 
 * When a post is saved, calculate and save the reading time of the content.
 * Saved to post meta so that this isn't calculated every time a post is viewed.
 * 
 * @param $post_id -- ID of the post object to calculate time of
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_time_to_read' ) )){
	function tommusrhodus_time_to_read( $post_id ){
	
	    $content      = get_post_field( 'post_content', $post_id );
	    $word_count   = str_word_count( strip_tags( $content ) );
	    $reading_time = ceil( $word_count / 200 );
	    
		update_post_meta( $post_id, '_tommusrhodus_reading_time', $reading_time );
		
	}
	add_action( 'save_post', 'tommusrhodus_time_to_read', 10, 1 );
}

/**
 * tommusrhodus_get_reading_time()
 * 
 * Display the post reading time for the post given by ID.
 * If none is saved in the post meta, calculate and save the time to post meta via tommusrhodus_time_to_read()
 * 
 * @param $post_id -- ID of the post object to calculate time of
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_reading_time' ) )){
	function tommusrhodus_get_reading_time( $post_id ){
		
		$min_read = esc_html__( ' min read', 'uptime' );
		
		if( $reading_time = get_post_meta( $post_id, '_tommusrhodus_reading_time', 1 ) ){
			return $reading_time . $min_read;
		}
		
		tommusrhodus_time_to_read( $post_id );
		
		return get_post_meta( $post_id, '_tommusrhodus_reading_time', 1 ) . $min_read;
		
	}
}

/**
 * tommusrhodus_register_required_plugins()
 * 
 * Register the required and recommended plugins for the theme.
 * Uses TGMPA for plugin installation and activation.
 * 
 * @documentation http://tgmpluginactivation.com
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_register_required_plugins' ) )){
	function tommusrhodus_register_required_plugins() {
	
		$plugins = array(
			array(
			    'name'      => esc_html__( 'Contact Form 7', 'uptime' ),
			    'slug'      => 'contact-form-7',
			    'required'  => false,
			    'version' 	=> '3.7.2'
			),
			array(
			    'name'      => esc_html__( 'Custom Metaboxes 2', 'uptime' ),
			    'slug'      => 'cmb2',
			    'required'  => true,
			    'version' 	=> '1.0.0'
			),
			array(
			    'name'      => esc_html__( 'WP Job Manager', 'uptime' ),
			    'slug'      => 'wp-job-manager',
			    'required'  => false,
			    'version' 	=> '1.0.0'
			),
			array(
			    'name'      => esc_html__( 'Elementor', 'uptime' ),
			    'slug'      => 'elementor',
			    'required'  => false,
			    'version' 	=> '1.0.0'
			),
			array(
				'name'     				=> esc_html__( 'TommusRhodus Framework', 'uptime' ),
				'slug'     				=> 'TommusRhodus-Framework-master',
				'source'   				=> 'https://github.com/tommusrhodus/tommusrhodus-framework/archive/master.zip',
				'required' 				=> true,
				'version' 				=> '1.0.2',
				'external_url' 			=> 'https://github.com/tommusrhodus/tommusrhodus-framework/archive/master.zip',
			)
		);
		
		tgmpa( $plugins );
		
	}
	add_action( 'tgmpa_register', 'tommusrhodus_register_required_plugins' );
}

/**
 * get_tommusrhodus_breadcrumbs()
 * 
 * Builds the breadcrumb markup for the theme.
 * Uses the current post to create a tiered breadcrumb.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'get_tommusrhodus_breadcrumbs' ) )){ 
	function get_tommusrhodus_breadcrumbs( $class = 'breadcrumb' ){
	
		if ( is_front_page() || is_search() ){
			return;
		}
		
		global $post;
		
		$post_type = get_post_type();
		$ancestors = false;
		
		if( isset( $post->ID ) ){
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
		}
		
		$before    = '<nav aria-label="breadcrumb"><ol class="'. $class .'">';
		$after     = '</ol></nav>';
		$home      = '<li class="breadcrumb-item"><a href="' . esc_url( home_url( "/" ) ) . '" class="home-link" rel="home">' . esc_html__( 'Home', 'uptime' ) . '</a></li>';
		
		if( 'portfolio' == $post_type ){
			$slug = get_option( 'portfolio_post_type_slug', 'portfolio' );
			$title = str_replace(array('-', '_'), ' ', $slug);
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( "/". $slug ."/" ) ) . '">' . ucfirst($title) . '</a></li>';
		}
		
		if( 'team' == $post_type ){
			$slug = get_option( 'team_post_type_slug', 'team' );
			$title = str_replace(array('-', '_'), ' ', $slug);
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( "/". $slug ."/" ) ) . '">' . ucfirst($title) . '</a></li>';
		}

		if( 'testimonial' == $post_type ){
			$slug = get_option( 'testimonial_post_type_slug', 'testimonials' );
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( "/". $slug ."/" ) ) . '">' . esc_html__( 'Testimonials', 'uptime' ) . '</a></li>';
		}
		
		if( class_exists('woocommerce') ){
			if( 'product' == $post_type && !(is_shop()) ){
				$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'uptime' ) . '</a></li>';
			} elseif( 'product' == $post_type && is_archive() ) {
				$home .= '<li class="breadcrumb-item active">' . esc_html__( 'Shop', 'uptime' ) . '</li>';
			}
		}
		
		$breadcrumb = '';
		if ( $ancestors ) {
			foreach ( $ancestors as $ancestor ) {
				$breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url( get_permalink( $ancestor ) ) . '">'. get_the_title( $ancestor ) .'</a></li>';
			}
		}
		
		if( tommusrhodus_is_blog_page() && is_single() ){
			$breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">'. get_theme_mod('post_archive_title', 'Blog') .'</a></li>';
		} elseif( tommusrhodus_is_blog_page() ){
			$breadcrumb .= '<li class="breadcrumb-item active">'. get_theme_mod('post_archive_title','Blog') .'</li>';
		} elseif( is_post_type_archive('product') || is_archive() ){		
			$breadcrumb .= '<li class="breadcrumb-item">'. single_cat_title('', false) .'</li>';		
		}
		
		return $before . $home . $breadcrumb . $after;
		
	}
}

/**
 * Add additional styling options to TinyMCE
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('tommusrhodus_mce_buttons_2') )){
	function tommusrhodus_mce_buttons_2( $buttons ) {
	    array_unshift( $buttons, 'styleselect' );
	    return $buttons;
	}
	add_filter( 'mce_buttons_2', 'tommusrhodus_mce_buttons_2' );
}

/**
 * Add additional styling options to TinyMCE
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('tommusrhodus_mce_before_init') )){
	function tommusrhodus_mce_before_init( $settings ) {
	    $style_formats = array(
	    	array(
				'title'	=> esc_html__( 'Button Styles', 'uptime' ),
				'items'	=> array(
			    	array(
			    		'title' => esc_html__( 'Button', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn',
			    	),
			    	array(
			    		'title' => esc_html__( 'Button Filled', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn btn-primary',
			    	),
			    	array(
			    		'title' => esc_html__( 'Button Filled Block', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn btn-block',
			    	),
			    	array(
			    		'title' => esc_html__( 'Button Filled Large', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn btn-lg',
			    	),
			    	array(
			    		'title' => esc_html__( 'Button Outline Large', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn btn-lg btn-outline-primary',
			    	),
			    	array(
			    		'title' => esc_html__( 'Button Large Filled Block', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'btn btn-lg btn-block',
			    	)
				)
	    	),
	    	array(
				'title'	=> esc_html__( 'Link Styles', 'uptime' ),
				'items'	=> array(
			    	array(
			    		'title' => esc_html__( 'Arrow', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'hover-arrow',
			    	),
			    	array(
			    		'title' => esc_html__( 'Arrow Lead', 'uptime' ),
			    		'selector' => 'a',
			    		'classes' => 'lead hover-arrow',
			    	)
				)
	    	),
	    	array(
	    		'title'	=> esc_html__( 'Margins', 'uptime' ),
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => esc_html__( 'Zero Bottom Margin', 'uptime' ),
	    	    		'selector' => '*',
	    	    		'classes' => 'mb0',
	    	    	)
	    		)
	    	),
	    	array(
	    		'title'	=> esc_html__( 'List Styles', 'uptime' ),
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => esc_html__( 'Bulleted List', 'uptime' ),
	    	    		'selector' => 'ul',
	    	    		'classes' => 'bullets',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Numbered List', 'uptime' ),
	    	    		'selector' => 'ol',
	    	    		'classes' => 'numbered',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Vertical Menu', 'uptime' ),
	    	    		'selector' => 'ul',
	    	    		'classes' => 'menu-vertical',
	    	    	)
	    		)
	    	),
	    	array(
	    		'title'	=> esc_html__( 'Text Styles', 'uptime' ),
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => esc_html__( 'Uppercase Text', 'uptime' ),
	    	    		'selector' => '*',
	    	    		'classes' => 'type--uppercase',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Faded Text', 'uptime' ),
	    	    		'selector' => '*',
	    	    		'classes' => 'type--fade',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Lead Paragraph', 'uptime' ),
	    	    		'selector' => 'p',
	    	    		'classes' => 'lead',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Fine Print', 'uptime' ),
	    	    		'selector' => '*',
	    	    		'classes' => 'type--fine-print',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Left Pull Quote', 'uptime' ),
	    	    		'selector' => 'p',
	    	    		'classes' => 'pull-quote left',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Right Pull Quote', 'uptime' ),
	    	    		'selector' => 'p',
	    	    		'classes' => 'pull-quote right',
	    	    	),
	    	    	array(
	    	    		'title' => esc_html__( 'Highlight', 'uptime' ),
	    	    		'selector' => '*',
	    	    		'inline' => 'span',
	    	    		'classes' => 'highlight',
	    	    	),
	    		)
	    	),
	    	array(
	    		'title'	=> esc_html__( 'Colors', 'uptime' ),
	    		'items'	=> array(
	    			array(
	    				'title' => esc_html__( 'Primary', 'uptime' ),
	    				'selector' => '*',
	    				'classes' => 'color--primary',
	    			),
	    			array(
	    				'title' => esc_html__( 'Primary 1', 'uptime' ),
	    				'selector' => '*',
	    				'classes' => 'color--primary-1',
	    			),
	    			array(
	    				'title' => esc_html__( 'Primary 2', 'uptime' ),
	    				'selector' => '*',
	    				'classes' => 'color--primary-2',
	    			),
	    			array(
	    				'title' => esc_html__( 'Dark', 'uptime' ),
	    				'selector' => '*',
	    				'classes' => 'color--dark',
	    			),
	    			array(
	    				'title' => esc_html__( 'Secondary', 'uptime' ),
	    				'selector' => '*',
	    				'classes' => 'color--secondary',
	    			),
	    		)
	    	)
	    );
	    $settings['style_formats'] = json_encode( $style_formats );
	    return $settings;
	}
	add_filter( 'tiny_mce_before_init', 'tommusrhodus_mce_before_init' );
}

/**
 * Grab and list all categories for standard posts.
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists( 'tommusrhodus_get_post_categories' ) )) {
	function tommusrhodus_get_post_categories() {
	    $categories = get_categories();
	    $cat_array = [];
	    foreach ($categories as $category) :
	        $cat_array[$category->slug] = $category->name;
	    endforeach;
	    return $cat_array;
	}
}

if(!( function_exists( 'tommusrhodus_exclude_category' ) )) {
	function tommusrhodus_exclude_category( $query ) {
		$layout 						= get_theme_mod( 'blog_layout', '1' );
		$featured_post_category		    = get_category_by_slug( get_theme_mod( 'post_archive_featured_posts_category' ) );
		if( isset( $featured_post_category->term_id ) ) {
			$featured_post_category         = '-'. $featured_post_category->term_id;
			if ( $query->is_home() && $query->is_main_query() ) {
				if ( '2' == $layout || '3' == $layout  ) {
					$query->set( 'cat', $featured_post_category );
				}			
			}
		}
	}
	add_action( 'pre_get_posts', 'tommusrhodus_exclude_category' );
}

/**
 * tommusrhodus_pagination()
 * 
 * Simple numbered pagination system, creates a custom, functional pagination.
 * 
 * @param $pages -- Number of pages to give to the function
 * @param $range -- Range of pages to show at this time
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pagination' ) )){
	function tommusrhodus_pagination( $pages = '', $range = 2 ){
		$showitems = ($range * 2)+1;
		
		global $paged;

		if(empty($paged)) $paged = 1;
		
		if($pages == ''){
			
			global $wp_query;
			$pages = $wp_query->max_num_pages;
				if(!$pages) {
					$pages = 1;
				}
		}
		
		$output = '';
			
		if(1 != $pages){
	  		$output .= "<div class=\"row justify-content-between align-items-center pagination-wrapper\"><div class=\"col-md-auto\">";

	  		if( $paged > 1 ){
	  			$output .= "<a href='". get_pagenum_link(1) ."' class='btn btn-outline-white'>". esc_html__( 'Previous' , 'uptime' ) ."</a>";
	  		}

	  		$output .= "</div><div class=\"col-md-auto\"><nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";  		
			
			for ($i=1; $i <= $pages; $i++){
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					$output .= ($paged == $i)? "<li class='page-item active'><a href='#' class='page-link'>".$i."</a></li> ":"<li class='page-item'><a href='".get_pagenum_link($i)."' class='page-link'>".$i."</a></li> ";
				}
			}				
			
			$output .= "</ul></nav></div><div class=\"col-md-auto\">";

			if( $paged < $pages ){
				$output .= "<a href='".get_pagenum_link($pages)."' class='btn btn-outline-white'>". esc_html__( 'Next' , 'uptime' ) ."</a>";
			}

			$output .= "</div></div>";	
		}
		
		return $output;
	}
}

if(!( function_exists('ebor_get_social_icons') )){
	function ebor_get_social_icons(){
		return array('socicon-behance','socicon-dribbble','socicon-facebook','socicon-flickr','socicon-github','socicon-grooveshark','socicon-houzz','socicon-icloud','socicon-instagram','socicon-lastfm','socicon-linkedin','socicon-medium','socicon-picasa','socicon-pinterest','socicon-rdio','socicon-skype','socicon-soundcloud','socicon-spotify','socicon-stumbleupon','socicon-tripadvisor','socicon-tumblr','socicon-twitter','socicon-vimeo','socicon-vine','socicon-vk','socicon-vk-alternitive','socicon-windows-store','socicon-xing','socicon-yelp','socicon-youko','socicon-youtube','socicon-google-plus');
	}
}

/**
 * tommusrhodus_get_header_layout
 * 
 * Use to conditionally check the page header meta layout against the theme option for the same
 * In short, this function can override the global header option on a post by post basis
 * Call within get_header() for this to override the global header choice
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('tommusrhodus_get_post_layout') )){
	function tommusrhodus_get_post_layout($post_id = false){
			
		if( false == $post_id ){
			
			global $post;
			
			if( isset($post->ID) ){
				$post_id = $post->ID;
			}
			
		}
		
		$post_layout = get_post_meta($post_id, '_tommusrhodus_post_layout_override', 1);
		if( '' == $post_layout || false == $post_layout || 'none' == $post_layout ){
			$post_layout = get_theme_mod( 'post_single_layout', '2' );
		}
		
		return $post_layout;	
	}
}

/**
 * tommusrhodus_custom_comment()
 * 
 * The custom comment markup for the theme, includes additional functionality.
 * 
 * @param $comment -- the current comment object (Array)
 * @param $args -- the arguments of the current object (Array)
 * @param $depth -- the current depth of the comment (Integer)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_custom_comment' ) )){
	function tommusrhodus_custom_comment( $comment, $args, $depth ) { 
		$GLOBALS['comment'] = $comment; 
	?>
		
		<li id="comment-<?php comment_ID() ?>" <?php comment_class( 'comment' ); ?>>
			
			<div class="d-flex align-items-center text-small">
				
				<?php 
					if( 'comment' == get_comment_type() ){
						echo get_avatar( $comment->comment_author_email, 52, false, false, array( 'class' => 'avatar avatar-sm mr-2' ) );
					}
				?>
				
				<div class="text-dark mr-1"><?php echo get_comment_author(); ?></div>
				<div class="text-muted"><?php echo get_comment_date(); ?></div>
				
			</div>
			
			<div class="my-2 comment-content"><?php echo wpautop( get_comment_text() ); ?></div>
			
			<div class="text-small">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'after' => tommusrhodus_svg_icons_pluck( 'Reply', 'icon bg-primary' ) ) ) ); ?>
			</div>
		<!-- /.media - closing li tag omitted on purpose -->

	<?php }
}

if( !class_exists( 'TommusRhodus_Custom_Menu_Fields' ) ){
	/* Add Custom Field to Menu Items */
	class TommusRhodus_Custom_Menu_Fields {
	
		/**
		 * Holds our custom fields
		 *
		 * @var    array
		 * @access protected
		 * @since  Menu_Item_Custom_Fields_Example 0.2.0
		 */
		protected static $fields = array();
		
		/**
		 * Initialize plugin
		 */
		public static function init() {
		
			add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
			add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
			add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );
			
			self::$fields = array(
				'custom-icon' 		=> esc_html__( 'Select an Icon', 'uptime' ),
				'custom-image' 		=> esc_html__( 'Custom Image', 'uptime' ),
			);
			
		}
		
		/**
		 * Save custom field value
		 *
		 * @wp_hook action wp_update_nav_menu_item
		 *
		 * @param int   $menu_id         Nav menu ID
		 * @param int   $menu_item_db_id Menu item ID
		 * @param array $menu_item_args  Menu item data
		 */
		public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			}
			
			check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );
			
			foreach ( self::$fields as $_key => $label ) {
				$key = sprintf( 'menu-item-%s', $_key );
				// Sanitize
				if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
					// Do some checks here...
					$value = $_POST[ $key ][ $menu_item_db_id ];
				} else {
					$value = null;
				}
				// Update
				if ( ! is_null( $value ) ) {
					update_post_meta( $menu_item_db_id, $key, $value );
				} else {
					delete_post_meta( $menu_item_db_id, $key );
				}
			}
			
		}
		
		/**
		 * Print field
		 *
		 * @param object $item  Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args  Menu item args.
		 * @param int    $id    Nav menu ID.
		 *
		 * @return string Form fields
		 */
		public static function _fields( $id, $item, $depth, $args ) {
			foreach ( self::$fields as $_key => $label ) :
				$key   = sprintf( 'menu-item-%s', $_key );
				$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
				$name  = sprintf( '%s[%s]', $key, $item->ID );
				$value = get_post_meta( $item->ID, $key, true );
				$class = sprintf( 'field-%s', $_key );
	
				if( $key == 'menu-item-custom-icon' ) {
	
					if( function_exists('tommusrhodus_get_svg_icons') ){
						$icons = tommusrhodus_get_svg_icons();
					}
	
					?>

						<p class="description description-wide <?php echo esc_attr( $class ) ?>">
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label><br />
							<select name="<?php echo esc_attr( $name ); ?>" id="<?php esc_attr( $id ); ?>" class="widefat">
								<?php foreach ( $icons as $icon_name => $icon ) { ?>
									<option value="<?php echo esc_attr( $icon_name  ); ?>" <?php selected( $value, $icon_name ); ?>><?php echo esc_html( $icon_name  ); ?></option>	
								<?php } ?>
							</select>
						</p>

					<?php 

				} else {
	
					echo '
						<p class="description description-wide '. esc_attr( $class ) .'">
							<label for="'. esc_attr( $id ) .'">'. esc_html( $label ) .'<input type="text" id="'. esc_attr( $id ) .'" class="widefat '. esc_attr( $id ) .'" name="'. esc_attr( $name ) .'" value="'. esc_attr( $value ) .'" /></label>
						</p>
					';
	
				} 
	
			endforeach;
		}
		
		/**
		 * Add our fields to the screen options toggle
		 *
		 * @param array $columns Menu item columns
		 * @return array
		 */
		public static function _columns( $columns ) {
			$columns = array_merge( $columns, self::$fields );
			return $columns;
		}
		
	}
	
	if( is_admin() ){
		global $pagenow;
		
		if( 'nav-menus.php' == $pagenow ){
			TommusRhodus_Custom_Menu_Fields::init();
		}
	}
	
}

if(!( function_exists( 'tommusrhodus_hex2rgb' ) )){
	function tommusrhodus_hex2rgb($hex) {
	
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   
	   return implode(",", $rgb); // returns the rgb values separated by commas
	   
	}
}

if(!( function_exists( 'tommusrhodus_get_excerpt' ) )){
	function tommusrhodus_get_excerpt( $word_count_limit ) {

	    $content = wp_strip_all_tags( strip_shortcodes( get_the_content() ), true );
	    echo wp_trim_words( $content, $word_count_limit );

	}
}