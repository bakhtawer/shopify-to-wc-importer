<?php
// Basic bootstrap for WP PHPUnit tests. For real use, configure WP test environment.
// This file assumes you installed WordPress PHPUnit test framework and have WP test constants set.
if ( file_exists( __DIR__ . '/../shopify-to-wc-importer.php' ) ) {
    require_once __DIR__ . '/../shopify-to-wc-importer.php';
}
