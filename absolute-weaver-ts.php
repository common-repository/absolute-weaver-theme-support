<?php
/*
Plugin Name: Absolute Weaver Theme Support
Plugin URI: http://weavertheme.com/plugins
Description: Absolute Weaver Theme Support - adds save/restore settings, advanced CSS options, and extra Added Content Areas to the theme options Customize menus. This plugin has no additional settings of its own.
Author: wpweaver
Author URI: http://weavertheme.com/about/
Version: 1.0.6
License: GPL V3

Absolute Weaver Theme Support

Copyright (C) 2019 Bruce E. Wampler - weaver@weavertheme.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.
*/


/* CORE FUNCTIONS
*/
$theme = get_template_directory();

function absolute_weaver_ts_alert($msg) {
	echo "<script> alert('" . esc_html($msg) . "'); </script>";
}


if ( strpos( $theme, '/absolute-weaver') !== false ) {		// only load if Absolute Weaver is the theme

define ('ABSOLUTE_WEAVER_TS_VERSION','1.0.4');


function absolute_weaver_ts_installed() {
    return ABSOLUTE_WEAVER_TS_VERSION;
}


// absolute_weaver_ts_save_restore
add_action('absolute_weaver_ts_load_save','absolute_weaver_ts_load_save_action');

function absolute_weaver_ts_load_save_action( $args = '' ) {

	require_once( dirname(__FILE__) . '/includes/save-restore.php');

}

require_once( dirname(__FILE__) . '/includes/ts-actions.php');
require_once( dirname(__FILE__) . '/includes/ts-content.php');
require_once( dirname(__FILE__) . '/includes/ts-customizer.php');



} // end only load if Absolute Weaver installed
