<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class STWI_Importer {

    // REST endpoint handler
    public static function import_products_endpoint( $request ) {
        if ( ! current_user_can('manage_options') ) {
            return new WP_Error('forbidden', 'You do not have permission.', ['status'=>403]);
        }

        $products = STWI_Shopify_API::get_products();
        if ( is_wp_error($products) ) return $products;

        $imported = 0;
        $log = [];

        foreach ( $products as $product ) {
            $res = self::import_single_product($product);
            if ( $res ) {
                $imported++;
            } else {
                $log[] = 'Failed to import product: ' . ($product['title'] ?? 'unknown');
            }
        }

        return rest_ensure_response([
            'status' => 'success',
            'imported' => $imported,
            'log' => $log,
        ]);
    }

    public static function import_single_product( $product ) {
        // Basic product insertion; adapt and expand for variations, categories, attributes etc.
        $postarr = [
            'post_title' => wp_strip_all_tags( $product['title'] ?? 'Unnamed' ),
            'post_content' => $product['body_html'] ?? '',
            'post_type' => 'product',
            'post_status' => 'publish',
        ];

        $post_id = wp_insert_post( $postarr );

        if ( is_wp_error($post_id) || $post_id == 0 ) {
            return false;
        }

        // Basic metadata (first variant)
        if ( ! empty($product['variants'][0]) ) {
            $variant = $product['variants'][0];
            update_post_meta($post_id, '_sku', sanitize_text_field($variant['sku'] ?? ''));
            update_post_meta($post_id, '_regular_price', sanitize_text_field($variant['price'] ?? ''));
            update_post_meta($post_id, '_price', sanitize_text_field($variant['price'] ?? ''));
        }

        // Handle featured image (single)
        if ( ! empty($product['image']['src']) ) {
            // Use media_sideload_image and set as featured if possible
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $image = media_sideload_image($product['image']['src'], $post_id, null, 'src');
            // media_sideload_image returns HTML on success; we attempt to attach the last added image
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'posts_per_page' => 1,
                'post_status' => null,
                'post_parent' => $post_id,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            if ( ! empty($attachments) ) {
                set_post_thumbnail($post_id, $attachments[0]->ID);
            }
        }

        return true;
    }
}
