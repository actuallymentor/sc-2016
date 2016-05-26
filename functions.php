<?php
add_action( 'after_setup_theme', 'materialize_setup' );
function materialize_setup ( ) {
	load_theme_textdomain( 'materialize', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640;
	register_nav_menus(
		array( 'main-menu' => __( 'Main Menu', 'materialize' ) )
		);
}
add_action( 'comment_form_before', 'materialize_enqueue_comment_reply_script' );
function materialize_enqueue_comment_reply_script ( ) {
	if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_filter( 'the_title', 'materialize_title' );
function materialize_title( $title ) {
	if ( $title == '' ) {
		return '&rarr;';
	} else {
		return $title;
	}
}
add_filter( 'wp_title', 'materialize_filter_wp_title' );
function materialize_filter_wp_title( $title ) {
	return $title . esc_attr( get_bloginfo( 'name' ) );
}
add_action( 'widgets_init', 'materialize_widgets_init' );
function materialize_widgets_init ( ) {
	register_sidebar( array (
		'name' => __( 'Footer Widget Area 1', 'footer-widget-1' ),
		'id' => 'footer-widget-area-1',
		'before_widget' => '<div class="footwidget col l3 m6 s12">',
		'after_widget' => "</div>",
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		) );
	register_sidebar( array (
		'name' => __( 'Footer Widget Area 2', 'footer-widget-2' ),
		'id' => 'footer-widget-area-2',
		'before_widget' => '<div class="footwidget col l3 m6 s12">',
		'after_widget' => "</div>",
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		) );
	register_sidebar( array (
		'name' => __( 'Footer Widget Area 3', 'footer-widget-3' ),
		'id' => 'footer-widget-area-3',
		'before_widget' => '<div class="footwidget col l3 m6 s12">',
		'after_widget' => "</div>",
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		) );
	register_sidebar( array (
		'name' => __( 'Footer Widget Area 4', 'footer-widget-4' ),
		'id' => 'footer-widget-area-4',
		'before_widget' => '<div class="footwidget col l3 m6 s12">',
		'after_widget' => "</div>",
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		) );
	register_sidebar( array (
		'name' => __( 'Header Widget', 'header-widget' ),
		'id' => 'header-widget',
		'before_widget' => '<div class="headwidget col l12 m12 s12">',
		'after_widget' => "</div>",
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
		) );
	register_sidebar( array (
		'name' => __( 'Nav Widget', 'nav-widget' ),
		'id' => 'nav-widget',
		'before_widget' => '<div class="navwidget hide-on-med-and-down right">',
		'after_widget' => "</div>",
		'before_title' => '',
		'after_title' => '',
		) );
}
function materialize_custom_pings( $comment ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
	<?php 
}
add_filter( 'get_comments_number', 'materialize_comments_number' );
function materialize_comments_number( $count ) {
	if ( !is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}

// Add thumbnail support
add_theme_support( 'post-thumbnails' );
add_image_size ( 'thumbnail','150', '150', true );
add_image_size ( 'small','250', '250', true );
add_image_size ( 'medium','550', '400', true );
add_image_size ( 'large','900', '600', true );
add_image_size ( 'schema','696', '696', true );


// Scripts, handle, src, deps, ver, in_footer
add_action( 'wp_enqueue_scripts', 'materialize_load_scripts' );
function materialize_load_scripts ( ) {
	wp_deregister_script( 'jquery' ); // Unload default wp jquery

	// Dependencies
	wp_enqueue_script ( 'jquery', 'https://code.jquery.com/jquery-2.2.3.min.js', [], '2.2.3', true );
	wp_enqueue_style ( 'material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', [], '0.97.6', 'all' );

	// Materialize
	wp_enqueue_style ( 'materialize', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css', [], '0.97.6', 'all' );

	// Custom css and js
	wp_enqueue_style( 'custom-styles', get_template_directory_uri() . '/css/style.css', [], '2.0', 'all' );
	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', ['jquery'], '1.0.0', true );
}


// error_reporting(E_ERROR);
// Box shortcode

function boxify ( $atts, $content = null ) {

	extract(shortcode_atts(array(
		'type' => 1,
		), $atts));

	$classes = 'box card ';
	$classes .=  $atts['type'] ;

	$return_string = '<div class="' . $classes . '">' . $content .'</div>';

	return $return_string;
}

function register_shortcodes(){
	add_shortcode('box', 'boxify');
}

add_action( 'init', 'register_shortcodes');

// Schema support
include ( get_template_directory() . '/functions/schema.php' );

// jQuery fallback

add_action('wp_footer', 'jqfallback');

function jqfallback (  ) {
	?>

	<script>
		document.addEventListener("DOMContentLoaded", function(event) { 
			if  ( $ == undefined ) {
				var js = document.createElement("script")
				js.type = "text/javascript"
				js.src = "<?php echo get_template_directory_uri(); ?>/js/jquery-2.2.3.min.js"
				document.body.appendChild(js)
			}
		})
	</script>

	<?php
}