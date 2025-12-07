<?php
class STWI_Importer_Test extends WP_UnitTestCase {

    public function setUp(): void {
        parent::setUp();
        // Ensure options exist
        update_option('stwi_shopify_store', 'example-store');
        update_option('stwi_shopify_token', 'dummy-token');
    }

    public function tearDown(): void {
        // Clean up created posts
        $posts = get_posts(array('post_type'=>'product','numberposts'=>-1));
        foreach($posts as $p){ wp_delete_post($p->ID, true); }
        parent::tearDown();
    }

    public function test_settings_saved() {
        $store = get_option('stwi_shopify_store');
        $token = get_option('stwi_shopify_token');
        $this->assertEquals('example-store', $store);
        $this->assertEquals('dummy-token', $token);
    }

    public function test_import_single_product_inserts_post() {
        $sample = array(
            'title' => 'Test Product',
            'body_html' => '<p>Test product description</p>',
            'variants' => array(array('sku'=>'TST-001','price'=>'19.99')),
            'image' => array('src' => '')
        );
        $res = STWI_Importer::import_single_product($sample);
        $this->assertTrue($res);

        $products = get_posts(array('post_type'=>'product','numberposts'=>-1));
        $this->assertCount(1, $products);
        $this->assertEquals('Test Product', $products[0]->post_title);
        $this->assertEquals('TST-001', get_post_meta($products[0]->ID,'_sku',true));
    }

    public function test_import_endpoint_with_mocked_products() {
        // Provide mock products via filter
        add_filter('stwi_test_products', function($null){
            return array(
                array(
                    'title' => 'Mocked Product A',
                    'body_html' => 'desc A',
                    'variants' => array(array('sku'=>'A1','price'=>'9.99')),
                    'image' => array('src' => '')
                ),
                array(
                    'title' => 'Mocked Product B',
                    'body_html' => 'desc B',
                    'variants' => array(array('sku'=>'B1','price'=>'29.99')),
                    'image' => array('src' => '')
                )
            );
        });

        // Call REST endpoint
        $request = new WP_REST_Request('POST', '/stwi/v1/import-products');
        $request->set_header('X-WP-Nonce', wp_create_nonce('wp_rest'));
        $response = rest_do_request($request);

        $this->assertEquals(200, $response->get_status());
        $data = $response->get_data();
        $this->assertEquals('success', $data['status']);
        $this->assertEquals(2, $data['imported']);

        // Ensure two products created
        $products = get_posts(array('post_type'=>'product','numberposts'=>-1));
        $this->assertCount(2, $products);
    }
}
