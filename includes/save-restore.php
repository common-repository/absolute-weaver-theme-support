<?php
/** save-restore.php
 *  (c) Copyright 2019, Bruce E. Wampler
 *
 *  This file is provided in the Absolute Weaver Theme Support plugin to allow save and restore
 *
 */

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Absolute_Weaver_Save_WV_Settings' ) ) {
	/**
	 * Class Absolute_Weaver_Save_Settings
	 *
	 * Save Absolute Weaver Settings
	 *
	 * NOTE: This whole file reads and writes settings to and from the user's computer.
	 *    It makes extensive uses of $_REQUEST to get the nonces from the customizer command options. Since
	 *    these uses are only for nonces, and are only useds in a safe manner to verify the nonce, there is
	 *    no need to sanitize them. In addition, the input and output of the options can't be sanitized
	 *    either, or it will break the structure.
	 *
	 */

	/*  This is a list of all the text in this plugin. We can duplicate these in Absolute Weaver so that they
	 *  get included in the .pot file
	 *
	 array( __( 'Absolute Weaver Theme', 'absolute-weaver' ),
	 __( '<strong>Save all</strong> current core <em>Absolute Weaver Theme</em> settings to file on your computer. ( Full settings backup, including those marked with &diams;. Does <strong>NOT</strong> include Absolute Weaver Plus settings. ) <em>File:</em>', 'absolute-weaver' ),
	 __( 'Save ALL Absolute Weaver Settings', 'absolute-weaver' ),
	 __( '<strong><em>Save only theme related</em></strong> current settings to file on your computer. <em>File: </em>', 'absolute-weaver' ),

	)

	 *
	 */
	class Absolute_Weaver_Save_WV_Settings extends WP_Customize_Control {

		public $description = '';
		public $code;

		public function render_content() {

			$a_pro = ( absolute_weaver_cz_is_plus() ) ? '-plus' : '';

			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			if ( '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . wp_kses_data( $this->description ) . '</span>';
			}

			echo '<span class="description customize-control-description">';
			echo '<h3>' . esc_html__( 'Absolute Weaver Theme', 'absolute-weaver' ) . '</h3><p>';
			echo wp_kses_post( __( '<strong>Save all</strong> current core <em>Absolute Weaver Theme</em> settings to file on your computer. ( Full settings backup, including those marked with &diams;. ) <br /><br /><em>File:</em>', 'absolute-weaver' ) ); ?>
			<strong>absolute-weaver-settings-backup<?php echo esc_html( $a_pro ); ?>.json</strong><br/><br/>

			<input type="button" class="button-primary" name="wvrx_save_all" value="<?php esc_attr_e( 'Save ALL Absolute Weaver Settings', 'absolute-weaver' ); ?>" />
			<br/><br/>

			<?php echo wp_kses_post( __( '<strong><em>Save only theme related</em></strong> current settings to file on your computer. <em>File: </em>', 'absolute-weaver' ) ); ?>
			<strong>absolute-weaver-settings-theme<?php esc_html( $a_pro ); ?>.json</strong><br/><br/>
			<input type="button" class="button-primary" name="wvrx_save" value="<?php esc_attr_e( 'Save THEME RELATED Settings', 'absolute-weaver' ); ?>"/>
			<br/>


			<?php

			if ( absolute_weaver_cz_is_plus() ) {

				echo '<br /><br /><hr /><h3>' . esc_html__( 'Absolute Weaver Plus', 'absolute-weaver' ) . esc_html( ABSOLUTE_WEAVER_PLUS_ICON ) . '</h3><p>';
				echo wp_kses_post( __( 'Note: The previous download settings will include <em>Absolute Weaver Plus</em> settings values ( if <em>Absolute Weaver Plus</em> is installed ) along with the free version settings.
The previous Save buttons do <em>not</em> include advanced <em>Absolute Weaver Plus</em> options like shortcodes or SmartMenu settings.', 'absolute-weaver' ) )
				     . '</p>';

				echo '<p>';
				echo wp_kses_post( __( '<strong>Save ALL Settings</strong> - Basic Absolute Weaver, including &diams;, &star;, and &starf;.', 'absolute-weaver' ) ); ?>
				</p><p><strong>File: aweaver-settings-timestamp.wxall</strong></p>

				<input type="button" class="button-primary" name="wvrx_save_xplus" value="<?php esc_attr_e( 'Save ALL Settings, including Absolute Weaver Plus', 'absolute-weaver' ); ?>"/>
				<?php
				echo "<br /><br />";
			}


			echo '<hr style="border-top: 3px double #8c8b8b;">';
		}

		static public function process_save( $wp_customize ) {

			if ( current_user_can( 'edit_theme_options' ) ) {
				if ( isset( $_REQUEST['wvrx_save'] ) ) {
					if ( wp_verify_nonce( $_REQUEST['wvrx_save'], 'wvrx-settings-saving' ) )            // use the wp_verify_nonce to validate the $_REQUEST value
					{
						self::_save_settings( $wp_customize, 'theme' );
					}
				} elseif ( isset( $_REQUEST['wvrx_save_all'] ) ) {
					if ( wp_verify_nonce( $_REQUEST['wvrx_save_all'], 'wvrx-settings-saving' ) )        // use the wp_verify_nonce to validate the $_REQUEST value
					{
						self::_save_settings( $wp_customize, 'all' );
					}
				} elseif ( isset( $_REQUEST['wvrx_save_xplus'] ) ) {
					if ( wp_verify_nonce( $_REQUEST['wvrx_save_xplus'], 'wvrx-settings-saving' ) )        // use the wp_verify_nonce to validate the $_REQUEST value
					{
						self::_save_settings( $wp_customize, 'plus' );
					}
				}
			}
		}

		static private function _absolute_weaver_filter_strip_default( $var ) {
			if ( ! is_string( $var ) ) {
				return true;
			}

			return strlen( $var ) && $var != 'default';
		}


		static private function _save_settings( $wp_customize, $ext ) {

			// Note: a $_REQUEST based nonce has been verified before this function called

			if ( headers_sent() ) {
				header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
				wp_die( esc_html__( 'Headers Sent: The headers have been sent by another plugin - there may be a plugin conflict.', 'absolute-weaver' ) );
			}


			if ( $ext == 'theme' ) {

				$fn = 'aweaver-settings-theme.json';


				$absolute_weaver_opts = get_theme_mod( apply_filters( 'absolute_weaver_options', 'absolute_weaver_settings' ), array() );

				$absolute_weaver_save = array();

				foreach ( $absolute_weaver_opts as $opt => $val ) { // create copy with
					if ( $opt[0] != '_' ) {
						$absolute_weaver_save[ $opt ] = $val;
					}
				}

				unset( $absolute_weaver_save['absolute_weaver_css_saved']);

				// Before saving, add WP CSS settings

				$absolute_weaver_save['wp_css'] =  wp_get_custom_css( );    // fetch

				$absolute_weaver_settings = json_encode( $absolute_weaver_save );

			} elseif ( $ext == 'all' ) {

				$fn = 'aweaver-settings-backup.json';

				$absolute_weaver_opts = get_theme_mod( apply_filters( 'absolute_weaver_options', 'absolute_weaver_settings' ), array() );

				unset( $absolute_weaver_opts['absolute_weaver_css_saved']);

				// Before saving, add WP CSS settings

				$absolute_weaver_opts['wp_css'] =  wp_get_custom_css( );    // fetch

				$absolute_weaver_settings = json_encode( $absolute_weaver_opts );

			}

			// Set the download headers.
			header( 'Content-disposition: attachment; filename=' . $fn );
			header( 'Content-Type: application/octet-stream; charset=utf-8' );

			// echo the export data.
			echo $absolute_weaver_settings;

			// Start the download.
			die();
		}

		static public function enqueue_scripts() {
			// scripts loaded by Absolute_Weaver_Load_WA_Subtheme
		}

	}
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Absolute_Weaver_Restore_WV_Settings' ) ) {

	class Absolute_Weaver_Restore_WV_Settings extends WP_Customize_Control {

		public $description = '';
		public $code;
		static private $wvrx_error = '';

		/**
		 */
		public function render_content() {

			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			if ( '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
			}
			?>
			<div class="wvrx-settings-restore-controls">
				<input type="file" name="wvrx-settings-restore-file" class="wvrx-settings-restore-file"/>

				<?php wp_nonce_field( 'wvrx_restore', 'wvrx-settings-restore' ); ?>
			</div>
			<div class="wvrx-uploading"><?php esc_html_e( 'Uploading...', 'absolute-weaver' ); ?></div>
			<input type="button" class="button-primary" name="wvrx_restore" value="<?php esc_attr_e( 'Upload Absolute Weaver Settings', 'absolute-weaver' ); ?>"/>
			<?php
		}

		static public function process_restore( $wp_customize ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				if ( isset( $_REQUEST['wvrx-settings-restore'] ) ) {
					self::_restore( $wp_customize );
				}

			}
		}

		static private function _restore( $wp_customize ) {
			// Make sure we have a valid nonce.
			if ( ! wp_verify_nonce( $_REQUEST['wvrx-settings-restore'], 'wvrx_restore' ) ) {
				unset( $_POST['wvrx-settings-restore'] );
				unset( $_REQUEST['wvrx-settings-restore'] );

				return;
			}
			unset( $_POST['wvrx-settings-restore'] );
			unset( $_REQUEST['wvrx-settings-restore'] );

			// Make sure WordPress upload support is loaded.
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );        // ( Not a template file )
			}


			// Setup global vars.
			global $wp_customize;

			// upload theme from users computer
			// they've supplied and uploaded a file

			$ok = true;     // no errors so far

			if ( isset( $_FILES['wvrx-settings-restore-file']['name'] ) ) {
				$filename = $_FILES['wvrx-settings-restore-file']['name'];
			} else {
				$filename = "";
			}

			if ( isset( $_FILES['wvrx-settings-restore-file']['tmp_name'] ) ) {
				$openname = $_FILES['wvrx-settings-restore-file']['tmp_name'];
			} else {
				$openname = "";
			}

			//Check the file extension
			$check_file = strtolower( $filename );
			$pat = '.';                // PHP version strict checking bug...
			$end = explode( $pat, $check_file );
			$ext_check = end( $end );

			if ( $filename == "" ) {
				return;
			}

			if ( $ok && $ext_check != 'wxt' && $ext_check != 'wxb' && $ext_check != 'json' ) {
				self::$wvrx_error = esc_html__( 'Theme files must have .wxt, .wxb, or .json extension.', 'absolute-weaver' ) . '<br />';

				return;
			}

			if ( ! absolute_weaver_f_exists( $openname ) ) {
				self::$wvrx_error = esc_html__( 'Sorry, there was a problem uploading your file. You may need to check your folder permissions or other server settings.', 'absolute-weaver' ) . esc_html__( 'Trying to use file', 'absolute-weaver' ) . $openname;
				absolute_weaver_alert( self::$wvrx_error );

				return;
			}


			// Get the upload data.
			$contents = implode( '', file( $openname ) );    // works if no newlines in the file...
			if ( empty( $contents ) ) {
				return false;
			}

			// Remove the uploaded file.
			unlink( $openname );
			unset( $FILES );

			if ( ! self::reset_options( $contents, $ext_check ) ) {
				return;
			}

			// we will now redirect to the customizer so all settings are reloaded
			return true;
		}

		static public function reset_options( $contents, $ext ) {

			if ( $ext == 'json' ) {         // restore new json format

				$opts = json_decode($contents, true );

				if ( empty( $opts ) ) {
					print_r( $contents );
					self::$wvrx_error = esc_html__( "Loading of theme settings file failed", 'absolute-weaver' );
					absolute_weaver_alert( self::$wvrx_error );

					return false;
				}

				// Before anything else, restore WP CSS settings

				if ( isset( $opts['wp_css'] ) ) {
					wp_update_custom_css_post( $opts['wp_css'] );   // replace with saved version
					unset ( $opts['wp_css'] );
				} else {
					wp_update_custom_css_post( '' );   // wipe previous settings
				}

				// see if theme or backup settings by looking for '_' options

				$is_backup = false;
				foreach ( $opts as $key => $val ) {
					if ( isset( $key[0] ) && $key[0] == '_' )    // these are non-theme specific settings
					{
						$is_backup = true;
						break;
					}
				}

				if ( $is_backup ) {
					$new_cache = $opts;

				} else {

					$version = absolute_weaver_getopt( 'absolute_weaver_version_id' );    // get something to force load of existing settings
					$new_cache = array();


					global $absolute_weaver_opts_cache;

					// need to clear some settings
					// first, pickup the per-site settings that aren't theme related...


					foreach ( $absolute_weaver_opts_cache as $key => $val ) {
						if ( isset( $key[0] ) && $key[0] == '_' )    // these are non-theme specific settings
						{
							$new_cache[ $key ] = $val;
						}    // keep
					}

					foreach ( $opts as $key => $val ) {             // now, add theme settings from loaded settings
						if ( isset( $key[0] ) && $key[0] != '_' ) {
							$new_cache[ $key ] = $val;
						}    // and add rest from restore
					}
				}

				$new_cache['absolute_weaver_css_saved'] = '';

				$new_cache['style_date'] = date( 'Y-m-d-H:i:s' );

				remove_theme_mod( 'absolute_weaver_settings' );

				set_theme_mod( 'absolute_weaver_settings', $new_cache );

				$absolute_weaver_opts_cache = $new_cache;               // and this loads the new settings into our cache


				absolute_weaver_save_generated_style( );

				do_action( 'absolute_weaver_save_mcecss' );        // theme support plugin saved editor css in file
				do_action( 'absolute_weaver_save_gutenberg_css' );



			} else {

				if ( substr( $contents, 0, 10 ) == 'WXT-V01.00' || substr( $contents, 0, 10 ) != 'WVA-V01.00' ) {
					$type = 'theme';
				} elseif ( substr( $contents, 0, 10 ) == 'WXB-V01.00' || substr( $contents, 0, 10 ) != 'WVB-V01.00' ) {
					$type = 'backup';
				} else {
					$val = substr( $contents, 0, 10 );
					self::$wvrx_error = esc_html__( "Wrong theme file format version", 'absolute-weaver' ) . ':' . $val;
					absolute_weaver_alert( self::$wvrx_error );

					return false;    /* simple check for one of ours */
				}

				$restore = array();
				$restore = unserialize( substr( $contents, 10 ) );

				if ( ! $restore ) {

					print_r( $contents );
					self::$wvrx_error = esc_html__( "Unserialize failed", 'absolute-weaver' );
					absolute_weaver_alert( self::$wvrx_error );

					return false;
				}

				$opts = $restore['weaverx_base'];    // fetch base opts


				if ( isset( $opts['add_css'] ) ) {
					wp_update_custom_css_post( $opts['add_css'] );   // replace with saved version
					unset ( $opts['add_css'] );
				} else {
					wp_update_custom_css_post( '' );   // wipe previous settings
				}

				$version = absolute_weaver_getopt_default( 'absolute_weaver_version_id', '0' );    // get something to force load

				$new_cache = array();
				global $absolute_weaver_opts_cache;
				if ( $type == 'theme' ) {
					// need to clear some settings
					// first, pickup the per-site settings that aren't theme related...


					foreach ( $absolute_weaver_opts_cache as $key => $val ) {
						if ( isset( $key[0] ) && $key[0] == '_' )    // these are non-theme specific settings
						{
							$new_cache[ $key ] = $val;
						}    // keep
					}

					foreach ( $opts as $key => $val ) {
						if ( isset( $key[0] ) && $key[0] != '_' ) {
							$new_cache[ $key ] = $val;
						}    // and add rest from restore
					}

				} elseif ( $type == 'backup' ) {
					foreach ( $opts as $key => $val ) {
						$new_cache[ $key ] = $val;    // overwrite with saved values
					}
				}
				$new_cache['absolute_weaver_version_id'] = $version;
				$new_cache['wvrx_css_saved'] = '';
				$new_cache['last_option'] = ABSOLUTE_WEAVER_THEMENAME;

				$new_cache['style_date'] = date( 'Y-m-d-H:i:s' );

				remove_theme_mod( 'absolute_weaver_settings' );

				set_theme_mod( 'absolute_weaver_settings', $new_cache );

				$absolute_weaver_opts_cache = $new_cache;

				absolute_weaver_save_generated_style( );

				do_action( 'absolute_weaver_save_mcecss' );        // theme support plugin saved editor css in file
				do_action( 'absolute_weaver_save_gutenberg_css' );
			}

			return true;
		}


		static public function enqueue_scripts() {
			// scripts loaded by Absolute_Weaver_Load_WA_Subtheme
			return;
		}

		static public function controls_print_scripts() {
			if ( self::$wvrx_error ) {
				echo '<script> alert( "' . esc_html( self::$wvrx_error ) . '" ); </script>';
			}
		}

	}
}
