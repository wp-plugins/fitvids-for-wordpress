<?php
/*
Plugin Name: FitVids for WordPress
Plugin URI: http://wordpress.org/extend/plugins/fitvids-for-wordpress/
Description: This plugin makes videos responsive using the FitVids jQuery plugin on WordPress.
Version: 1.0.1
Tags: videos, fitvids, responsive
Author URI: http://kevindees.cc

/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| Dashboard Cleanup - cleaning up the wordpress dashboard.           |
| Copyright (C) 2011, Kevin Dees,                                    |
| http://kevindees.cc                                               |
| All rights reserved.                                               |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |   
|                                                                    |
\--------------------------------------------------------------------/
*/

// protect yourself
if ( !function_exists( 'add_action') ) {
	echo "Hi there! Nice try. Come again.";
	exit;
}

class fitvids_wp {
	// when object is created
	function __construct() {
		add_action('admin_menu', array($this, 'menu')); // add item to menu
		add_action('wp_enqueue_scripts', array($this, 'fitvids_scripts')); // add fit vids to site
	}

	// make menu
	function menu() {
		add_submenu_page('themes.php', 'FitVids for WordPress', 'FitVids', 'administrator', __FILE__,array($this, 'settings_page'), '', '');
	}

	// create page for output and input
	function settings_page() {
		?>
	    <div class="icon32" id="icon-options-general"><br></div>
	    <div id="fitvids-wp-page" class="wrap">
	    
	    <h2>FitVids for WordPress</h2>
	    
	    <?php
	    // $_POST needs to be sanitized by version 1.0
	   	if($_POST['submit']) {
	   		update_option('fitvids_wp_jq', addslashes($_POST['fitvids_wp_jq']));
	   		update_option('fitvids_wp_selector', trim(addslashes($_POST['fitvids_wp_selector'])));
	   		
	   		if($_POST['fitvids_wp_jq'] != '') { $fitvids_wp_message .= 'You have enabled jQuery for your theme.'; }
	   		echo '<div id="message" class="updated below-h2"><p>FitVids is updated. ', $fitvids_wp_message ,'</p></div>';
	   	}
	    ?>
	    
	    <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
	    <?php
	    // settings_fields('fitvids_wp_settings_group');
	    
	    if(get_option('fitvids_wp_jq') == 'true') { $checked = 'checked="checked"'; }
	    ?>
	    
	    <table class="form-table">
	    <tbody>
	    <tr>
	    	<th><label for="fitvids_wp_jq">Add jQuery</label></th>
		    <td>
		    	<input 	id="fitvids_wp_jq" 
		    			value="true" 
		    			name="fitvids_wp_jq" 
		    			type="checkbox" 
		    			<?php echo $checked; ?>
		    	>
		    </td>
	    </tr>	
	    <tr>
		    <th><label for="fitvids_wp_selector">Enter jQuery Selector</label></th>
		    <td>
		    	<input id="fitvids_wp_selector" value="<?php echo get_option('fitvids_wp_selector'); ?>" name="fitvids_wp_selector" type="text"> <a href="http://www.w3schools.com/jquery/jquery_selectors.asp" target="_blank">Need help?</a>
	    	</td>
    	</tr>	
	    </tbody>
	    </table>
	    <p class="submit">
	    <input type="submit" name="submit" class="button-primary" value="Save Changes" /></p>
	    </form>
	    
	    </div>
	    
	    <?php }
    
    // add FitVids to site
    function fitvids_scripts() {
    	if(get_option('fitvids_wp_jq') == 'true') {
    		wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', '1.0');
			wp_enqueue_script( 'jquery' );
    	}
    	
    	// add fitvids
    	wp_register_script( 'fitvids', plugins_url('/jquery.fitvids.js', __FILE__), array('jquery'), '1.0', true);    	
    	wp_enqueue_script( 'fitvids');
    	add_action('wp_footer', array($this, 'add_fitthem'));	
    } // end fitvids_scripts
    
    // slecetor script
    function add_fitthem() { ?>
    	<script type="text/javascript">
    	jQuery(document).ready(function() {
    		jQuery('<?php echo get_option('fitvids_wp_selector'); ?>').fitVids();
    	});
    	</script><?php
    }    
} // end fitvids_wp obj

new fitvids_wp();