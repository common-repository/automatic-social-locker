<?php
/*
Plugin Name: Automatic Social Lock
Plugin URI: http://www.weontech.com
Description: Automatically hide content from users until they Like, Plus or Tweet about the page.
Version: 2.0
Author: WeOnTech
Author URI: http://www.weontech.com
*/

$wp_scripts = new WP_Scripts();
if (!is_admin()) {
	wp_enqueue_script("jquery");
	wp_deregister_script('facebooksdk');
	wp_register_script('facebooksdk', 'http://connect.facebook.net/en_US/all.js#xfbml=1');
	wp_enqueue_script("facebooksdk");
	wp_deregister_script('plusone');
	wp_register_script('plusone', 'https://apis.google.com/js/plusone.js');
	wp_enqueue_script("plusone");
	wp_deregister_script('twittersdk');
	wp_register_script('twittersdk', 'https://platform.twitter.com/widgets.js');
	wp_enqueue_script("twittersdk");
}

if(!class_exists('automaticsociallock_class')) :
// DEFINE PLUGIN ID
define('AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID', 'automaticsociallock-plugin-options');
// DEFINE PLUGIN NICK
define('AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_NICK', 'Automatic Social Lock');

class automaticsociallock_class {
	
	function __construct() {
		if (is_admin()) {			
			add_action('wp_ajax_autosociallock', array(&$this, "autosociallock_callback"));
			add_action('wp_ajax_nopriv_autosociallock', array(&$this, "autosociallock_callback"));	
			add_action('admin_init', array(&$this, 'register'));
	        add_action('admin_menu', array(&$this, 'menu'));			
		} else {								
			add_action("wp_head", array(&$this, "front_header"));
			add_action("wp_footer", array(&$this, "front_footer"));							
		}
	}
	
	public static function file_path($file)
	{
		return ABSPATH.'wp-content/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).$file;
	}
	
	public static function register()
	{
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_showCloseButton');
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_title');
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_countdown');
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_countdown_duration');
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_url');		
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_post');		
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_category');
        register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_tag');
        register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_page');
		register_setting(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'_options', 'automaticsociallockpo_archive');		
	}
	/** function/method
	* Usage: hooking (registering) the plugin menu
	* Arg(0): null
	* Return: void
	*/
	public static function menu()
	{
		// Create menu tab
		add_options_page(AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_NICK.' Plugin Options', AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_NICK, 'manage_options', AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID, array('automaticsociallock_class', 'options_page'));      
	}
	
	public static function options_page()
	{
		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
	
		$plugin_id = AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID;
		// display options page
		include(self::file_path('options.php'));
	}
	/** function/method
	* Usage: filtering the content
	* Arg(1): string
	* Return: string
	*/
  function front_header() {
		echo '<link type="text/css" rel="stylesheet" href="'.site_url().'/wp-content/plugins/'.basename(dirname(__FILE__)).'/css/faceboxmodal.css">';
		echo '<script type="text/javascript" src="'.site_url().'/wp-content/plugins/'.basename(dirname(__FILE__)).'/js/faceboxmodal.js"></script>';
	}
  
	function autosociallock_callback() {
		global $wpdb;		
    $cookie_value = "0|0|0";	
    if(!empty($_COOKIE["__asl"])){
			$cookie_value = $_COOKIE["__asl"];
		}
    $cookies = explode("|", $cookie_value);
		switch ($_POST['network']) {
			case "facebook":
        $cookie_value = "1|".$cookies[1]."|".$cookies[2];
				break;
      case "plus":
        $cookie_value = $cookies[0]."|1|".$cookies[2];
        break;
      case "twitter":
        $cookie_value = $cookies[0]."|".$cookies[1]."|1";
        break;
			default:
				break;
		}
		setcookie("__asl", "1", time()+3600*24*90, "/");
	}		

