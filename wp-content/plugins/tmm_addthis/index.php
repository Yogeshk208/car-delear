<?php

/**
 * Plugin Name: ThemeMakers AddThis
 * Plugin URI: http://webtemplatemasters.com
 * Description: ThemeMakers WordPress AddThis Share
 * Author: ThemeMakers
 * Version: 1.0.8
 * Author URI: http://themeforest.net/user/ThemeMakers
 * Text Domain: tmm_addthis
 */

if (!class_exists('TMM_AddThis_Controller')) {

    class TMM_AddThis_Controller {
       
        public static $options, $app_options;

        public static function get_application_path() {
            return plugin_dir_path(__FILE__);
        }

        private function get_application_uri() {
            return plugin_dir_url(__FILE__);
        }

        public static function get_option($option, $prefix = TMM_ADDTHIS_DEF_PREFIX) {
            if ($prefix == TMM_ADDTHIS_DEF_PREFIX) {
                if (isset(self::$options[$option])) {
                    return self::$options[$option];
                }
            } else {
                if (isset(self::$app_options[$prefix][$option])) {
                    return self::$app_options[$prefix][$option];
                }
            }
        }

        public static function update_option($option, $data, $prefix = TMM_ADDTHIS_DEF_PREFIX) {
            if ($prefix == TMM_ADDTHIS_DEF_PREFIX) {
                self::$options[$option] = $data;
                update_option($prefix . 'theme_options', self::$options);
            } else {
                self::$app_options[$prefix][$option] = $data;
                update_option($prefix . 'theme_app_options', self::$app_options);
            }
        }

        public static function draw_free_page($pagepath, $data = array()) {
            @extract($data);
            ob_start();
            include($pagepath);
            return ob_get_clean();
        }

        public function init() {
            
            define('TMM_ADDTHIS_PREFIX', 'tmm_addthis_');
            define('TMM_ADDTHIS_DEF_PREFIX', 'thememakers_');

            add_action('admin_notices', array($this, 'admin_notices'));
            add_action('wp_ajax_app_addthis_save_settings', array(__CLASS__, 'save_settings'));
	        add_shortcode('tmm_addthis', array(__CLASS__, 'addthis_shortcode'));
        }

        public static  function addthis_register_widgets() {
            register_widget('ThemeMakers_AddThis_Widget');
        }

	    public static function addthis_shortcode( $atts, $content = '' ) {
		    if ( !isset( $atts['buttons_type'] ) ) {
			    $atts['buttons_type'] = TMM::get_option( 'buttons_type' );
		    }

		    if ( !isset( $atts['add_buttons'] ) ) {
			    $atts['add_buttons'] = TMM::get_option( 'add_buttons' );
		    }

		    $show_buttons = TMM::get_option( 'show_buttons' );
		    $post_type    = get_post_type();

		    if ( $post_type === 'post' ) {

			    if ( isset( $show_buttons['single_blog'] ) && $show_buttons['single_blog'] === '0' ) {
				    return '';
			    }

		    }

		    if ( class_exists( 'TMM_Ext_PostType_Car' ) && $post_type === TMM_Ext_PostType_Car::$slug ) {

			    if ( isset( $show_buttons['single_car'] ) && $show_buttons['single_car'] === '0' ) {
				    return '';
			    }

		    }

		    echo TMM_AddThis_Controller::draw_free_page( TMM_AddThis_Controller::get_application_path() . '/views/output.php', $atts );
	    }

        public function save_settings() {
            $data = array();
            parse_str($_REQUEST['values'], $data);
            $data = TMM_Helper::db_quotes_shield($data);
            if (!empty($data)) {
                foreach ($data as $key => $value) {

                    self::update_option($key, $value);
                }
            }
            _e('Options have been saved.', 'tmm_addthis');
            exit;
        }

        function admin_notices() {
            $notices = "";

            echo $notices;
        }

        public function draw_html($view, $data = array()) {
            @extract($data);
            ob_start();
            include(self::get_application_path() . '/views/' . $view . '.php');
            return ob_get_clean();
        }

        public function load_addthis_admin_style() {
            wp_register_style('addthis_css', $this->get_application_uri() . '/css/styles.css', false, '1.0.0');
            wp_enqueue_style('addthis_css');

            wp_register_script('addthis_admin_js', $this->get_application_uri() . '/js/addthis_admin.js', false, '1.0.0');
            wp_enqueue_script('addthis_admin_js');
        }

        public function enqueue_scripts() {
            wp_register_script('tmm_addthis_widget', 'https://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f3c188f442f3bf2', array(), false, true);

	        global $post;

	        if (get_post_type() === 'post') {

		        $post_pod_type = get_post_meta($post->ID, 'post_pod_type', true);
		        $thumb_src = TMM_Helper::get_post_featured_image($post->ID, '840*500');

		        if ($post_pod_type === 'video_test') { //todo: share video on facebook in testing mode
			        $post_type_values = get_post_meta( get_the_ID(), 'post_type_values', true );
			        $source_url = $post_type_values['video'];

			        if (!has_post_thumbnail()) {
				        if (strpos($source_url, "youtube.com") !== false || strpos($source_url, "youtu.be") !== false) {

					        if (strpos($source_url, "youtube.com") !== false || strpos($source_url, "youtu.be") !== false) {
					            $video_youtube = explode("?v=", $source_url);
					        } else {
					            $video_youtube = explode("youtu.be/", $source_url);
					        }

					        if (!empty($video_youtube[1])) {
						        $thumb_src = 'http://img.youtube.com/vi/'. $video_youtube[1] .'/default.jpg';
					        }

				        } else if (strpos($source_url, "vimeo.com") !== false) {
					        $arr = parse_url($source_url);

					        if (!empty($arr['path'])) {
						        $xml = simplexml_load_file('http://vimeo.com/api/v2/video' . $arr['path'] . '.xml');

						        if ($xml) {
							        $thumb_src = (string) $xml->video->thumbnail_medium;
						        }
					        }

				        }
			        }

			        if (!empty($source_url)) {
				        ?>
				        <meta property="og:url"                content="<?php the_permalink() ?>" />
				        <meta property="og:type"               content="article" />
				        <meta property="og:video"              content="<?php echo $source_url ?>" />
				        <meta property="og:video:secure_url"   content="<?php echo $source_url ?>" />
				        <meta property="og:image"              content="<?php echo $thumb_src ?>" />
				        <?php
			        }

		        } else {

			        if (!has_post_thumbnail()) {
				        if ($post_pod_type === 'gallery') {
					        $post_type_values = get_post_meta( get_the_ID(), 'post_type_values', true );
					        $gall = $post_type_values['gallery'];
					        $thumb_src = $gall[0];

					        if (is_multisite()) {
						        $path = wp_upload_dir();
						        $temp = explode('wp-content/uploads', $thumb_src);
						        $thumb_src = $path['baseurl'] . $temp[1];
					        }
				        }
			        }

			        ?>
			        <meta property="og:url"                content="<?php the_permalink() ?>" />
			        <meta property="og:type"               content="article" />
			        <meta property="og:image"              content="<?php echo $thumb_src ?>" />
			        <meta property="og:image:width"        content="840" />
			        <meta property="og:image:height"       content="500" />
		            <?php
		        }
	        } else if (class_exists('TMM_Ext_PostType_Car') && get_post_type() === TMM_Ext_PostType_Car::$slug) {
		        $thumb_src = tmm_get_car_cover_image($post->ID, 'thumb');
		        ?>
		        <meta property="og:url"                content="<?php the_permalink() ?>" />
		        <meta property="og:type"               content="article" />
		        <meta property="og:image"              content="<?php echo $thumb_src ?>" />
		        <meta property="og:image:width"        content="460" />
		        <meta property="og:image:height"       content="290" />
	            <?php
	        } else {
		        ?>
		        <meta property="og:url"            content="<?php the_permalink() ?>" />
		        <meta property="og:title"          content="<?php the_title() ?>" />
		        <meta property="og:description"    content="<?php the_excerpt() ?>" />
				<?php
	        }

        }

    }

}

