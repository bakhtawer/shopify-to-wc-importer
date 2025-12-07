<?php
class STWI_Importer_Test extends WP_UnitTestCase {

    public function test_import_endpoint_returns_array() {
        // We cannot call external Shopify in unit tests here; instead test that the endpoint exists and returns something when shop not configured.
        $request = new WP_REST_Request('POST', '/stwi/v1/import-products');
        $response = rest_do_request($request);
        $this->assertNotNull($response);
        $data = $response->get_data();
        $this->assertArrayHasKey('status', $data);
    }
}
