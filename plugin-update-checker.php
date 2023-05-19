<?php
if( ! function_exists( 'my_plugin_check_for_updates' ) ){

    function my_plugin_check_for_updates( $update, $plugin_data, $plugin_file ){

        static $response = false;

        if( empty( $plugin_data['UpdateURI'] ) || ! empty( $update ) )
            return $update;

        if( $response === false )
            $response = wp_remote_get( $plugin_data['UpdateURI'] );

        if( empty( $response['body'] ) )
            return $update;

        $custom_plugins_data = json_decode( $response['body'], true );

        if( ! empty( $custom_plugins_data[ $plugin_file ] ) )
            return $custom_plugins_data[ $plugin_file ];
        else
            return $update;

    }

    add_filter('update_plugins_app.recruiterswebsites.com', 'my_plugin_check_for_updates', 10, 3);

}

?>