<?php
if (!defined('ABSPATH')) exit;

class STWI_Settings_Page {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'register_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function register_settings_page() {
        add_submenu_page(
            'stwi-importer',
            'Shopify Importer Settings',
            'Settings',
            'manage_options',
            'stwi-settings',
            [__CLASS__, 'render_settings_page']
        );
    }

    public static function register_settings() {

        register_setting('stwi_settings_group', 'stwi_shopify_store', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);

        register_setting('stwi_settings_group', 'stwi_shopify_token', [
            'sanitize_callback' => 'sanitize_text_field'
        ]);

        add_settings_section(
            'stwi_settings_section_main',
            'Shopify API Configuration',
            function() {
                echo '<p>Enter your Shopify credentials. These are required for importing products.</p>';
            },
            'stwi-settings'
        );

        add_settings_field(
            'stwi_shopify_store',
            'Shopify Store Name',
            function() {
                $val = get_option('stwi_shopify_store', '');
                echo '<input type="text" name="stwi_shopify_store" value="' . esc_attr($val) . '" class="regular-text" placeholder="your-store-name">';
                echo '<p class="description">Example: <strong>your-shop</strong> (for your-shop.myshopify.com)</p>';
            },
            'stwi-settings',
            'stwi_settings_section_main'
        );

        add_settings_field(
            'stwi_shopify_token',
            'Shopify Access Token',
            function() {
                $val = get_option('stwi_shopify_token', '');
                echo '<input type="text" name="stwi_shopify_token" value="' . esc_attr($val) . '" class="regular-text" placeholder="Shopify Admin API Token">';
                echo '<p class="description">Get this from Shopify Admin → Apps → Custom App → API Credentials.</p>';
            },
            'stwi-settings',
            'stwi_settings_section_main'
        );
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Shopify Importer Settings</h1>
            <form method="post" action="options.php" style="max-width:600px;">
                <?php
                    settings_fields('stwi_settings_group');
                    do_settings_sections('stwi-settings');
                    submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
}

STWI_Settings_Page::init();
