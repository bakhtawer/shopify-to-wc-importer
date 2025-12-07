<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}
// Clean up options
delete_option('stwi_shopify_store');
delete_option('stwi_shopify_token');
