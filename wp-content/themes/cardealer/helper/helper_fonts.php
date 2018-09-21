<?php if (!defined('ABSPATH')) die('No direct access allowed');

class TMM_HelperFonts {

    static public $included_fonts = array();

	/* get default fonts + google fonts array */
    static function get_fonts_array() {
        $google_fonts_array = self::get_google_fonts_array();
        $fonts = array(
            '' => __('Default', 'cardealer'),
            'Arial' => 'Arial',
            'Tahoma' => 'Tahoma',
            'Verdana' => 'Verdana',
            'Calibri' => 'Calibri',
        );

        foreach ($google_fonts_array as $font_value){
            $fonts[$font_value] = $font_value;
        }

        ksort($fonts);
        return $fonts;
    }

    /* get google fonts array */
    static function get_google_fonts_array() {
        $fonts_file = TMM_THEME_PATH . '/helper/google-web-fonts.json';
        $fonts_list = json_decode( @file_get_contents( $fonts_file ), true );
        $fonts = array();

        foreach ($fonts_list['items'] as $value) {
            $fonts[$value['family']] = $value['family'];
        }

        return $fonts;
    }

    /* get google fonts link */
	static function get_google_fonts_link($fonts = array()) {
		$fonts_url = '';

		if(!empty($fonts)){
			$all_google_fonts = self::get_google_fonts_array();
			$all_google_fonts = array_flip($all_google_fonts);

			foreach($fonts as $key => $value){
				if(isset($all_google_fonts[$key]) && !isset(self::$included_fonts[$key])){
					$fonts[$key] = remove_query_arg( 'subset', $all_google_fonts[$key] );
					self::$included_fonts[$key] = 1;
				}else{
					unset($fonts[$key]);
				}
			}

			if(!empty($fonts)){

				if ( 'off' !== _x( 'on', 'Google fonts: on or off', 'cardealer' ) ) {
					$query_args = array(
						'family' => urlencode( implode( '|', str_replace('_', ' ', $fonts) ) ),
						'subset' => urlencode( 'latin,latin-ext' ),
					);

					$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

				}

			}
		}

		return esc_url_raw( $fonts_url );
	}
}

