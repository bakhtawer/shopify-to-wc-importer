<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class STWI_Shopify_API {

    /**
     * Get products from Shopify.
     * Supports overriding via 'stwi_test_products' filter for unit tests.
     *
     * @param string|null $page_info
     * @param int $limit
     * @return array|WP_Error
     */
    public static function get_products($page_info = null, $limit = 50) {
        // Allow tests to inject products
        $override = apply_filters('stwi_test_products', null, $page_info, $limit);
        if ( is_array($override) ) {
            return $override;
        }

        $shop = get_option('stwi_shopify_store', '');
        if ( empty($shop) ) return new WP_Error('no_shop', 'Shopify store not configured.');

        $url = esc_url_raw("https://{$shop}.myshopify.com/admin/api/2024-01/products.json?limit={$limit}");
        if ( $page_info ) {
            $url .= '&page_info=' . urlencode($page_info);
        }

        $args = STWI_Auth::get_headers();
        $response = wp_remote_get($url, $args);

        if ( is_wp_error($response) ) return $response;

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return new WP_Error('invalid_response', 'Could not decode Shopify response.');
        }
        return $body['products'] ?? [];
    }

    // Basic example to fetch a single product by id
    public static function get_product($id) {
        $shop = get_option('stwi_shopify_store', '');
        if ( empty($shop) ) return new WP_Error('no_shop', 'Shopify store not configured.');
        $url = esc_url_raw("https://{$shop}.myshopify.com/admin/api/2024-01/products/{$id}.json");
        $args = STWI_Auth::get_headers();
        $response = wp_remote_get($url, $args);
        if ( is_wp_error($response) ) return $response;
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['product'] ?? null;
    }
}
