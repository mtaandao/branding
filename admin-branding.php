<?php
 /*
Plugin Name: Custom Branding
Plugin URI: http://www.mnbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=pluginuri
Description: Brand the Mtaandao admin section and login screen.
Version: 1.1.2
Author: Gabriel Nordeborn
Author URI: http://www.mnbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=authoruri
Text Domain: custom-mn-login
*/
 

 require_once( dirname( __FILE__ ) . '/slate.php' );
// Include Redux if plugin isn't available
if ( ! class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' ) ) {

    require_once( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' );

}

require_once( dirname( __FILE__ ) . '/inc/redux-config.php' );
require_once( dirname( __FILE__ ) . '/inc/custom-functions.php' );         // Import our custom functions first

// Register activation hook
register_activation_hook( __FILE__, 'custom_mn_login_activation_function' );

// Load localization
function custom_mn_login_init_plugin() {

    load_plugin_textdomain( 'custom-mn-login', false, dirname( __FILE__ ) . '/lang' );

}

add_action( 'admin_init', 'custom_mn_login_init_plugin' );

/**
 * Function for redirecting to login page if option is set.
 *
 * @since 1.0.0
 *
 */

function custom_mn_login_do_redirect() {

    // Get the login URL
    $login_url = get_option( 'custom_mn_login_login_url' );
    $login_url = sanitize_title_with_dashes( $login_url );

    if( ( isset( $login_url ) ) && ( $login_url != '' ) ) {

        $match_url = get_site_url() . '/' . $login_url;

        // Set the base protocol to http
        $protocol = 'http';
        // check for https
        if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
            $protocol .= "s";
        }

        $current_url = $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

        if( $current_url == $match_url ) {

            $redir_url = admin_url();
            //header ('HTTP/1.1 301 Moved Permanently');
            header ('Location: ' . $redir_url);
            exit();

        }

    }

}

custom_mn_login_do_redirect();

function custom_mn_login_load_custom_mn_admin_style() {

        // Custom CSS
        mn_register_style( 'mnbizplugins-admin-customization', plugins_url( '/assets/css/admin-customization.css', __FILE__ ), false, '1.0.0' );
        mn_enqueue_style( 'mnbizplugins-admin-customization' );

}

add_action( 'admin_enqueue_scripts', 'custom_mn_login_load_custom_mn_admin_style' );

/**
 * Function for outputting the admin panel modification CSS, if needed
 *
 * @since 1.0.0
 *
 */

function custom_mn_login_output_css_for_admin_panel() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'show_extra_menu_styling' ] ) ) && ( $custom_mn_login_options[ 'show_extra_menu_styling' ] == true ) ) {

        $css = get_option( 'custom_mn_login_admin_panel_css' );
        echo $css;

    }
}

add_action( 'admin_head', 'custom_mn_login_output_css_for_admin_panel' );

/**
 * Modify the admin title.
 *
 */

function custom_mn_login_admin_title( $admin_title, $title ) {

    return get_bloginfo( 'name' ).' &bull; ' . $title;

}

/**
 * Wrapper function for changing the admin title
 *
 */

function custom_mn_login_admin_title_wrapper() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'remove_mn_from_title' ] ) ) && ( $custom_mn_login_options[ 'remove_mn_from_title' ] == true ) ) add_filter( 'admin_title', 'custom_mn_login_admin_title', 10, 2 );

}

add_action( 'admin_init', 'custom_mn_login_admin_title_wrapper' );

/**
 * Function for adding a custom logo to the admin menu
 *
 * @since 1.0.0
 *
 */

function custom_mn_login_add_logo_to_admin_menu() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'menu_logo' ][ 'url' ] ) ) && ( $custom_mn_login_options[ 'menu_logo' ][ 'url' ] != '' ) ) {

        $image = '<img id="admin-menu-logo" src="' . $custom_mn_login_options[ 'menu_logo' ][ 'url' ] . '">';

        if( ( isset( $custom_mn_login_options[ 'menu_logo_link_url' ] ) ) && ( $custom_mn_login_options[ 'menu_logo_link_url' ] != '' ) ) {

            if( ( isset( $custom_mn_login_options[ 'menu_logo_link_newwindow' ] ) ) && ( $custom_mn_login_options[ 'menu_logo_link_newwindow' ] == true ) ) $extra_html = ' target="_blank"'; else $extra_html = '';

            $image = '<a href="' . $custom_mn_login_options[ 'menu_logo_link_url' ] . '"' .  $extra_html . '>' . $image . '</a>';

        }

        ?>
        <script type="text/javascript">
            jQuery( document ).ready( function() {
                jQuery( '#adminmenuwrap' ).prepend( '<div id="admin-menu-logo-container"><?php echo $image; ?></div>');
            });
        </script>
        <style type="text/css">
        #admin-menu-logo-container {
            padding: 10px 10px 10px 10px;
        }
        #admin-menu-logo {
            display: block;
            max-height: 100%;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            vertical-align: middle;
        }

        </style>
        <?php
 
    }
}

