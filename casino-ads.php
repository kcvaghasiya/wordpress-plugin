<?php
/**
 * Plugin Name: Casino Ads
 * Plugin URI: #
 * Description: Casino Ads is an easy and useful WordPress Plugin for create shortcode of custom post type "Casino Ads".
 * Version: 1.0.1
 * Text Domain: casino-ads
 * Author: #
 * Author URI: #
 *
 * @package CasinoAds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'CA_PLUGIN_DIR' ) ) {
	define( 'CA_PLUGIN_DIR', __FILE__ );
}

if ( ! defined( 'CA_PLUGIN_URL' ) ) {
	define( 'CA_PLUGIN_URL', untrailingslashit( plugins_url( '/', CA_PLUGIN_DIR ) ) );
}

if ( ! defined( 'CA_VERSION' ) ) {
	define( 'CA_VERSION', '1.0.1' );
}

class CasinoAds{
	/**
	 * Bind all methods
	 *
	 * @since 1.0
	 */
	public function __construct(){
		// register_activation_hook( __FILE__, [ $this,'cs_activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'cs_deactivate' ] );
		add_action( 'init', [ $this, 'cs_create_custom_post_type'] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_shortcode('casinoAds', [ $this, 'casinoAds_frontend_shorcode']);
		add_action('wp_enqueue_scripts', [ $this, 'frontend_scripts_styles' ]);
	}

	/**
	 * Register activation hook for this plugin by invoking activate.
	 *
	 * @since 1.0
	 */
	public function cs_create_custom_post_type() {
		$casinoAds = array(
	        'name'          => 'Casino Ads',
	        'singular_name' => 'Casino Ad',
	    );
		$args = array(
	    	'labels'              => $casinoAds,
	        'description'         => 'Post type post Casino Ads',
	        'supports'            => array('title', 'author', 'thumbnail', 'revisions' ),
	        'taxonomies'          => array( '' ),
	        'hierarchical'        => false,
	        'public'              => true,
	        'show_ui'             => true,
	        'show_in_menu'        => true,
	        'show_in_nav_menus'   => true,
	        'show_in_admin_bar'   => true,
	        'menu_position'       => 5,
	        'menu_icon'           => 'dashicons-admin-home',
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => false,
	        'publicly_queryable'  => true,  
	        'map_meta_cap' 		  => true,
	        'rewrite' => array( 'slug' => 'casino-ads' ),
	    );
	    register_post_type('casino-ads', $args);
	}

	/**
	 * Add metabox to display shortcode in admin
	 *
	 * @since 1.0
	 */
	public function add_meta_box(){
		if(isset($_GET['post'])){
			add_meta_box("casino-ads", "Casino Ads Shortcode", [$this,"meta_markup_casino_ads"], "casino-ads", "side");
		}
	}

	/**
	 * Callback function to display shortcode to metabox in admin
	 *
	 * @since 1.0
	 */
	public function meta_markup_casino_ads(){
		if(isset($_GET['post'])){
			echo '[casinoAds post='.$_GET["post"].']';
		}	
	}

	/**
	 * Create shortcode to diplay post in frontend
	 *
	 * @since 1.0
	 */
	public function casinoAds_frontend_shorcode($atts, $content = null){
		extract(shortcode_atts(array(
	        'post' => '',
	    ) , $atts));

		
	    ob_start();
	    if($post){
	    	$args = array(
	            'post_type' => 'casino-ads',
	            'p' => $post,
	            'posts_per_page' => 1,
	            'post_status' => 'publish',
	        );
	        $content_posts = get_posts($args);
	        if($content_posts){
	        	foreach ($content_posts as $content_post) {
		    		?>
		    		<div class="casino-ads">
					   <div class="casino-ads-item-logo box-9">
					      <div class="casino-ads-item-logo-item-logo-ins box-100 text-center">
					         <a href="" title="<?php echo $content_post->post_title; ?>"><?php echo get_the_post_thumbnail($content_post->ID, 'full', ''); ?>
					         </a>
					      </div>
					   </div>
					   <div class="casino-ads-item-title box-66">
					      <div class="casino-ads-item-title-ins box-100 text-left">
					        	<h3 title="<?php echo $content_post->post_title; ?>" class="item-title"><?php echo $content_post->post_title; ?></h3>
					        <a class="simple_link" href="<?php echo get_field('simple_link', $content_post->ID ); ?>" ><span><?php echo get_field('simple_link', $content_post->ID); ?></span></a>									
					      </div>
					   </div>
					   <div class="casino-ads-item-button box-25">
					      <div class="casino-ads-item-button-ins box-100 text-center">
					        	<a href="<?php echo get_field('ads_link', $content_post->ID); ?>" class="ads_link" title="<?php  echo __( 'Jouer', 'casino-ads' )?>"><?php  echo __( 'Jouer', 'casino-ads' )?></a>
					      </div>
					   </div>
					</div>
		    		<?php
		    	}
	        }
		}
	    $output_string = ob_get_contents();
	    ob_end_clean();
	    return $output_string;
	}


	/**
	* enqueue frontend display_staff.js
	*/
	public function frontend_scripts_styles(){
	    wp_enqueue_style( 'casino-ads', CA_PLUGIN_URL. '/casino-ads.css', CA_VERSION, true );
	    
	}
	/**
	 * Register deactivation hook for this plugin by invoking activate.
	 *
	 * @since 1.0
	 */
	public function cs_deactivate() {
		flush_rewrite_rules();
	}

		
}
new CasinoAds();