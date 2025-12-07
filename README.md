# Shopify → WooCommerce Importer (Generated)

## What you get
- WordPress plugin that adds a "Shopify Importer" admin page.
- A Settings page to store Shopify Store name and Admin API token.
- A REST endpoint at `/wp-json/stwi/v1/import-products` that imports products from configured Shopify store.
- Admin UI (simple built JS) at plugin menu.
- React admin source located in `admin/src` and `package.json` to build a real React app locally if you prefer.
- PHPUnit test scaffold under `tests/`. Tests include mocking Shopify responses via `stwi_test_products` filter.

## Installation
1. Upload `shopify-to-wc-importer` folder to `wp-content/plugins/`.
2. Activate the plugin.
3. Go to **Shopify Importer → Settings** and enter:
   - **Shopify Store Name** (e.g. `my-shop` for `my-shop.myshopify.com`)
   - **Shopify Access Token** (private app token)
4. Go to **Shopify Importer** and click "Start Import".

## Testing
- The tests mock Shopify API responses using the `stwi_test_products` filter.
- To run tests you need a configured WP PHPUnit environment.

## Next steps
Expand import logic to support:
- Product variants → variable products
- Multiple images and gallery attachments
- Collections → categories
- Orders & customers
- Rate-limit handling & pagination
