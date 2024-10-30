<?php
/*
Plugin Name: LH Buddypress Group Redirect
Plugin URI: https://lhero.org/portfolio/lh-buddypress-group-redirect/
Description: If user is a member of only one group redirect them from their groups page to that group
Author: Peter Shaw
Version: 1.02
Author URI: https://shawfactor.com/
*/


if (!class_exists('LH_Buddypress_group_redirect_plugin')) {

class  LH_Buddypress_group_redirect_plugin {
	    
private static $instance;

static function plugin_name(){
    
    return 'LH Buddypress Site Only Groups';
    
    }
	    
	    
static function isValidURL($url){ 

return (bool)parse_url($url);
}

	    
public function handle_group_redirect() { 

global $wp_query; 

if ( is_singular() and is_user_logged_in() and !current_user_can('bp_moderate')) { 

$post = $wp_query->get_queried_object(); 

$page_array = get_option('bp-pages');

if (!empty($post->ID) && ( $post->ID == $page_array['groups'] ) ) { 
    
$current_user = wp_get_current_user();

$groups = groups_get_user_groups($current_user->ID);

if ($groups['total'] == '1'){


$args = array( 
  'group_id' => $groups['groups'][0]
);

$group = groups_get_group( $args );

$url = bp_get_group_permalink($group);


if (self::isValidURL($url)){

wp_redirect($url,302, self::plugin_name());  exit; 

}


}


} 



}


}

public function plugin_init(){

//handle the group redirect    
add_action('template_redirect', array($this,'handle_group_redirect'));     
    
    
    
}


    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }

	    
	
	public	function __construct() {
	    
	    
	    	 //run our hooks on bp_include loaded to as we may need checks       
            add_action( 'bp_include', array($this,'plugin_init'));
		    
		}
		
		
		
	}
	
	
$lh_buddypress_group_redirect_instance = LH_Buddypress_group_redirect_plugin::get_instance();
}

?>