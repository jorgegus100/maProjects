<?php 
	/*
	plugin name: V menu plugin
	version 1.0
	Description: Vertical menu to show Ukranian cities
	*/

add_action('admin_menu', 'vmenuplugin_admin_actions');
function vmenuplugin_admin_actions(){
	add_options_page('vMenuPlugin', 'vMenuPlugin', 'manage_options',__FILE__, 'vmenupluginAdmin');
}

function vmenupluginAdmin(){
	echo "<h4>Vertical Menu</h4>";
	
	
}
function your_css_and_js() {
wp_enqueue_script('jquery');
wp_register_style('your_css_and_js', plugins_url('style.css',__FILE__ ));
wp_enqueue_style('your_css_and_js');
wp_register_script( 'your__js', plugins_url('jquery.ntm.js',__FILE__ ));
wp_enqueue_script('your__js');
wp_register_script( 'your_js_1', plugins_url('vMenu.js',__FILE__ ));
wp_enqueue_script('your_js_1');
}

add_action( 'init','your_css_and_js');

class v_menu extends WP_Widget {
	function v_menu() {
		// widget actual processes
		parent::WP_Widget(false, $name = 'Vertical Menu', array(
			'description' => 'Displays a Vertical Menu'
		));
	}
	function widget($args, $instance) {
		global $post;
		extract($args);
		global $wpdb;
		$table_name = $wpdb->prefix . "cities";
		$regions = $wpdb->get_results(
			"SELECT * FROM {$table_name} WHERE type=0"
		);
		echo $before_widget;
		$instance['title'] = "Ukraine";
		echo $before_title . $instance['title'] . $after_title;
		echo '<div class="tree-menu demo" id="tree-menu">';
		echo '<ul class="region"> Regions';
			foreach ($regions as $region ) {
				echo '<li><a>'.$region->name.'</a>';
					echo '<ul> Cities';
						$cities = $wpdb->get_results(
							"SELECT * FROM {$table_name} WHERE type=1 AND id_parent={$region->id}"
						);
						foreach ($cities as $city) {
							echo '<li><a >'.$city->name.'</a>';
								echo '<ul> Villages';
									$villages = $wpdb->get_results(
										"SELECT * FROM {$table_name} WHERE type=2 AND id_parent={$city->id}"
									);
									foreach ($villages as $village) {
											echo '<li><a > '.$village->name.'</a>';
											echo '</li>';
									}
								echo '</ul>';
							echo '</li>';
						}	
					echo '</ul>';	
				echo '</li>';
			}
		echo '</ul>';
		echo '</div>';		
		echo $after_widget;
	}
}

add_action('widgets_init', 'register_v_menu');
function register_v_menu() {
	register_widget('v_menu');
}


?>