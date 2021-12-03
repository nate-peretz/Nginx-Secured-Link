<?php
    function buildSecuredUrl( $url) {
        // Fix session stuff
        if( ! isset( $_SESSION)) session_start();
        if( ! isset( $_SESSION['user_ip'])) $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];

        // Return secured URL
        $expiration = time() + 3100; // 1 Hour
        return $url .'?md5='. md5( $url . $_SERVER['REMOTE_ADDR'] .' <custom string>') .'&expires='. $expiration; // 1 Hour
    }

    function validateSecuredURL( $url) {
        // Get full URL with params
        $parsed_url = parse_url( $url);

        // Check for md5 param
        if( isset( $parsed_url['query']) && strpos( $parsed_url['query'], '&') !== false && strpos( $parsed_url['query'], 'md5=') !== false) {
            $params  = explode('&', $parsed_url['query']);
            $md5     = explode('=', $params[0])[1];
            $expires = explode('=', $params[1])[1];

            // Check if expired
            if( time() > $expires) {
                header('HTTP/1.0 403 Forbidden');
                die('403: Forbidden');
            }

            $user_ip = $_SESSION['user_ip'];
            $path = $parsed_url['path'];

            // Validate md5
            $md5_test = md5("$path$user_ip <custom string>");
            if( $md5 !== $md5_test) {
                header('HTTP/1.0 403 Forbidden');
                die('403: Forbidden');
            }
        }

        // If check is not required..
        return true;
    }
