<?php
if( !defined( 'CP_BASE_DIR_IFB' ) ) {
	define( 'CP_BASE_DIR_IFB', plugin_dir_path( __FILE__ ));
}
require_once(CP_BASE_DIR_IFB.'/functions/functions.php');
if(!class_exists('Smile_Info_Bars')){
	class Smile_Info_Bars extends Convert_Plug{
		public static $settings = array();
		public static $options = array();

		function __construct(){
			add_action( 'admin_menu',array($this,'add_admin_menu_page' ), 999);
			add_action( 'admin_head',array($this,'load_customizer_scripts' ) );
			add_action( 'wp_footer', array( $this, 'load_info_bar_globally' ) );
			add_action( 'wp_enqueue_scripts',array($this,'enqueue_front_scripts' ), 101);
			add_action( 'admin_enqueue_scripts',array($this,'enqueue_admin_scripts' ) );
			add_action( 'init', array( $this, 'register_theme_templates') );
			add_filter( 'admin_body_class', array( $this, 'cp_admin_body_class') );
			require_once(CP_BASE_DIR_IFB.'info_bar_preset.php');
		}

		function cp_admin_body_class( $classes ) {

			if( isset( $_GET['style-view']) && $_GET['style-view'] === "new" && strpos( $classes, "cp-add-new-style" ) === false ){
	        	$classes .= ' cp-add-new-style';
				$classes = implode(' ',array_unique(explode(' ', "cp-add-new-style")));
	        }
			return $classes;
		}

		function register_theme_templates(){
			$dir = plugin_dir_path( __FILE__ );
			$themes = glob($dir . 'themes/*.php');
			foreach( $themes as $theme ){
				require_once( $theme );
			}
		}

		function add_admin_menu_page(){
			$page = add_submenu_page(
				CP_PLUS_SLUG,
				'Info Bar Designer',
				'Info Bar',
				'access_cp',
				'smile-info_bar-designer',
				array($this,'info_bar_dashboard') );
			$obj = new Convert_Plug;
			add_action( 'admin_print_scripts-' . $page, array($obj,'convert_admin_scripts'));
			add_action( 'admin_print_scripts-' . $page, array($this,'info_bar_admin_scripts'));
            add_action( 'admin_footer-'. $page, array($this,'cp_admin_footer') );

		}

		function info_bar_admin_scripts(){
			if( ( isset( $_GET['style-view'] ) && ( $_GET['style-view'] === "edit" || $_GET['style-view'] === "variant") ) || !isset( $_GET['style-view'] ) ) {

				wp_enqueue_script( 'smile-info_bar-receiver',			CP_PLUGIN_URL. 'modules/info_bar/assets/js/receiver.js' );
				wp_enqueue_style( 'cp-contacts',						CP_PLUGIN_URL.'admin/contacts/css/cp-contacts.css' );
				wp_enqueue_media();
				wp_enqueue_script( 'smile-info_bar-importer',			CP_PLUGIN_URL.'modules/assets/js/admin-media.js',array( 'jquery' ), '', true );
			}

			if( isset($_GET['style-view']) && $_GET['style-view'] === 'analytics' ) {
				wp_enqueue_style( 'css-select2',						CP_PLUGIN_URL.'admin/assets/select2/select2.min.css' );
				wp_enqueue_script( 'convert-select2',					CP_PLUGIN_URL.'admin/assets/select2/select2.min.js' );
				wp_enqueue_script( 'bsf-charts-js',						CP_PLUGIN_URL.'admin/assets/js/chart.js' );
				wp_enqueue_script( 'bsf-charts-bar-js',					CP_PLUGIN_URL.'admin/assets/js/chart.bar.js' );
				wp_enqueue_script( 'bsf-charts-donut-js',				CP_PLUGIN_URL.'admin/assets/js/chart.donuts.js' );
				wp_enqueue_script( 'bsf-charts-line-js',				CP_PLUGIN_URL.'admin/assets/js/Chart.Line.js' );
				wp_enqueue_script( 'bsf-charts-polararea-js',			CP_PLUGIN_URL.'admin/assets/js/Chart.PolarArea.js' );
				wp_enqueue_script( 'bsf-style-analytics-js',			CP_PLUGIN_URL.'modules/assets/js/style-analytics.js' );
			}
		}

		function info_bar_dashboard(){
			$page = isset($_GET['style-view']) ? esc_attr( $_GET['style-view'] ) : 'main';

			// load default option set
			if( is_admin() ){
				require_once(CP_BASE_DIR_IFB.'/functions/functions.options.php');
			}

			switch($page){
				case 'main':
					require_once(CP_BASE_DIR_IFB.'/views/main.php');
					break;
				case 'new':
					$default_google_fonts = array (
						"Lato",
						"Open Sans",
						"Libre Baskerville",
						"Montserrat",
						"Neuton",
						"Raleway",
						"Roboto",
						"Sacramento",
						"Varela Round",
						"Pacifico",
						"Bitter"
					);
					$gfonts = implode( ",", $default_google_fonts );
					require_once(CP_BASE_DIR_IFB.'/functions/functions.php');
					if( function_exists( "cp_enqueue_google_fonts" ) ){
						cp_enqueue_google_fonts( $gfonts );
					}
					require_once(CP_BASE_DIR_IFB.'/views/new-style.php');
					break;
				case 'edit':
					require_once(CP_BASE_DIR_IFB.'/views/edit.php');
					break;
				case 'variant':
					require_once(CP_BASE_DIR_IFB.'/views/variant.php');
					break;
				case 'analytics':
					require_once(CP_BASE_DIR_IFB.'/views/analytics.php');
					break;
			}
		}

		function load_info_bar_globally(){

			if(!isset($_GET['hidemenubar'])){
				?>
	            <script type="text/javascript" id="info-bar">
				jQuery(window).on( 'load', function(){
					startclock();
				});
				function stopclock (){
				  if(timerRunning) clearTimeout(timerID);
				  timerRunning = false;
				  document.cookie="time=0";
				}
				function showtime () {
				  var now = new Date();
				  var my = now.getTime() ;
				  now = new Date(my-diffms) ;
				  document.cookie="time="+now.toLocaleString();
				  timerID = setTimeout('showtime()',10000);
				  timerRunning = true;
				}
				function startclock () {
				  stopclock();
				  showtime();
				}
				var timerID = null;
				var timerRunning = false;
				var x = new Date() ;
				var now = x.getTime() ;
				var gmt = <?php echo time(); ?> * 1000 ;
				var diffms = (now - gmt) ;
				</script>
	            <?php

				$info_bar_style = $info_bar_style_delay = $info_bar_cookie_delay = '';
				$live_styles = cp_get_live_styles('info_bar');
				$prev_styles = get_option('smile_info_bar_styles');
				$smile_variant_tests = get_option('info_bar_variant_tests');

				if( is_array($live_styles) && !empty( $live_styles ) ) {

					global $post;
					$info_bar_arrays = $live_styles;
					$taxonomies = get_post_taxonomies( $post );

					foreach( $info_bar_arrays as $key => $info_bar_array ) {
						$display = false;
						$settings_encoded = '';

						$style_settings = array();
						$global_display = $pages_to_exclude = $cats_to_exclude = $exclusive_pages = $exclusive_cats = $show_for_logged_in = '';
						$settings_array = unserialize($info_bar_array[ 'style_settings' ]);
						foreach($settings_array as $key => $setting){
							$style_settings[$key] = apply_filters( 'smile_render_setting',$setting );
						}

						$style_id = $info_bar_array[ 'style_id' ];
						$info_bar_style = $style_settings[ 'style' ];

						if( is_array($style_settings) && !empty($style_settings) ) {

							$settings = unserialize( $info_bar_array[ 'style_settings' ] );
							$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';

							$display = cp_is_style_visible($settings);

							// remove back slashes from settings
							$settings = stripslashes_deep( $settings );

							$settings = serialize( $settings );
							$settings_encoded 	= base64_encode( $settings );
						}

						if( $display ) {

							// developer mode
							if( self::$cp_dev_mode === '1' ) {

								$script_handlers = array(
									'smile-cp-common-script',
									'smile-info-bar-script',
									'cp-ideal-timer-script',
									'cp-info-bar-mailer-script'
								);

	   							$list = 'enqueued';

	   							foreach( $script_handlers as $handler ) {
	   								if ( !wp_script_is( $handler, $list ) ) {
								       wp_enqueue_script( $handler );
								    }
	   							}

	   						} else {   							

								if ( !wp_script_is( 'cp-module-main-js', 'enqueued' ) ) {
								    wp_enqueue_script( 'cp-module-main-js' );
								}

								if ( !wp_script_is( 'smile-info-bar-script', 'enqueued' ) ) {
								    wp_enqueue_script( 'smile-info-bar-script' );
								}
	   						}

							//	Convert style name to lowercase - ( Case sensitive URL's not accessible by server )
							$info_bar_style = strtolower( $info_bar_style );

                            //    Generate style ID
                            $id = $info_bar_style . '-' . $style_id;

                            //    Individual Style Path
                            $file_name = '/assets/demos/'. strtolower($info_bar_style) . '/' . strtolower($info_bar_style) . '.min.css';
                            $url = CP_PLUGIN_URL.'modules/info_bar/'. $file_name ;

                            echo '<link rel="stylesheet" id="'.$id.'" href="' . $url .'" type="text/css" media="all" />';

							echo do_shortcode('[smile_info_bar style_id = '.$style_id.' style="'.$info_bar_style.'" settings_encoded="' . $settings_encoded . ' "][/smile_info_bar]');
							$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';
							apply_filters('cp_custom_css',$style_id, $css);
						}
					}
				}
			}
		}

		function load_customizer_scripts(){

			if( ( isset( $_GET['hidemenubar'] ) && isset( $_GET['module'] ) && esc_attr( $_GET['module'] ) === "info_bar" ) ) {

				//countdown js
				if( isset($_GET['theme']) && $_GET['theme'] === 'countdown' ){
					wp_enqueue_style( 'cp-countdown-style');
					wp_enqueue_script( 'cp-counter-plugin-js');
					wp_enqueue_script( 'cp-countdown-js');
				}

				wp_enqueue_script( 'cp-admin-customizer-js', 	CP_PLUGIN_URL.'modules/assets/js/admin.customizer.js' );
				wp_enqueue_script( 'smile-info_bar-common-functions-js', 	CP_PLUGIN_URL.'modules/info_bar/assets/js/functions-common.js', array('jquery') );

				require_once( CP_BASE_DIR_IFB.'/functions/functions.options.php' );
				$demo_html = $customizer_js = '';
				$settings = $this::$options;
				foreach( $settings as $style => $options ){
					if( $style === esc_attr( $_GET['theme'] ) ) {
						$customizer_js = $options['customizer_js'];
					}
				}
				if( $customizer_js !== "" ){
					wp_enqueue_script( 'cp-style-customizer-js', $customizer_js , array( 'jquery' ), "", true );
				}
			}
		}

		function enqueue_admin_scripts($hook) {
			if( ( isset( $_GET['hidemenubar'] ) && $_GET['module'] === 'info_bar' )
					|| ( isset($_GET['style-view']) && $_GET['style-view'] === 'new' && $hook === CP_PLUS_SLUG.'_page_smile-info_bar-designer' ) ) {

				wp_enqueue_style( 'smile-info-bar', CP_PLUGIN_URL.'modules/info_bar/assets/css/info_bar.min.css' );

				wp_localize_script( 'jquery', 'cp', array(
														'demo_dir' => CP_PLUGIN_URL.'modules/info_bar/assets/demos' ,
														'module' => 'info_bar',
														"module_img_dir" => CP_PLUGIN_URL.'modules/assets/images', 
														) );

				//	Add 'Theme Name' as a class to <html> tag
				//	To provide theme compatibility
				$theme_name = wp_get_theme();
				$theme_name = $theme_name->get( "Name" );
				$theme_name = strtolower( preg_replace("/[\s_]/", "-", $theme_name ) );

				wp_localize_script( 'jquery', 'cp_active_theme', array( 'slug' => $theme_name ) );
				wp_localize_script( 'jquery', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}

		function enqueue_front_scripts(){

			$live_styles = cp_get_live_styles('info_bar');

			// if any style is live or info_bar is in live preview mode then only enqueue scripts and styles
			if( $live_styles && count($live_styles) > 0 ) {

				$handler ='';
				if( self::$cp_dev_mode === '1' ) {

					// register styles
					wp_enqueue_style( 'smile-info-bar-style', CP_PLUGIN_URL.'modules/info_bar/assets/css/info_bar.css' );
					wp_enqueue_style( 'cp-animate-style', CP_PLUGIN_URL.'modules/assets/css/animate.css' );
					wp_enqueue_style( 'convertplug-style', CP_PLUGIN_URL.'modules/assets/css/convertplug.css' );
					wp_enqueue_style( 'cp-social-media-style', CP_PLUGIN_URL.'modules/assets/css/cp-social-media-style.css' );
					wp_enqueue_style( 'cp-social-icon-style', CP_PLUGIN_URL.'modules/assets/css/social-icon-css.css' );
					wp_enqueue_style( 'cp-social-icon-dev-style', CP_PLUGIN_URL.'modules/assets/css/social-icon.css' );

					// register scripts
					wp_register_script( 'smile-cp-common-script', CP_PLUGIN_URL.'modules/assets/js/convertplug-common.js', array( 'jquery' ), null, null, true );
					wp_register_script( 'smile-info-bar-script', CP_PLUGIN_URL.'modules/info_bar/assets/js/info_bar.js', array( 'jquery','smile-cp-common-script'));

					wp_register_script( 'cp-info-bar-mailer-script', CP_PLUGIN_URL.'modules/info_bar/assets/js/mailer.js', array( 'jquery' ), null,
								null, true );

				} else {
					wp_enqueue_style( 'smile-info-bar-style', CP_PLUGIN_URL.'modules/info_bar/assets/css/info_bar.min.css' );
					wp_register_script( 'smile-info-bar-script', CP_PLUGIN_URL.'modules/info_bar/assets/js/info_bar.min.js', array( 'jquery' ));
				}
			}

			wp_localize_script( 'smile-info-bar-script', 'smile_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	$Smile_Info_Bars = new Smile_Info_Bars;
}

if (!function_exists('smile_info_bar_popup')) {
	function smile_info_bar_popup( $atts, $content = null ) {
		$style = '';
		extract(shortcode_atts(array(
			'style' 				=> '',
			'style_name'			=> '',
		), $atts));
		$output = '';
		$func = 'info_bar_theme_'.$style;

		$settings = base64_decode( $atts['settings_encoded'] );
		$style_settings = unserialize( $settings );

		// remove back slashes from settings
		$settings = stripslashes_deep( $style_settings );

		$settings = serialize( $settings );
		$settings_encoded 	= base64_encode( $settings );
		$atts['settings_encoded'] = $settings_encoded;

		if( function_exists( $func ) ) {
			$output = $func( $atts );
		}
		echo $output;
	}
	add_shortcode('smile_info_bar', 'smile_info_bar_popup');
}

if (!function_exists('cp_info_bar_custom')) {
	function cp_info_bar_custom( $atts, $content = null ) {
		ob_start();
		$id = $display = '';
		extract(shortcode_atts(array(
			'id' 				=> '',
			'display'			=> '',
		), $atts));
		$live_styles = cp_get_live_styles('info_bar');
		$live_array = $settings = '';
		foreach( $live_styles as $key => $info_bar_array ){
			$style_id = $info_bar_array[ 'style_id' ];

			$settings = unserialize( $info_bar_array[ 'style_settings' ] );
			if(isset($settings['variant_style_id']) && $id === $settings['style_id']){
				$id = $settings['variant_style_id'];
			}

			if( $id === $style_id ) {
				$live_array = $info_bar_array;
				$settings = unserialize( $info_bar_array[ 'style_settings' ] );
				$settings_array = unserialize($info_bar_array[ 'style_settings' ]);
				foreach($settings_array as $key => $setting){
					$style_settings[$key] = apply_filters( 'smile_render_setting',$setting );
				}
				$info_bar_style = $style_settings[ 'style' ];
				$global = $style_settings[ 'global' ];
				$style_settings[ 'display' ] = $display;
				$style_settings['custom_class'] .= isset( $style_settings['custom_class']) ? $style_settings['custom_class'].',cp-trigger-'.$style_id : 'cp-trigger-'.$style_id;
				$display = cp_is_style_visible($style_settings);

				// remove back slashes from settings
				$settings = stripslashes_deep( $settings );

				$encode_settings = serialize( $style_settings );
				$settings_encoded = base64_encode( $encode_settings );

				echo '<span class="cp-trigger-shortcode cp-trigger-'.$style_id.' cp-'.$style_id.'">'.do_shortcode( $content ).'</span>';
				if( $display ){
					//	Generate style ID
					$id = $info_bar_style . '-' . $style_id;

					//	Individual Style Path
					$file_name = '/assets/demos/'. strtolower($info_bar_style) . '/' . strtolower($info_bar_style) . '.min.css';
					$url = CP_PLUGIN_URL.'modules/info_bar/'. $file_name ;

					//	Check file exist or not - and append to the head
					echo '<link rel="stylesheet" id="'.$id.'" href="' . $url .'" type="text/css" media="all" />';
					echo do_shortcode('[smile_info_bar manual="true" style_id = '.$style_id.' style="'.$info_bar_style.'" settings_encoded="' . $settings_encoded . ' "][/smile_info_bar]');
					$css = isset( $settings['custom_css'] ) ? urldecode($settings['custom_css']) : '';
					apply_filters('cp_custom_css',$style_id, $css);
				}
				break;
			}
		}
		return ob_get_clean();
	}
	add_shortcode('cp_info_bar', 'cp_info_bar_custom');
}
