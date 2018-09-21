<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
class ThemeMakers_AddThis_Widget extends WP_Widget {

	public $defaults;

    function __construct() {
        $settings = array('classname' => __CLASS__, 'description' => __('Displays share buttons.', 'tmm_addthis'));
        parent::__construct(__CLASS__, __('TMM Add This', 'tmm_addthis'), $settings);
	    $this->defaults = array(
		    'title' => __('Social Share', 'tmm_addthis'),
		    'bt_type' => array(),
		    'addthis_button_facebook' => 'true',
		    'addthis_button_tweet' => 'true',
		    'addthis_button_pinterest' => 'true',
		    'addthis_button_google' => 'true',
		    'addthis_counter' => 'true'
	    );
    }
        
    function widget($args, $instance) {
	    $args['instance'] = wp_parse_args((array) $instance, $this->defaults);
	    $args['widget'] = $this;
        echo TMM_AddThis_Controller::draw_free_page(TMM_AddThis_Controller::get_application_path() . '/views/addthis_view.php', $args);
    }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		foreach ($this->defaults as $k => $v) {
			$instance[$k] = $new_instance[$k];
		}
		if (isset($instance['title'])) {
			$instance['title'] = strip_tags($instance['title']);
		}
		return $instance;
	}

    function form($instance) {
	    $args['instance'] = wp_parse_args((array) $instance, $this->defaults);
	    $args['widget'] = $this;
        echo TMM_AddThis_Controller::draw_free_page(TMM_AddThis_Controller::get_application_path() . '/views/addthis_form.php', $args);
    }

}

