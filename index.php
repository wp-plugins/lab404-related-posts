<?php
/*
Plugin Name: Lab404 Related Posts
Plugin URI: http://lab404.net
Description: Show related posts in nice format with image. Plugin is fully configurable and easy to use.
Version: 1.0
Author: Ivan M
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register an activation hook
register_activation_hook( __FILE__, array( 'lab404_related_posts', 'on_activation' ) );

// Run the init function when it's time to load the plugin
add_action( 'wp_loaded', array( 'lab404_related_posts', 'get_instance' ) );

// Related posts class
Class lab404_related_posts{
    
    // Plugin version
    public static $version = '1.0';
    
    // plugin constructor
    private function __construct() {
        
        // add scripts and styles to website
        add_action( 'wp_enqueue_scripts', array( $this, 'lab404_include_styles_and_scripts' ) );
        // Add admin menu page, scripts and stylesheets
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        // create shortcode function
        add_shortcode( 'lab404-related-posts', array($this, 'lab404_related_posts_shortcode') );
        // content filter - for related posts
        
        // TODO: is active check can be placed here
        add_filter( 'the_content', array($this, 'lab404_related_posts_content_filter') );
    }
    
    public static function api() {
        static $api = null;
        // Check to see if an instance already exists
        if (null === $api) {
            // Include our API class
            require_once( plugin_dir_path(__FILE__) . 'includes/class_related_posts_api.php' );
            // Create a new instance
            $api = RelatedPostsAPI::get_instance();
        }
        // Return the instance
        return $api;
    }

    // get instance
    public static function get_instance() {
        static $instance = null;

        // Check to see if an instance already exists
        if (null === $instance) {
            // Create a new instance
            $instance = new static();
        }

        // Return the instance
        return $instance;
    }

    // Check if we're coming from an older version
    public static function on_activation() {
        // Update our option to the current version
        update_option( 'lab404_related_posts_version', self::$version );
        if(!get_option('lab404_related_posts_options')){
            // set default options on plugin activation
            update_option('lab404_related_posts_options', 'a:12:{s:8:"_wpnonce";s:10:"84eb10637a";s:16:"_wp_http_referer";s:45:"/wp-admin/admin.php?page=lab404_related_posts";s:20:"enable_related_posts";s:1:"1";s:22:"related_posts_template";s:5:"boxes";s:12:"tags_related";s:1:"1";s:16:"category_related";s:1:"1";s:28:"related_article_widget_title";s:16:"Related Articles";s:22:"related_article_number";s:1:"6";s:20:"related_article_cols";s:1:"4";s:8:"order_by";s:4:"date";s:9:"order_way";s:4:"DESC";s:9:"save_menu";s:12:"Save Options";}');
        }
    }

    // Add the options page
    public function admin_menu() {
        add_menu_page(
            __('WordPress Related Posts', 'lab404-related-posts'), __('WordPress Related Posts', 'lab404-related-posts'), 'manage_options', 'lab404_related_posts', array($this, 'options_page_lab404_related_posts')
        );
        /*
        add_submenu_page( 
            'lab404_related_posts',__('Instructions', 'lab404-related-posts'), __('Instructions', 'lab404-related-posts'), 'manage_options', 'lab404_related_posts_instructions', array($this, 'options_page_lab404_related_posts_instructions') 
        ); 
        add_submenu_page( 
            'lab404_related_posts',__('Pro Version', 'lab404-related-posts'), __('PRO Version', 'lab404-related-posts'), 'manage_options', 'lab404_related_posts_pro_version', array($this, 'options_page_lab404_related_posts_pro_version') 
        ); 
        */
        // This is so we can register our scripts and stylesheets
        add_action('admin_init', array($this, 'admin_init'));

    }

    // Plugin initialization
    public function admin_init() {
        // Version so that the browser doesn't cache when we release new versions
        $version = self::$version;

        // Register options page stylesheet and script
        wp_register_style('lab404-related-posts', plugins_url('css/style.css', __FILE__), array(), $version);
        wp_register_script('lab404-related-posts', plugins_url('js/script.js', __FILE__), array('jquery'), $version, true);
        wp_enqueue_style( 'lab404-related-posts' );
        wp_enqueue_script( 'lab404-related-posts' );

    }
    // load styles and script to each page
    public function lab404_include_styles_and_scripts(){
        wp_register_style( 'lab404-related-posts-widget-style', plugins_url('css/widgets.css', __FILE__) );
        wp_enqueue_style( 'lab404-related-posts-widget-style' );
    }
    

    // options page
    public function options_page_lab404_related_posts() {
        
        // update options
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        if($action === 'save_options' && $_POST){
            update_option('lab404_related_posts_options', serialize($_POST));
            wp_redirect( 'admin.php?page=lab404_related_posts', 301 ); exit; 
        }
        
        // get options
        $all_options = $this->api()->GetAllOptions();
        $enable_related_posts = $this->api()->GetSingleOption($all_options,'enable_related_posts');
        if(!$enable_related_posts){
            $enable_related_posts = 1;
        }
        
        $related_posts_template = $this->api()->GetSingleOption($all_options,'related_posts_template');
        if(!$related_posts_template){
            $related_posts_template = 'boxes';
        }
        
        $tags_related = $this->api()->GetSingleOption($all_options,'tags_related');
        if(!$tags_related){
            $tags_related = 1;
        }
        
        $category_related = $this->api()->GetSingleOption($all_options,'category_related');
        if(!$category_related){
            $category_related = 1;
        }
        
        $related_article_widget_title = $this->api()->GetSingleOption($all_options,'related_article_widget_title');
        if(!$related_article_widget_title){
            $related_article_widget_title = "Related Articles";
        }
        
        $related_article_number = $this->api()->GetSingleOption($all_options,'related_article_number');
        if(!$related_article_number){
            $related_article_number = 6;
        }
        
        $related_article_cols = $this->api()->GetSingleOption($all_options,'related_article_cols');
        if(!$related_article_cols){
            $related_article_cols = 3;
        }
        
        $order_by = $this->api()->GetSingleOption($all_options,'order_by');
        if(!$order_by){
            $order_by = "ID";
        }
        
        $order_way = $this->api()->GetSingleOption($all_options,'order_way');
        if(!$order_way){
            $order_way = "DESC";
        }

        // Show the form templates
        include( plugin_dir_path( __FILE__ ) . 'templates/admin_home_tpl.php' );
    }
    
    
    // usage instructions
    public function options_page_lab404_related_posts_instructions(){

        include( plugin_dir_path( __FILE__ ) . 'templates/instructions_tpl.php' );
    }
    
    
    // pro version description
    public function options_page_lab404_related_posts_pro_version(){
        
        include( plugin_dir_path( __FILE__ ) . 'templates/pro_version_tpl.php' );
    }
    
    
    /***************************************************************************
     * FRONT END PART OF PLUGIN
     **************************************************************************/
    
    
    // related posts shortcode
    public function lab404_related_posts_shortcode($atts){
        
        if ( is_singular('post')){
            
            // some additional checks must be implemented
            $cat_posts = $this->api()->RelatedContentShortcode($atts);

            
            $related_posts_template = ($atts['template']) ? $atts['template'] : 'boxes';
            $defined_cols = ($atts['cols']) ? (int)$atts['cols'] : 3;
            
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/'.$related_posts_template.'_tpl.php' );
            
            return ob_get_clean();
        }
        
    }
    
    // filter for the_content, add related articles below post
    public function lab404_related_posts_content_filter($content){
        if ( is_singular('post')){
            // some additional checks must be implemented
            $cat_posts = $this->api()->PostsBelowContent();
            // get template option
            $all_options = $this->api()->GetAllOptions();
            $related_posts_template = $this->api()->GetSingleOption($all_options,'related_posts_template');
            $defined_cols = $this->api()->GetSingleOption($all_options,'related_article_cols');
            $related_posts_title = ($this->api()->GetSingleOption($all_options,'related_article_widget_title')) ? $this->api()->GetSingleOption($all_options,'related_article_widget_title') : 'Related posts'; 
            if(!$related_posts_template){
                $related_posts_template = 'boxes';
            }   
            if(!$defined_cols){
                $defined_cols = 2;
            }
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'templates/'.$related_posts_template.'_tpl.php' );
            
            $content .= ob_get_clean();
        }
        
        return $content;
    }
    

}