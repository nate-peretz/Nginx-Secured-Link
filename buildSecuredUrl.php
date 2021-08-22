<?php
    function buildSecuredUrl( $url) {
        // Fix session stuff
        if( ! isset( $_SESSION)) session_start();
        if( ! isset( $_SESSION['user_ip'])) $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];

        // Return secured URL
        $expiration = time() + 3100; // 1 Hour
        return $url .'?md5='. md5( $url . $_SERVER['REMOTE_ADDR'] .' <custom string>', true) .'&expires='. $expiration; // 1 Hour
    }
