<?php

add_action('absolute_weaver_ts_pp_fi_link', 'absolute_weaver_ts_pp_fi_link_action' );

function absolute_weaver_ts_pp_fi_link_action( $postID ) {

?>
	<br/><br/>

	<input type="text" size="30" id='_pp_fi_link' name='_pp_fi_link'
	       value="<?php echo esc_attr( get_post_meta( $postID, '_pp_fi_link', true ) ); ?>"/>
	<?php esc_html_e( 'Featured Image Link - Full URL to override default link target from FI', 'absolute-weaver' ); ?>
	<br style="clear:both;"/>
	<br/>

	<?php
}


add_action('absolute_weaver_head_opts', 'absolute_weaver_ts_head_opts_action');

function absolute_weaver_ts_head_opts_action( ) {
	absolute_weaver_ts_echo_html_code( absolute_weaver_getopt( 'head_opts' ) );
}

function absolute_weaver_ts_echo_html_code( $code ) {
	// This will echo previously sanitized full HTML code, including <style> and <script>
	if ( $code == '')
		return;
	$trimmed = trim( $code );

	if ( $trimmed == ' ' ) {
		return;
	}
	echo wp_check_invalid_utf8( $trimmed );

}

add_action( 'absolute_weaver_inject', 'absolute_weaver_ts_inject_area' );

function absolute_weaver_ts_inject_area( $name ) {

	// determine classes for areas
	$area_name  = '' . $name . '_insert';
	$hide_front = 'hide_front_' . $name;
	$hide_rest  = 'hide_rest_' . $name;

	if ( absolute_weaver_getopt_checked( $hide_front ) && is_front_page() ) {
		return;
	}
	if ( absolute_weaver_getopt_checked( $hide_rest ) && ! is_front_page() ) {
		return;
	}

	$idinj      = 'inject_' . $name;
	$add_class  = "absolute_weaver_inject_area wvrx_{$name}";        // give them all these wrapping classes
	$more_class = absolute_weaver_getopt( 'inject_add_class_' . $name );
	if ( $more_class ) {
		$add_class .= " {$more_class}";
	}
	$add_class = rtrim( $add_class );

	if ( is_customize_preview() ) {
		echo "\t<div class='" . esc_attr( $idinj ) . ' ' . esc_attr( $add_class ) . "'>\n";
		echo wp_kses_post( absolute_weaver_getopt( $area_name ) );
		echo( "\t</div><!-- injection area -->\n" );
	} else {
		echo "\t<div class='" . esc_attr( $idinj ) . ' ' . esc_attr( $add_class ) . "'>\n";
		echo wp_kses_post( absolute_weaver_getopt( $area_name ) );
		echo( "\t</div><!-- injection area -->\n" );
	}
}

add_action('absolute_weaver_header_html', 'absolute_weaver_ts_header_extra_html' );

function absolute_weaver_ts_header_extra_html() {

	// add extra html to header

	$extra = absolute_weaver_get_per_page_value( '_pp_header_html' );
	if ( $extra == '' ) {
		$extra = absolute_weaver_getopt( 'header_html_text' );
	}

	$hide = absolute_weaver_getopt_default( 'header_html_hide', 'hide-none' );

	if ( $extra == '' && is_customize_preview() ) {
		echo '<div id="header-html" style="display:inline;"></div>';        // need the area there for customizer live preview
	} elseif ( $extra != '' && $hide != 'hide' ) {
		$c_class = absolute_weaver_area_class( 'header_html', 'not-pad', '-none', 'margin-none' );

		if ( absolute_weaver_getopt_expand( 'expand_header-html' ) ) {
			$c_class .= ' wvrx-expand-full';
		}

		// see if the content is just an int, assume it to be a post id if so.
		// it seems that if a string has an int in it, the (int) cast will just cast that part, and throw away the rest.
		// we want an int and only an int, so we double cast to test, and that seems to work

		$post_id = (int) trim( $extra );

		if ( (string) $post_id == $extra && $post_id != 0 ) {       // assume a number only is a post id to provide as replacement
			echo wp_kses_post( apply_filters( 'absolute_weaver_page_builder_content', $post_id, 'header-html', $c_class ) );
		} else {
			?>
			<div id="header-html" class="<?php echo esc_attr( $c_class ); ?>">
				<?php echo do_shortcode( wp_kses_post( $extra ) ); ?>
			</div> <!-- #header-html -->
		<?php }
	}
}

//--

if ( ! has_action( 'absolute_weaver_plugin_support' ) ) {
	add_action( 'absolute_weaver_plugin_support', 'absolute_weaver_ts_plugin_support_action' );

	function absolute_weaver_ts_plugin_support_action( $arg ) {
		// add support for Absolute Weaver Plus and Theme Support Plugins
	}
}


