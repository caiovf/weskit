<?php

// Definindo a constante da Url do tema
define("THEME_URL", get_template_directory_uri().'/');


// Aqua Resizer
require_once('aq_resizer.php');


// Habilitando suporte a thumbnails
add_theme_support('post-thumbnails');


// Criando o menu
function register_my_menu() {
	register_nav_menu( 'primary', __( 'Primary Menu', 'theme-slug' ) );
}
add_action( 'after_setup_theme', 'register_my_menu' );


// Criando os cortes das imagens
add_image_size( 'slide_home', 1600, 600, true );


// Incluindo Css e Js
function enqueue_scripts() {
	wp_enqueue_style( 'style', THEME_URL . 'css/style.css' );
	wp_enqueue_script( 'script', THEME_URL . 'js/script.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );


// Habilitando Css Login
function login_css() {
	wp_enqueue_style('login_css', THEME_URL . 'login.css' );
}
add_action('login_head', 'login_css');


// Habilitando ACF options
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}


// Desabilitando wp_emoji
// - Remove os styles e scripts do wp_emoji no Head e Footer de todas as páginas
function disable_wp_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'disable_wp_emoji' );


// Adicionando o slug da pagina no body_class()
function add_slug_body_class( $classes ) {
	if ( is_page() ) {
		global $post;
		$classes[] = $post->post_type . '-' . $post->post_name;

		if( $post->post_parent ) {
			$parent = get_post( $post->post_parent );
			$classes[] = $parent->post_type . '-' . $parent->post_name;
		}
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


/////////********** CODIGO DA MORTE - ALTERE E MORRA ************////////////
function add_image_insert_override($sizes) {
	global $corte;
	foreach ($sizes as $key => $size) {
		if (
			$key != $corte &&
			$key != 'thumbnail' &&
			$key != 'medium' &&
			$key != 'medium_large' &&
			$key != 'large'
			) { unset($sizes[$key]); }
	}
	return $sizes;
}
$corte = '';
if(isset($_POST['editedSize'])) {
	$corte = $_POST['editedSize'];
}
add_filter('intermediate_image_sizes_advanced', 'add_image_insert_override' );
/////////********** CODIGO DA MORTE - ALTERE E MORRA ************////////////
?>