	function front_footer() {
		global $wpdb;
        $showCategory = get_option('automaticsociallockpo_category');
        $showTag = get_option('automaticsociallockpo_tag');
        $showPost = get_option('automaticsociallockpo_post');
        $showPage = get_option('automaticsociallockpo_page');
		$showArchive = get_option('automaticsociallockpo_archive');
        if (empty($showCategory) && is_category()) return;
        if (empty($showTag) && is_tag()) return;
        if (empty($showPost) && is_single()) return;
        if (empty($showPage) && is_page()) return;
		if (empty($showArchive) && is_archive()) return;

		$cookie_value = "";	
		if(!empty($_COOKIE["__asl"])){
			$cookie_value = $_COOKIE["__asl"];
		}
		$popupTitle = get_option('automaticsociallockpo_title');
		$showClose = '';
		if (get_option('automaticsociallockpo_showCloseButton'))
			$showClose = 'jQuery(".popup").append(\'<a class="close" href="#"><img class="close_image" title="close" src="'.site_url().'/wp-content/plugins/'.basename(dirname(__FILE__)).'/images/closelabel.png"></a>\'); 
							jQuery("#facebox .close").click(jQuery.facebox.close);';
		if (get_option('automaticsociallockpo_url') != '') {
			$url = 'href="'.get_option('automaticsociallockpo_url').'"';
			$twitterUrl = 'data-url="'.get_option('automaticsociallockpo_url').'"';
		}
		$countDown = '';
		$countDownDuration = get_option('automaticsociallockpo_countdown_duration');				
		if (get_option('automaticsociallockpo_countdown'))
			$countDown = '				
				var countD = '.$countDownDuration.';	
				var timer = setInterval(function() {
					jQuery(".countDownDiv").html(\'Or wait <b>\' + countD + \'</b> seconds\');				
					countD--;
					if (countD == -1) {						
						clearInterval(timer);
						jQuery.facebox.close();
					}					
				},1000);								
			';
		
		if($cookie_value != "1"){
			echo '			
			<div id="fb-root"></div>
			<script type="text/javascript">
				FB.XFBML.parse();
			</script>
			<script type="text/javascript">
			var autosociallock_use = false;
			function autosociallock_plusone(plusone) {
				if (plusone.state == "on") {
					var data = {action: "autosociallock", network: "plus"};
					jQuery.post("'.admin_url('admin-ajax.php').'", data, function(response) {
						if (autosociallock_use) location.reload();
					});
				}
			}
			FB.init();
			jQuery(document).ready(function() {
				FB.Event.subscribe("edge.create", function(href, widget) { 
					var data = {action: "autosociallock", network: "facebook"};
					jQuery.post("'.admin_url('admin-ajax.php').'", data, function(response) {
						if (autosociallock_use) location.reload();
					});
				});		
				twttr.ready(function (twttr) {
					twttr.events.bind("tweet", function(event) {
						var data = {action: "autosociallock", network: "twitter"};
						jQuery.post("'.admin_url('admin-ajax.php').'", data, function(response) {
							if (autosociallock_use) location.reload();
						});
					});
				});				
			});
			</script>
			<div id="autosociallock" style="display:none;">
				<div class="socialviral-box">                
			    '.$popupTitle.'
				  <div class="asl-socials">
					<div><fb:like layout="box_count" show_faces="false" '.$url.'></fb:like></div>
					<div><g:plusone callback="autosociallock_plusone" size="tall" '.$url.'></g:plusone></div>
					<div><a class="twitter-share-button" data-count="vertical" '.$twitterUrl.'>Tweet</a></div>
				  </div>
				</div>
				<div id="countDownDiv" class="countDownDiv"></div>
				<div class="asl-author">Powered by <a href="http://www.weontech.com" target="_blank">www.WeOnTech.com</a></div>	
				</div>
			<script type="text/javascript">			  
			  autosociallock_use = true;
			  jQuery(document).ready(function() {            
				  jQuery.facebox({div: "#autosociallock", loadingImage: "'.site_url().'/wp-content/plugins/'.basename(dirname(__FILE__)).'/images/loading.gif"});
				  '.$countDown.'  			  
				  '.$showClose.'			  
				});
			</script>
			';
		}
	}
}

$autosociallock = new automaticsociallock_class();

if(isset($autosociallock)) { 
	function plugin_settings_link($links) { 
		$settings_link = '<a href="options-general.php?page='.AUTOMATIC_SOCIAL_LOCK_PLUGINOPTIONS_ID.'">Settings</a>'; 
		array_unshift($links, $settings_link); return $links; 
	} 
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'plugin_settings_link'); 
}

endif;
?>