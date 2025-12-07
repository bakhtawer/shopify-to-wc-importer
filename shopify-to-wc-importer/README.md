# Shopify → WooCommerce Importer (Generated)

## What you get
- WordPress plugin that adds a "Shopify Importer" admin page.
- A REST endpoint at `/wp-json/stwi/v1/import-products` that imports products from configured Shopify store.
- Admin UI (simple built JS) at Tools > Shopify Importer (menu added).
- React admin source located in `admin/src` and `package.json` to build a real React app locally if you prefer.
- PHPUnit test scaffold under `tests/`.

## Installation
1. Upload `shopify-to-wc-importer` folder to `wp-content/plugins/`.
2. Activate the plugin.
3. Set two options in the database or via code:
   - `stwi_shopify_store` => your-shop-name (e.g. `my-store` for `my-store.myshopify.com`)
   - `stwi_shopify_token` => private app access token
4. Go to the "Shopify Importer" admin menu and click "Start Import".

## Notes
- This is a **starter** plugin. It demonstrates the architecture and a working import flow for simple products. You will likely want to:
  - Map collections to categories
  - Handle multiple images and gallery attachments
  - Import variants as variable products
  - Import customers, orders, and metafields
  - Add error handling and rate-limit support for Shopify API
- To build a full React admin UI, `cd admin && npm install` then `npm run build`. The built assets should output to `admin/build/main.js`.