add_action( 'admin_footer', 'custom_mn_login_add_logo_to_admin_menu' );

/**
 * Add custom text to the footer
 *
 * @since 1.0.2
 *
 */

function custom_mn_login_add_custom_text_to_footer() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'footer_text' ] ) ) && ( $custom_mn_login_options[ 'footer_text' ] != '' ) ) {

        echo apply_filters( 'the_content', $custom_mn_login_options[ 'footer_text' ] );
 
    }

}

add_action( 'in_admin_footer', 'custom_mn_login_add_custom_text_to_footer' );

/**
 * Function for removing unwanted dashboard metaboxes.
 *
 * @since 1.0.0
 *
 */

function custom_mn_login_remove_unwanted_dashboard_widgets() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'show_rightnow' ] ) ) && ( $custom_mn_login_options[ 'show_rightnow' ] == false ) ) remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    if( ( isset( $custom_mn_login_options[ 'show_activity' ] ) ) && ( $custom_mn_login_options[ 'show_activity' ] == false ) ) remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
    if( ( isset( $custom_mn_login_options[ 'show_quickpress' ] ) ) && ( $custom_mn_login_options[ 'show_quickpress' ] == false ) ) remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    if( ( isset( $custom_mn_login_options[ 'show_primary' ] ) ) && ( $custom_mn_login_options[ 'show_primary' ] == false ) ) remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

}

add_action( 'mn_dashboard_setup', 'custom_mn_login_remove_unwanted_dashboard_widgets', 9999 );

/**
 * Use a transient to set and output the CSS we want to hide in the admin section.
 *
 * @since 1.0
 *
 */

function custom_mn_login_load_css_to_hide_transient() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }


    // Get any existing copy of our transient data
    if ( ( $css = get_transient( 'custom_mn_login_css_to_hide' ) ) === false ) {

        // It wasn't there, so regenerate the data and save the transient
         $css = custom_mn_login_get_css_to_hide( $custom_mn_login_options[ 'css_to_hide' ] );
         set_transient( 'custom_mn_login_css_to_hide', $css, 24 * HOUR_IN_SECONDS );

    }

    // Use the data like you would have normally...
    echo $css;

}

add_action( 'admin_head', 'custom_mn_login_load_css_to_hide_transient' );

/**
 * Function for changing the name of the dashboard.
 *
 * @since 1.0.0
 */

function custom_mn_login_change_dashboard_name( $menu ) {  

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        return $menu;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return $menu;
    }


    if( ( is_admin() ) && ( isset( $custom_mn_login_options[ 'dashboard_name' ] ) ) && ( $custom_mn_login_options[ 'dashboard_name' ] != '' ) ) {

        foreach( $menu as $id => $item_array ) {

            if( $item_array[0] == 'Dashboard' ) $menu[ $id ][ 0 ] = $custom_mn_login_options[ 'dashboard_name' ];

        }

    }

    return $menu;
}

add_filter( 'add_menu_classes', 'custom_mn_login_change_dashboard_name' );

function custom_mn_login_change_dashboard_name_on_dashboard() {

    global $custom_mn_login_options;

    if( ( is_admin() ) && ( isset( $custom_mn_login_options[ 'dashboard_name'] ) ) && ( $custom_mn_login_options[ 'dashboard_name'] != '' ) ) {

        echo '<script type="text/javascript">jQuery( document ).ready( function() {
        jQuery( "div#mnwrap div#mncontent div#mnbody div#mnbody-content div.wrap h2" ).text("' . $custom_mn_login_options[ 'dashboard_name'] . '");
    });</script>';

    }

}

add_action('admin_head-index.php', 'custom_mn_login_change_dashboard_name_on_dashboard');

