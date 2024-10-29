<?php

add_filter( 'absolute_weaver_ts_customizer_define_content_sections', 'absolute_weaver_customizer_define_content_sections_filter');

if ( ! function_exists( 'absolute_weaver_customizer_define_content_sections_filter' ) ) :
	/**
	 * Define the sections and settings for the Content panel
	 */
	function absolute_weaver_customizer_define_content_sections_filter( $args = array() ) {
		$panel            = 'absolute_weaver_content';
		$content_sections = array();

		// <head> section

		$content_sections['content-head'] = array(
			'panel' => $panel,
			'title' => __( 'Site <HEAD> Section', 'absolute-weaver' ),

			'options' => array(

				'content-headsec-heading' => absolute_weaver_cz_heading( __( 'Introductory Help for <HEAD> Section', 'absolute-weaver' ),
						__( 'This panel allows you to add HTML to the &lt;HEAD&gt; Section of every page on your site.
PLEASE NOTE: Only minimal validation is made on the field values, so be careful not to use invalid code. Invalid code is usually harmless, but it can make your site display incorrectly. If your site looks broken after making changes here, please double check that what you entered uses valid HTML or CSS rules.', 'absolute-weaver' ) ),


				'head_opts' => absolute_weaver_cz_textarea( __( '&lt;HEAD&gt; Section Content', 'absolute-weaver' ),
					/* $description = */
					__( 'This input area allows you to enter allowed HTML head elements to the &lt;head&gt; section, including &lt;title&gt;, &lt;base&gt;, &lt;link&gt;,&lt;meta&gt;, &lt;script&gt;, and &lt;style&gt;. Code entered into this box is included right before the &lt;/head&gt; HTML tag on each page of your site. We recommend using dedicated WordPress plugins to add things like ad tracking, SEO tags, Facebook code, and so on. Note: You can add CSS Rules using the "Custom CSS Rules" option on the Main Options tab.', 'absolute-weaver' ),
					/* $rows = */
					'4',
					__( 'Any HTML allowed in &lt;head&gt;.', 'absolute-weaver' ),
					'refresh',
					false,
					'absolute_weaver_cz_sanitize_head'),
			),
		);


		/**
		 * Site Header
		 */
		$content_sections['content-header'] = array(
			'panel'   => $panel,
			'title'   => __( 'Site Header Area', 'absolute-weaver' ),
			'options' => array(

				'header_html_text' => absolute_weaver_cz_htmlarea(
					__( 'Header HTML Content', 'absolute-weaver' ),
					__( 'Add arbitrary HTML to Header Area (in &lt;div id="header-html"&gt;)', 'absolute-weaver' ),
					'2',
					'',
					'refresh'),


				'header-image-html-rep-head' => absolute_weaver_cz_group_title( __( 'Replace Header Image with HTML', 'absolute-weaver' ), '' ),

				'header_image_html_text' => absolute_weaver_cz_htmlarea( __( 'Image HTML Replacement', 'absolute-weaver' ),
					__( 'Replace Header image with arbitrary HTML. Useful for slider shortcodes in place of image. FI as Header Image has priority over HTML replacement.', 'absolute-weaver' ),
					'1' ),

				'header_image_html_home_only' => absolute_weaver_cz_checkbox( __( 'Show Replacement only on Front Page', 'absolute-weaver' ),
					__( 'Check to use the Image HTML Replacement only on your Front/Home page.', 'absolute-weaver' ) ),

			),
		);


		/**
		 * Main Menu
		 */

		$wp_logo = absolute_weaver_get_wp_custom_logo_url();

		if ( $wp_logo ) {
			$logo_html = '<br /><br />' . __( 'Current Site Logo: ', 'absolute-weaver' ) . "<img src='" . esc_url( $wp_logo ) . "' style='max-height:36px;margin-left:10px;' />";
		} else {
			$logo_html = '<br /><br />' . __( '***Site Logo has not been set.***', 'absolute-weaver' );
		}

		$content_sections['content-menus'] = array(
			'panel'       => $panel,
			'title'       => __( 'Primary Menu', 'absolute-weaver' ),
			'description' => __( 'Set added content for Primary Menu.', 'absolute-weaver' ),
			'options'     => array(
				'content-mm-heading' => absolute_weaver_cz_group_title( __( 'Primary Menu', 'absolute-weaver' ) ),
				'm_primary_html_right' => absolute_weaver_cz_textarea( __( 'Right HTML', 'absolute-weaver' ),
					'',
					'2'
				),
			)
		);

		/**
		 * Post Specific
		 */
		$content_sections['content-post-specific'] = array(
			'panel'       => $panel,
			'title'       => __( 'Post Specific', 'absolute-weaver' ),
			'description' => __( 'Post Specific Content - override Content.', 'absolute-weaver' ),
			'options'     => array(

				'excerpt_more_msg' => absolute_weaver_cz_htmlarea( __( '"Continue reading" Message', 'absolute-weaver' ),
					__( 'Change default <em>Continue reading &rarr;</em> message for excerpts. You can include HTML ( e.g., &lt;img> ).', 'absolute-weaver' ),
					'1' ),
			),
		);

		/**
		 * Footer
		 */
		$content_sections['content-footer'] = array(
			'panel'   => $panel,
			'title'   => __( 'Footer Area', 'absolute-weaver' ),
			'options' => array(

				'footer_html_text' => array(
					'setting' => array(
						'sanitize_callback' => 'absolute_weaver_cz_sanitize_code',
						'transport'         => 'postMessage',
						'default'           => '',
					),
					'control' => array(
						'control_type' => 'Absolute_Weaver_Textarea_Control',
						'label'        => __( 'Footer HTML Content', 'absolute-weaver' ),
						'description'  => __( 'Add arbitrary HTML to Footer Area (in <&lt;div id="footer-html"&gt;)', 'absolute-weaver' ),
						'type'         => 'textarea',
						'input_attrs'  => array(
							'rows'        => '3',
							'placeholder' => __( '<!-- Add HTML Here -->', 'absolute-weaver' ),
						),
					),
				),

				'copyright' => array(
					'setting' => array(
						'sanitize_callback' => 'absolute_weaver_cz_sanitize_code',
						'transport'         => 'postMessage',
						'default'           => '',
					),
					'control' => array(
						'control_type' => 'Absolute_Weaver_Textarea_Control',
						'label'        => __( '&copy; Site Copyright', 'absolute-weaver' ),
						'description'  => __( 'If you fill this in, the default copyright notice in the footer will be replaced with the text here. It will not automatically update from year to year. Use &amp;copy; to display &copy;. You can use other HTML and shortcodes as well.
Use &amp;nbsp; to hide the copyright notice. &diams;', 'absolute-weaver' ),
						'type'         => 'textarea',
						'input_attrs'  => array(
							'rows'        => '2',
							'placeholder' => __( '<!-- Add HTML Here -->', 'absolute-weaver' ),
						),
					),
				),

				'postfooter_insert' => array(
					'setting' => array(
						'sanitize_callback' => 'absolute_weaver_cz_sanitize_code',
						'transport'         => 'refresh', //'postMessage',
						'default'           => '',
					),
					'control' => array(
						'control_type' => 'Absolute_Weaver_Textarea_Control',
						'label'        => __( 'Post Footer HTML/Javascript Content', 'absolute-weaver' ),
						'description'  => __( 'Add arbitrary HTML or Javascript after the Footer Area ( in &lt;div id="footer-html"&gt; ). Suitable for adding scripts at bottom of site HTML.', 'absolute-weaver' ),
						'type'         => 'textarea',
						'input_attrs'  => array(
							'rows'        => '3',
							'placeholder' => __( '<!-- Add HTML Here -->', 'absolute-weaver' ),
						),
					),
				),

			),
		);

		return $content_sections;
	}
endif;

// code for an add injection area, maybe to add with XPlus...

if ( false ) :        // injection areas
	function absolute_weaver_cz_add_injection( $root, $label = '', $description = '', $version = 'XPlus' ) {
		$opt = array();

		if ( $version == 'XPlus' ) {
			$label .= ABSOLUTE_WEAVER_PLUS_ICON;
		}
		$opt[ $root . '-heading' ] = absolute_weaver_cz_group_title( $label );

		if ( $description ) {
			$opt[ $root . '-desc' ] = array(
				'control' => array(
					'control_type' => 'Absolute_Weaver_Misc_Control',
					'description'  => $description,
					'type'         => 'text',
				),
			);
		}

		if ( $version != 'XPlus' || absolute_weaver_cz_is_plus() ) {

			$opt["{$root}_insert"] = array(
				'setting' => array(
					'sanitize_callback' => 'absolute_weaver_cz_sanitize_html',
					'transport'         => 'postMessage',
					'default'           => '',
				),
				'control' => array(
					'control_type' => 'Absolute_Weaver_Textarea_Control',
					'label'        => __( 'HTML', 'absolute-weaver' ),        // . ABSOLUTE_WEAVER_REFRESH_ICON,
					'type'         => 'textarea',
					'input_attrs'  => array(
						'rows'        => '3',
						'placeholder' => __( 'Any HTML, including shortcodes.', 'absolute-weaver' ),
					),
				),
			);

			$opt["inject_{$root}_bgcolor"] = array(
				'setting' => array(
					'sanitize_callback' => ABSOLUTE_WEAVER_CZ_SANITIZE_COLOR,
					'transport'         => 'postMessage',
					'default'           => absolute_weaver_cz_getopt( "inject_{$root}_bgcolor" ),
				),
				'control' => array(
					'control_type' => ABSOLUTE_WEAVER_COLOR_CONTROL,
					'label'        => __( 'BG Color', 'absolute-weaver' ),
					'description'  => '',
				),
			);


			$opt["inject_{$root}_bgcolor_css"] = absolute_weaver_cz_css( __( 'Custom CSS', 'absolute-weaver' ) );


			$opt["inject_add_class_{$root}"] = array(
				'setting' => array(
					'sanitize_callback' => 'absolute_weaver_cz_sanitize_html',
					'transport'         => 'refresh',
					'default'           => '',
				),
				'control' => array(
					'control_type' => ABSOLUTE_WEAVER_PLUS_TEXT_CONTROL,
					'label'        => __( 'Add Classes', 'absolute-weaver' ) . ABSOLUTE_WEAVER_PLUS_ICON . ABSOLUTE_WEAVER_REFRESH_ICON,
					'description'  => __( 'Space separated class names to add to this area (*Advanced option*) (&starf;Plus)', 'absolute-weaver' ),
					'type'         => 'text',
					'input_attrs'  => array(),
				),
			);
			$opt["hide_front_{$root}"]       = absolute_weaver_cz_checkbox(
				__( 'Hide on front page', 'absolute-weaver' ),
				__( 'If you check this box, then the code from this area will not be displayed on the front ( home ) page.', 'absolute-weaver' )
			);

			$opt["hide_rest_{$root}"] = absolute_weaver_cz_checkbox(
				__( 'Hide on non-front pages', 'absolute-weaver' ),
				__( 'If you check this box, then the code from this area will not be displayed on non-front pages.', 'absolute-weaver' )
			);


		} // is plus
		$opt[ $root . '-line-break' ] = array(
			'control' => array(
				'control_type' => 'Absolute_Weaver_Misc_Control',
				'label'        => '',
				'type'         => 'line',
			),
		);


		return $opt;

	}
endif;
