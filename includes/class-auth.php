<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class STWI_Auth {
    public static function get_headers() {
        $token = get_option('stwi_shopify_token', '');
        return [
            'headers' => [
                'X-Shopify-Access-Token' => $token,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ];
    }
}
