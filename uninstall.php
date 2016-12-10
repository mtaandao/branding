<?php
/*  mnBizPlugins Easy Admin Quick Menu
    Copyright 2014  Gabriel Nordeborn  (email : gabriel@mnbizplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  021101301  USA
*/
 
if( ! defined( 'mn_UNINSTALL_PLUGIN' ) )
	exit();


// Delete our options
delete_option( 'custom_mn_login_menu_order' );
delete_option( 'custom_mn_login_css_for_login' );
delete_option( 'custom_mn_login_js_for_login' );
delete_option( 'custom_mn_login_login_url' );
delete_option( 'custom_mn_login_admin_panel_css' );