include_once TMM_AddThis_Controller::get_application_path() . 'widget/addthis_widget.php';

add_action('init', array(new TMM_AddThis_Controller(), 'init'), 999);

add_action( 'widgets_init', array(new TMM_AddThis_Controller(), 'addthis_register_widgets'));

add_action('admin_enqueue_scripts', array(new TMM_AddThis_Controller(), 'load_addthis_admin_style'));
add_action('wp_enqueue_scripts', array(new TMM_AddThis_Controller(), 'enqueue_scripts'));

/**
 * Load plugin textdomain.
 */
function tmm_addthis_load_textdomain() {
	load_plugin_textdomain( 'tmm_addthis', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'tmm_addthis_load_textdomain' );

/**
 * Add Settings tab.
 */
function tmm_addthis_add_settings_tab() {
	if ( current_user_can('manage_options') ) {
		if (class_exists('TMM_OptionsHelper')) {

			$content = array();
			$tmpl_path = TMM_AddThis_Controller::get_application_path() . '/views/theme_options_tab.php';

			$content[ 'tmm_addthis' ] = array(
				'title' => '',
				'type' => 'custom',
				'custom_html' => TMM::draw_free_page($tmpl_path),
				'show_title' => false
			);

			$sections = array(
				'name' => __("Add This Share", 'tmm_addthis'),
				'css_class' => 'shortcut-plugins',
				'show_general_page' => true,
				'content' => $content,
				'child_sections' => array(),
				'menu_icon' => 'dashicons-share'
			);

			TMM_OptionsHelper::$sections[ 'tmm_addthis' ] = $sections;

		}
	}
}

add_action( 'tmm_add_theme_options_tab', 'tmm_addthis_add_settings_tab', 90 );