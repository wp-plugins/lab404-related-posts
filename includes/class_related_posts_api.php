<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RelatedPostsAPI {
    
    private $wordpress_root_path = '';
    
    public function __construct() {
        $this->wordpress_root_path = ABSPATH;
    }
    
    // Singleton pattern implementation
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
    
    public static function GetAllOptions(){
        $options = get_option('lab404_related_posts_options');
        $clean = unserialize($options);
        return $clean;
    }
    
    public static function GetSingleOption($all_options, $option_name){
        return $all_options[$option_name];
    }
    
    public static function RelatedContentShortcode($atts){

        
        $get_by_tags = ($atts['related_by_tag']==="1") ? true : false; 
        $get_by_categories = ($atts['related_by_category'] === "1") ? true : false; 
        $number_of_articles = ($atts['limit']) ? (int)$atts['limit'] : 6; 
        $order_by = ($atts['order_by']) ? $atts['order_by'] : 'ID'; 
        $order_by_way =  ($atts['order']) ? $atts['order'] :  'DESC';

        global $post;
            
        // generate categories ids array
        $cat_ID = array();
        $categories = get_the_category(); //get all categories for this post
        if($categories){
            foreach ($categories as $category) {
                array_push($cat_ID, $category->cat_ID);
            }
        }

        // generate tags ids array
        $tag_ID = array();
        $posttags = get_the_tags();
        if($posttags){
            foreach ($posttags as $posttag) {
                array_push($tag_ID, $posttag->term_id);
            }
        }

        // category array
        $category_array = array();
        if($get_by_categories && $cat_ID){
            $category_array = array(
                'taxonomy' => 'category',
                'field' => 'ID',
                'terms' => $cat_ID,
                'include_children' => false 
            );
        }
        // tags array
        $tags_array = array();
        if($get_by_tags && $tag_ID){
            $tags_array = array(
                'taxonomy' => 'post_tag',
                'field' => 'ID',
                'terms' => $tag_ID,
            );
        }
 
        // generate tax query array
        $tax_query = null;
        if($tags_array || $category_array){
            $tax_query = array(
                'relation' => 'OR',
                $category_array,
                $tags_array
            );
        }
        
        $args = array(
            'orderby' => $order_by,
            'order' => $order_by_way,
            'post_type' => 'post',
            'numberposts' => $number_of_articles,
            'post__not_in' => array($post->ID),
            'tax_query' => $tax_query
        );
        $cat_posts = get_posts($args);
        
        return $cat_posts;
    }
    
    
    // get posts below content
    public static function PostsBelowContent(){
        
        $all_options = self::GetAllOptions();
        
        $get_by_tags = (self::GetSingleOption($all_options,'tags_related') === "1") ? true : false; 
        $get_by_categories = (self::GetSingleOption($all_options,'category_related') === "1") ? true : false; 
        $number_of_articles = (self::GetSingleOption($all_options,'related_article_number')) ? (int)self::GetSingleOption($all_options,'related_article_number') : 6; 
        $order_by = (self::GetSingleOption($all_options,'order_by')) ? self::GetSingleOption($all_options,'order_by') : 'ID'; 
        $order_by_way =  (self::GetSingleOption($all_options,'order_way')) ? self::GetSingleOption($all_options,'order_way') :  'DESC';

        global $post;
            
        // generate categories ids array
        $cat_ID = array();
        $categories = get_the_category(); //get all categories for this post
        if($categories){
            foreach ($categories as $category) {
                array_push($cat_ID, $category->cat_ID);
            }
        }

        // generate tags ids array
        $tag_ID = array();
        $posttags = get_the_tags();
        if($posttags){
            foreach ($posttags as $posttag) {
                array_push($tag_ID, $posttag->term_id);
            }
        }

        // category array
        $category_array = array();
        if($get_by_categories && $cat_ID){
            $category_array = array(
                'taxonomy' => 'category',
                'field' => 'ID',
                'terms' => $cat_ID,
                'include_children' => false 
            );
        }
        // tags array
        $tags_array = array();
        if($get_by_tags && $tag_ID){
            $tags_array = array(
                'taxonomy' => 'post_tag',
                'field' => 'ID',
                'terms' => $tag_ID,
            );
        }
 
        // generate tax query array
        $tax_query = null;
        if($tags_array || $category_array){
            $tax_query = array(
                'relation' => 'OR',
                $category_array,
                $tags_array
            );
        }
        
        $args = array(
            'orderby' => $order_by,
            'order' => $order_by_way,
            'post_type' => 'post',
            'numberposts' => $number_of_articles,
            'post__not_in' => array($post->ID),
            'tax_query' => $tax_query
        );
        $cat_posts = get_posts($args);
        
        return $cat_posts;
        
    }
    
    // helpers function if featured image does not exist
    public static function GetFirstImageFromPost($content) {

        $first_img = '';
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
        $first_img = $matches [1] [0];
        if(!$first_img){
            $first_img = plugins_url('../templates/images/noimage.gif', __FILE__);
        }
        
        return $first_img;
    }

}