/**
 * Add your own CSS and JS to the admin section. 
 *
 * @since 1.0.0
 *
 */

function custom_mn_login_load_custom_admin_css() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    $css_general = get_option( 'custom_mn_login_admin_general_css' );

    if( $css_general != false ) echo $css_general;

    echo '<style type="text/css">';

    /**
     * BUTTONS
     *
     */

    if( ( isset( $custom_mn_login_options[ 'show_extra_button_styling' ] ) ) && ( $custom_mn_login_options[ 'show_extra_button_styling' ] == true ) ) {

        echo '.button-primary { 
            background: none repeat scroll 0% 0% ' . $custom_mn_login_options[ 'button_color_background' ] . ' !important; 
            border-color: ' . $custom_mn_login_options[ 'button_color_border' ] . ' !important;
            box-shadow: 0px 1px 0px rgba(150, 150, 150, 0.5) inset, 0px 1px 0px rgba(0, 0, 0, 0.15) !important;
        }'; 

        echo '.button-primary:hover { 
            background: none repeat scroll 0% 0% ' . $custom_mn_login_options[ 'button_color_border' ] . ' !important; 
            border-color: ' . $custom_mn_login_options[ 'button_color_background' ] . ' !important;
            box-shadow: none !important;
        }'; 

    }

    if( ( isset( $custom_mn_login_options[ 'admin_custom_css'] ) ) && ( $custom_mn_login_options[ 'admin_custom_css'] != '' ) ) {
        echo $custom_mn_login_options[ 'admin_custom_css' ];
    }

    echo '</style>';

   /*echo '<style type="text/css">
    #adminmenuwrap {
        height: 100%;
    }
    </style>';*/

}

add_action( 'admin_head', 'custom_mn_login_load_custom_admin_css' );

function custom_mn_login_load_custom_admin_js() {

    global $custom_mn_login_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $custom_mn_login_options[ 'show_for_admin' ] ) ) && ( $custom_mn_login_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $custom_mn_login_options[ 'capability_threshold' ] ) ) && ( current_user_can( $custom_mn_login_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $custom_mn_login_options[ 'admin_custom_js'] ) ) && ( $custom_mn_login_options[ 'admin_custom_js'] != '' ) ) {
        echo '<script type="text/javascript">' . $custom_mn_login_options[ 'admin_custom_js' ] . '</script>';
    }

}

add_action( 'admin_footer', 'custom_mn_login_load_custom_admin_js' );

/**
 *
 * Functions for styling the login page.
 *
 */

function custom_mn_login_style_login_page() { 

    $css = get_option( 'custom_mn_login_css_for_login' );

    echo $css;

}

add_action( 'login_head', 'custom_mn_login_style_login_page' );

function custom_mn_login_js_for_login_page() { 

    $js = get_option( 'custom_mn_login_js_for_login' );

    echo $js;

}

add_action( 'login_footer', 'custom_mn_login_js_for_login_page' );

/**
 * Change login title and link
 *
 * @since 1.1.1
 *
 */

function custom_mn_login_login_link( $login_header_url ) {

    global $custom_mn_login_options;

    if( ( isset( $custom_mn_login_options[ 'login_link' ] ) ) && ( $custom_mn_login_options[ 'login_link' ] != '' ) ) $login_header_url = $custom_mn_login_options[ 'login_link' ];

    return $login_header_url;

}

add_filter( 'login_headerurl', 'custom_mn_login_login_link' );

function custom_mn_login_login_title( $login_header_title ) {

    global $custom_mn_login_options;

    if( ( isset( $custom_mn_login_options[ 'login_title' ] ) ) && ( $custom_mn_login_options[ 'login_title' ] != '' ) ) $login_header_title = $custom_mn_login_options[ 'login_title' ];

    return $login_header_title;

}

add_filter( 'login_headertitle', 'custom_mn_login_login_title' );

function custom_mn_login_login_link_blank() {

    global $custom_mn_login_options;

    if( ( isset( $custom_mn_login_options[ 'login_link' ] ) ) && ( $custom_mn_login_options[ 'login_link' ] != '' ) ) {
        echo '<script type="text/javascript">jQuery( document ).ready( function() {
            jQuery( "#login a" ).attr( "target", "_blank" );
        });
        </script>';
    }

}

add_action( 'login_footer', 'custom_mn_login_login_link_blank' );